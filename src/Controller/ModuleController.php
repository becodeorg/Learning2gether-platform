<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Badgr;
use App\Domain\MdParser;
use App\Entity\LearningModule;
use App\Entity\Language;
use Parsedown;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    /**
     * @Route("/portal/module/{module}", name="module", requirements={"module" = "\d+"})
     * @param Request $request
     * @param LearningModule $module
     * @return Response
     */
    public function module(Request $request, LearningModule $module): Response
    {
        //initialise badgr object
        $badgrObj = new Badgr;
        $badgrObj = $this->getSession($badgrObj);
        $tokens = $this->getTokens();

        //user = logged in user
        $user = $this->getUser();

        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy([
            'code' => $request->getLocale()
        ]);

        $moduleBadge = $module->getBadge();

        // when module completed, give badge
        // maybe put this in a private function instead of hardcoding a boolean -Jan
        $completed = true;
        if($completed === true){
            //add badge from this module to user
            $badgrObj->addBadgeToUser($module, $user, $tokens['accessToken']);
            $user->addBadge($module);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        // create the classes needed for parsing markdown to html, and finding and replacing yt links with an iplayer
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        $mdParser = new MdParser();

        return $this->render('module/index.html.twig', [
            'language' => $language,
            'module' => $module,
            'parsedown' => $parsedown,
            'mdparser' => $mdParser,
        ]);
    }

    // shouldn't the 2 functions below be in the badgr class ?? -Jan
    private function getSession(Badgr $badgrObj){
        //check if we already have refreshtoken
        if(isset($_SESSION['refreshToken'])){
            $refreshToken = $_SESSION['refreshToken'];
            $badgrObj->getTokenData($refreshToken);
        }
        //if we don't, do the initial authentication to get it
        else{
            $badgrObj->initialise();
        }
        return $badgrObj;
    }

    // probably not what its supposed to do, but my best guess -Jan
    private function getTokens(): array
    {
        $tokens = [];
        if (isset($_SESSION['accessToken'])){
            $tokens['accessToken'] = $_SESSION['accessToken'];
        }
        if (isset($_SESSION['refreshToken'])){
            $tokens['refreshToken'] = $_SESSION['refreshToken'];
        }
        return $tokens;
    }
}