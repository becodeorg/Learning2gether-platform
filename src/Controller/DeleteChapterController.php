<?php

namespace App\Controller;

use App\Entity\Chapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteChapterController extends AbstractController
{
    /**
     * @Route("partner/delete/chapter/{chapter}", name="delete_chapter", requirements={"chapter"="\d+"})
     * @param Request $request
     * @param Chapter $chapter
     * @return Response
     */
    public function index(Request $request, Chapter $chapter) : Response
    {
        $module = $chapter->getLearningModule();
        $em = $this->getDoctrine()->getManager();
        $em->remove($chapter);
        $em->flush();

        if (isset($_GET['return'])){
            switch ($_GET['return']){
                case 'flow':
                    return $this->redirectToRoute('edit_module', ['module' => $module->getId()]);
                case 'dash':
                    return $this->redirectToRoute('dashboard_module', ['module' => $module->getId()]);
                default:
                    return $this->redirectToRoute('partner');
            }
        }
    }
}
