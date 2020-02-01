<?php

namespace App\Controller\Front;

use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WorkController extends AbstractController {

  private $workRepository;

  public function __construct(WorkRepository $workRepository) {
    $this->workRepository = $workRepository;
  }

  // Mes travaux

  /**
   * @Route("/mes-travaux/ajouter", name="work.add")
   */
  public function add(Request $request) {
    $work = new Work();
    $form = $this->createForm(WorkType::class, $work);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $work->setUser($this->getUser());
      $entityManager->persist($work);
      $entityManager->flush();
      return $this->redirectToRoute('work.show', [
        'id' => $work->getId()
      ]);
    }
    return $this->render('front/work/add.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/mes-travaux", name="work.user")
   */
  public function myWorks() {
    $works = $this->workRepository->findBy(['user' => $this->getUser()]);
    return $this->render('front/work/myWorkList.html.twig', [
      'works' => $works,
    ]);
  }


  /**
   * @Route("/mes-travaux/{id}", name="work.show")
   */
  public function showMyWork(Work $work) {
    if($work->getUser()->getId() != $this->getUser()->getId()){
      return $this->redirectToRoute('work.index');
    }
    return $this->render('front/work/show.html.twig', [
      'work' => $work,
      'user' => $this->getUser(),
      'likeNb' => count($work->getLikes()->toArray()),
    ]);
  }



  // Autres Travaux

  /**
   * @Route("/categorie/travaux/liste", name="work.index")
   */
  public function index() {
    return $this->render('front/work/index.html.twig', [
    ]);
  }

  /**
   * @Route("/travaux/{id}", name="work.show.other")
   */
  public function show(Work $work) {
    if($work->getUser()->getId() === $this->getUser()->getId()){
      $this->redirectToRoute('work.index');
    }
    return $this->render('front/work/show.html.twig', [
      'work' => $work,
      'user' => $this->getUser(),
      'isLiked' => $work->getLikes()->contains($this->getUser()),
      'likeNb' => count($work->getLikes()->toArray()),
    ]);
  }

  /**
   * @Route("/travaux/categorie/top3", name="work.category.best")
   */
  public function workList() {

    $works = $this->workRepository->findThree();

    return $this->render('front/work/workList.html.twig', [
      'works' => $works,
    ]);
  }


  /**
   * @Route("/like/{id}", name="work.like")
   */
  public function like(Work $work) {
    $entityManager = $this->getDoctrine()->getManager();
    //If unliked, become liked
    if($work->getLikes()->contains($this->getUser())){
      $work->removeLike($this->getUser());
    } else {
      $work->addLike($this->getUser());
      $entityManager->persist($work);
    }
    $entityManager->flush();
    return new JsonResponse(['isLiked' => $work->getLikes()->contains($this->getUser()), 'likeNb' => count($work->getLikes()->toArray())]);
  }

  /**
   * @Route("/mark/{id}", name="work.mark")
   */
  public function mark(Work $work, Request $request)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $mark = $request->request->get('mark');
    if(null != $mark && $mark >= 0 && $mark <=20){
      $work->setMark($mark);
      $entityManager->persist($work);
      $entityManager->flush();
    } else {
      $this->addFlash('warning', 'La note attribuée n\'est pas correcte.');
    }
    return $this->redirectToRoute('work.show.other', [
      'id' => $work->getId(),
    ]);
  }
}
