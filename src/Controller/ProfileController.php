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

        /** @var User $user */
        $user = $this->getUser();

//        //initialise badgr object
//        $badgrObj = new Badgr;
//
//        function getSession(Badgr $badgrObj){
//            //check if we already have refreshtoken
//            if(isset($_SESSION['refreshToken'])){
//                $refreshToken = $_SESSION['refreshToken'];
//                $badgrObj->getTokenData($refreshToken);
//            }
//            //if we don't, do the initial authentication to get it
//            else{
//                //this getPassword is so I don't reveal my personal pass, we use my (Tim) account for badgr atm
//                $password = $badgrObj->getPassword();
//                $badgrObj->initialise($password);
//            }
//        }
//
//        function getAccessToken(Badgr $badgrObj){
//            $accessToken = $_SESSION['accessToken'];
//            return $accessToken;
//            // disgusting
//        }
//
//        getSession($badgrObj);
//        $accessToken = getAccessToken($badgrObj);


        //For some unholy reason this is required for the rest to work
//        $testModule = $this->getDoctrine()->getRepository(LearningModule::class)->find('1');
//        $user->addBadge($testModule);

        //get all badges from user
        $badges = $user->getBadges()->getSnapshot();
        //put all badge keys in userBadges
        $badgeKeys = [];
        foreach ($badges as &$badgeData) {
            $badgeKey = $badgeData->getBadge();
            array_push($badgeKeys, $badgeKey);
        }
        //pass userBadges with keys and the user to the getAllBadges method
        $userBadges = $badgrHandler->getAllBadges($badgeKeys, $user);
//        $userBadges = $badgrObj->getAllBadges($badgeKeys, $user, $accessToken);

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageManager = new ImageManager();
            $avatarImage = $this->getDoctrine()->getRepository(Image::class)->findOneBy(['type' => 'avatar', 'user' => $user->getId()]);
            $user = $imageManager->changeUserAvatarImage($request->files->get('edit_profile')['avatar'], $avatarImage, $user, $this->getParameter('uploads_directory'));
            $this->flushUpdatedUser($user);
        }

        return $this->render('profile/index.html.twig', [
//            'badgeKeys' => $badgeKeys,
//            'userBadges' => $userBadges,
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
}
