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
     * @Route("/{_locale}/module", name="module")
     */
    public function module(Request $request): Response
    {
        // check the $_GET['module'], has to be set, and an integer, if not, redirects back to portal
        if (isset($_GET['module']) && ctype_digit((string)$_GET['module'])) {
            //get this Module
            $moduleID = $_GET['module'];
        } else {
            return $this->redirectToRoute('partner');
        }

        //initialise badgr object
        $badgrObj = new Badgr;

        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy([
            'code' => $request->getLocale()
        ]);

        //user = logged in user
        $user = $this->getUser();

        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($moduleID);

        //$moduleBadge = $module->getBadge();

        //when module completed, give badge
        $completed = false;
        if($completed === true){
            //add badge from this module to user
            $badgrObj->addBadgeToUser($module, $user);
            $user->addBadge($module);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        // create the classes needed for parsing markdown to html, and finding and replacing yt links with an iplayer
        $parsedown = new Parsedown();

        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'language' => $language,
            'module' => $module,
            'parsedown' => $parsedown,
        ]);
    }
}