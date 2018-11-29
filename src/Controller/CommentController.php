<?php

namespace App\Controller;


use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment")
     */
    public function index(Request $request)
    {
    	$Comment = $this->getDoctrine()
    	->getRepository('App:Comment')
    	->findall();
    	$article = $this->getDoctrine()
    	->getRepository('App:Article')
    	->findall();
        $com = new Comment();

    	$form = $this->get('form.factory')->createBuilder(FormType::class, $com)
      		->add('textec',     TextType::class)
      		->add('fkarticle',  IntegerType::class)
      		->add('save',      SubmitType::class)
      		->getForm();

    // Si la requête est en POST
    	if ($request->isMethod('POST')) {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $com contient les valeurs entrées dans le formulaire par le visiteur
      	$form->handleRequest($request);
      // On vérifie que les valeurs entrées sont correctes
      		if ($form->isValid()) {
        // On enregistre notre objet $com dans la base de données, par exemple
        		$comments = $this->getDoctrine()->getManager();
        		$comments->persist($com);
        		$comments->flush();
		
				$request->getSession()->getFlashBag()->add('notice', 'Commentaire enregistré.');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        		return $this->redirectToRoute('article', array('comments' => $comments));
      		}
    	}

		return $this->render('comment/index.html.twig', [
            'Comment' => $Comment,
            'Article' => $article,
            'form' => $form->createView()
        ]);
    }


}
