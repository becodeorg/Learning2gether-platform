<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Domain\ImageManager;
use App\Entity\Image;
use App\Entity\PwdResetToken;
use App\Entity\User;
use App\Form\EditProfileType;
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
     * @return Response
     */
    public function index(Request $request): Response
    {
        $badgrHandler = new Badgr;

        /** @var User $user */
        $user = $this->getUser();

        //get all badges from user
        $badges = $user->getBadges()->getSnapshot();
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
            return $this->deleteUser();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $imageManager = new ImageManager();
            $imageManager->fixUploadsFolder($this->getParameter('uploads_directory'), $this->getParameter('public_directory'));
            $avatarImage = $this->getDoctrine()->getRepository(Image::class)->findOneBy(['type' => 'avatar', 'user' => $user->getId()]);
            $user = $imageManager->changeUserAvatarImage($request->files->get('edit_profile')['avatar'], $avatarImage, $user, $this->getParameter('uploads_directory'));
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
     * @return RedirectResponse
     */
    public function deleteUser(): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $imageManager = new ImageManager();
        $user = $this->getUser();
        $this->get('security.token_storage')->setToken(null);
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
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('portal');
    }
}
