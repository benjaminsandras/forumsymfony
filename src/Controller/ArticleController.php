<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("addcom", name="addcom")
     */
    public function addAction(Request $request){
    	if (isset($_GET["addcom"])) {
    		$article = ($_GET["addcom"]);

    $arti = $this->getDoctrine()
    	->getRepository('App:Article')
    	->findOneBy([
    		"id" => $article
    	]); 
  	
    // On crée un objet Comment
    $com = new Comment();

    $form = $this->get('form.factory')->createBuilder(FormType::class, $com)
      ->add('textec',     TextType::class)
     ->add('fk_article', EntityType::class, array(
        'class' => 'App:Article',
        'data' => $arti,))
      ->add('save',      SubmitType::class)
      ->getForm()
    ;

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
        return $this->redirectToRoute('/', array('id' => $com->getId()));
      }
    }

    // À ce stade, le formulaire n'est pas valide car :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
    return $this->render('article/index.html.twig', array(
      'form' => $form->createView(),
    ));
        	}
  }
}
