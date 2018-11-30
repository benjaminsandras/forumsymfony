<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/", name="/")
     */
    public function index()
    {
    	$Comment = $this->getDoctrine()
    	->getRepository('App:Comment')
    	->findall();
    	$article = $this->getDoctrine()
    	->getRepository('App:Article')
    	->findall();
        return $this->render('comment/index.html.twig', [
            'Comment' => $Comment,
            'Article' => $article
        ]);
    }


}
