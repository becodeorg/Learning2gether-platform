<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Domain\ImageManager;
use App\Entity\Chapter;
use App\Entity\Image;
use App\Entity\PwdResetToken;
use App\Entity\User;
use App\Entity\UserChapter;
use App\Form\EditProfileType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function index(Request $request, Swift_Mailer $mailer): Response
    {
        $badgrHandler = new Badgr;

        /** @var User $user */
        $user = $this->getUser();

        //get all badges from user
        $badges = $user->getBadges()->getValues();
        //put all badge keys in userBadges
        $badgeKeys = [];
        foreach ($badges as &$badgeData) {
            $badgeKey = $badgeData->getBadge();
            $badgeKeys[] = $badgeKey;
        }
        $userBadges = $badgrHandler->getAllBadges($badgeKeys, $user);

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        $deleteBtn = $this->createFormBuilder()
            ->add('delete_user', SubmitType::class)
            ->getForm();
        $deleteBtn->handleRequest($request);

        if ($deleteBtn->isSubmitted() && $deleteBtn->isValid()){
            return $this->deleteUser($mailer);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $imageManager = new ImageManager();
            $imageManager->fixUploadsFolder($this->getParameter('uploads_directory'), $this->getParameter('public_directory'));
            $avatarImage = $this->getDoctrine()->getRepository(Image::class)->findOneBy(['type' => 'avatar', 'user' => $user->getId()]);
            // check if there was an previous image for that user
            if(!$avatarImage){
                $newImage = $imageManager->createImage($request->files->get('edit_profile')['avatar'], $user, $this->getParameter('uploads_directory'), 'avatar');
                $user->setAvatar($newImage->getSrc());
                $this->getDoctrine()->getManager()->persist($newImage);
            } else {
            $user = $imageManager->changeUserAvatarImage($request->files->get('edit_profile')['avatar'], $avatarImage, $user, $this->getParameter('uploads_directory'));
            }
            $this->flushUpdatedUser($user);
        }

        return $this->render('profile/index.html.twig', [
            'badgeKeys' => $badgeKeys,
            'userBadges' => $userBadges,
            'user' => $user,
            'profileForm' => $form->createView(),
            'delete' => $deleteBtn->createView(),
        ]);
    }

    /**
     * @param User $user
     */
    public function flushUpdatedUser(User $user): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    /**
     * @param Swift_Mailer $mailer
     * @return RedirectResponse
     */
    public function deleteUser(Swift_Mailer $mailer): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $imageManager = new ImageManager();
        /* @var User $user */
        $user = $this->getUser();
        $userEmail = $user->getEmail();
        $this->get('security.token_storage')->setToken(null);
        $userProgress = $user->getProgress();
        foreach ($userProgress as $item) {
            $user->removeProgress($item);
        }
        $userImages = $this->getDoctrine()->getRepository(Image::class)->findBy(['user' => $user]);
        foreach ($userImages as $userImage) {
            $imageManager->removeUpload($userImage->getSrc(), $this->getParameter('uploads_directory'));
        }
        $userPwdTokens = $this->getDoctrine()->getRepository(PwdResetToken::class)->findBy(['user' => $user]);
        if (!empty($userPwdTokens)){
            foreach ($userPwdTokens as $userPwdToken){
                $em->remove($userPwdToken);
            }
        }
        $this->sendDeleteEmail($userEmail, $mailer);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('portal');
    }

    public function sendDeleteEmail(string $userEmail, Swift_Mailer $mailer): void
    {
        $message = (new Swift_Message('User deleted confirmation'))
            ->setFrom('no-reply@example.com')
            ->setTo($userEmail)
            ->setBody($this->renderView('profile/dltmail.html.twig'), 'text/html');
        $mailer->send($message);
    }
}
