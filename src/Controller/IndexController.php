<?php
namespace App\Controller;
use App\Entity\Employe;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class IndexController extends AbstractController 

{
  /**
     *@Route("/",name="employe_list")
     */
//@Route("/") pour dire je suis sur la racine exécute moi la fonction  home
//pour que le @Route {"annotation" pour configurer les routes} soit compréhensif coté serveur on doit l'indiquer dans le controlleur par use symfony\Component.... 

public function home(EntityManagerInterface $entityManager):Response
{
   // return new Response('<h1> Mon PFE  avec mention excellent </h1>');
  // return $this->render('articles\index.html.twig'); 
/* 
  $employes = ['employe 1', 'employe 2','employe3'];
  return  $this->render('articles/index.html.twig',['employes' => $employes]); 
   */

   
    //récupérer tous les articles de la table article de la BD
    // et les mettre dans le tableau $articles
    $employes = $entityManager->getRepository(Employe::class)->findAll(); //remplir le tableau Employe par le resultat de tableau findAll
  
    return  $this->render('articles/index.html.twig',['employes' => $employes]);  
  

}
/**
     * @Route("/article/new", name="new_employe")
     * Method({"GET", "POST"})
     */
    public function new(Request $request , EntityManagerInterface $entityManager ) :Response
   {
      $employes = new Employe(); //creation d'un employe
      $form = $this->createFormBuilder($employes)// creation d'un formulaire et je fait la liason par l'objet employes
        ->add('nom', TextType::class)
        ->add('save', SubmitType::class, array(
          'label' => 'Créer')
        )->getForm();//le resultat sera ajouter dans la form
        

      $form->handleRequest($request); //pour remplir l'objet à partir de formulaire par la methode  "handleRequest"

      if($form->isSubmitted() && $form->isValid()) {
        $employes= $form->getData();

        $entityManager->persist($employes);
        $entityManager->flush();

        return $this->redirectToRoute('employe_list'); 
      }
      return $this->render('articles/new.html.twig',['form' => $form->createView()]);
  }

        /**
     * @Route("/article/{id}", name="employe_show")
     */
    public function show(EntityManagerInterface $entityManager , $id): Response

    {
      
      $employes = $entityManager->getRepository(Employe::class)->find($id);
      return $this->render('articles/show.html.twig', array('employes' => $employes));
    //   if (!$employes) {
    //     throw $this->createNotFoundException(
    //         'je ne trouve pas les details de cet employé '.$id
    //     );
    // }

      
    }

/**
     * @Route("/article/edit/{id}", name="edit_employe")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request ,EntityManagerInterface $entityManager , $id): Response
     {
      $employes = new Employe();
      $employes = $entityManager->getRepository(Employe::class)->find($id);

      $form = $this->createFormBuilder($employes)
        ->add('nom', TextType::class)
        ->add('save', SubmitType::class, array(
          'label' => 'Modifier'         
        ))->getForm();

      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {

        
        $entityManager->flush();

        return $this->redirectToRoute('employe_list');
      }

      return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }

 /**
   * @Route("/article/delete/{id}",name="delete_employe")
   * @Method({"DELETE"})
   */
  public function delete(Request $request ,EntityManagerInterface $entityManager , $id): Response
  {
      
      $employes = $entityManager->getRepository(Employe::class)->find($id);
      
      $entityManager->remove($employes);
      $entityManager->flush();

      $response = new Response();
      $response->send();

      return $this->redirectToRoute('employe_list');
   

    }





//    /**
//       * @Route("/employe/save")
//       */
// public function save(EntityManagerInterface $entityManager) :Response
// {

  
  
//   $employes = new Employe();
//   $employes->setNom('employe : Amin Mohamed');

//   $entityManager->persist($employes); //asssue l’enregistrement dans le cash
//   $entityManager->flush(); //enregistrer effectivement dans la base des donnéés
//   return new Response('Employe enregisté avec id   '.$employes->getId());



// }

  

}