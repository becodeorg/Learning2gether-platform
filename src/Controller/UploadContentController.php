<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageUploaderType;
use App\Form\UploadContentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\I;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

        if ($uploader->isSubmitted() && $uploader->isValid()){
            //get upload dir
            $uploads_directory = $this->getParameter('uploads_directory');

            $uploadedImage = $uploader->getData()['upload'];

            $filename = md5(uniqid('', true)) . '.' . $uploadedImage->guessExtension();

            $newImage = new Image($uploadedImage->getClientOriginalName(), $filename, $this->getUser(), 'content');

            $em = $this->getDoctrine()->getManager();
            $em->persist($newImage);
            $em->flush();

            $uploadedImage->move(
                $uploads_directory,
                $filename
            );
        }

        $imagesAll = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('upload_content/index.html.twig', [
            'uploader' => $uploader->createView(),
            'imagesAll' => $imagesAll,
        ]);
    }
}
