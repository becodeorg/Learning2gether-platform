<?php

namespace App\Controller;

use App\Domain\ImageManager;
use App\Entity\Image;
use App\Form\ImageUploaderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadContentController extends AbstractController
{
    /**
     * @Route("partner/upload/content", name="upload_content")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $uploader = $this->createForm(ImageUploaderType::class);
        $uploader->handleRequest($request);

        if ($uploader->isSubmitted() && $uploader->isValid()) {
            //get upload dir
            $imageManager = new ImageManager();
            $imageManager->fixUploadsFolder($this->getParameter('uploads_directory'), $this->getParameter('public_directory'));
            $newImage = $imageManager->createImage($uploader->getData()['upload'], $this->getUser(), $this->getParameter('uploads_directory'), 'content');

            $em = $this->getDoctrine()->getManager();
            $em->persist($newImage);
            $em->flush();

            //if ($request->isXmlHttpRequest()) {

            // }
        }

        $imagesAll = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('upload_content/index.html.twig', [
            'uploader' => $uploader->createView(),
            'imagesAll' => $imagesAll,
        ]);
    }
}
