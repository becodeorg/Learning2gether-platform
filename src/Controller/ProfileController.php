<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Entity\LearningModule;
use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request): Response
    {
        //initialise badgr object
        $badgrObj = new Badgr;

        function getSession(Badgr $badgrObj){
            //check if we already have refreshtoken
            if(isset($_SESSION['refreshToken'])){
                $refreshToken = $_SESSION['refreshToken'];
                $badgrObj->getTokenData($refreshToken);
            }
            //if we don't, do the initial authentication to get it
            else{
                //this getPassword is so I don't reveal my personal pass, we use my (Tim) account for badgr atm
                $password = $badgrObj->getPassword();
                $badgrObj->initialise('loderunner666');
            }
        }

        function getAccessToken(Badgr $badgrObj){
            $accessToken = $_SESSION['accessToken'];
            return $accessToken;
        }

        getSession($badgrObj);
        $accessToken = getAccessToken($badgrObj);

        $user = $this->getUser();

        //For some unholy reason this is required for the rest to work
        $testModule = $this->getDoctrine()->getRepository(LearningModule::class)->find('1');
        $user->addBadge($testModule);

        //get all badges from user
        $badgesData = $user->getBadges();
        $badges = $badgesData->getSnapshot();
        //put all badge keys in userBadges
        $badgeKeys = [];
        foreach ($badges as &$badgeData) {
            $badgeKey = $badgeData->getBadge();
            $badgeKeys[] = $badgeKey;
        }
        //pass userBadges with keys and the user to the getAllBadges method
        $userBadges = $badgrObj->getAllBadges($badgeKeys, $user, $accessToken);

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get old avatar
            $deleteFile = $user->getAvatar();
            //get new avatar
            $avatar = $request->files->get('edit_profile')['avatar'];
            //get upload dir
            $uploads_directory = $this->getParameter('uploads_directory');
            //hash unique new avatar
            $filename = md5(uniqid('', true)) . '.' . $avatar->guessExtension();
            //put new avatar in upload dir
            $avatar->move(
                $uploads_directory,
                $filename
            );
            //put filename in database
            $user->setAvatar($filename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //delete old avatar from upload dir
            unlink($uploads_directory. '/' .$deleteFile);
        }

        //var_dump($userBadges[0]['result']);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'badgeKeys' => $badgeKeys,
            'userBadges' => $userBadges,
            'user' => $user,
            'profileForm' => $form->createView(),
        ]);
    }
}


