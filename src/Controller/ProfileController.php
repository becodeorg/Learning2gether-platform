<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Domain\ImageManager;
use App\Entity\Image;
use App\Entity\LearningModule;
use App\Entity\User;
use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $badgrHandler->initialise();

        /** @var User $user */
        $user = $this->getUser();

        $accessToken = $this->getAccessToken();

        //get all badges from user
        $badges = $user->getBadges()->getSnapshot();
        //put all badge keys in userBadges
        $badgeKeys = [];
        foreach ($badges as &$badgeData) {
            $badgeKey = $badgeData->getBadge();
            $badgeKeys[] = $badgeKey;
        }
        $userBadges = $badgrHandler->getAllBadges($badgeKeys, $user, $accessToken);

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

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

    private function getAccessToken(){
        return $_SESSION['accessToken'];
    }
}
