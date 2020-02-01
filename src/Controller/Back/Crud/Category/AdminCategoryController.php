<?php

namespace App\Controller\Back\Crud\Category;

use App\Entity\Category\Category;
use App\Form\Back\Category\AddType;
use App\Repository\Category\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin_category")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->render('back/crud/Category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * Ajouter une categorie
     * @Route("/admin/categories/add", name="admin_category_add")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function add(Request $request, ObjectManager $manager)
    {
        $cat = new Category();

        $form = $this->createForm(AddType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            $cat->setCreatedBy($this->getUser());
            $manager->persist($cat);
            $manager->flush();

            $this->addFlash(
                'success',
                "<i class=\"fas fa-check-circle\"></i> Création d'une categorie réalisé avec succès"
            );

            return $this->redirectToRoute('admin_category');

        endif;

        return $this->render('Back/Crud/Category/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajouter une catégorie'
        ]);

    }

    /**
     * edit la catégorie
     * @Route("/admin/categories/edit/{id}", name="admin_category_edit_id")
     * @param Category $category
     * @param Request $request
     * @param ObjectManager $manager
     * @return RedirectResponse|Response
     */
    public function edit(Category $category ,Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AddType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()):

            $category->setCreatedBy($this->getUser());
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                "<i class=\"fas fa-check-circle\"></i> La catégorie a été modifié"
            );

            return $this->redirectToRoute('admin_category');

        endif;

        return $this->render('Back/Crud/Category/add.html.twig', [
            'form' => $form->createView(),
            'title' => "Editer une catégorie"
        ]);

    }


    /**
     * afficher de categorie
     * @Route("/admin/categories/{id}", name="admin_category_id")
     * @param Category $category
     * @return Response
     */
    public function id(Category $category)
    {
        return $this->render('Back/Crud/Category/id.html.twig', [
            'c' => $category
        ]);
    }

    /**
     * delete la categorie
     * @Route("/admin/categories/delete/{id}", name="admin_category_delete_id")
     * @param Category $category
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Category $category, ObjectManager $manager)
    {
        $manager->remove($category);
        $manager->flush();
        return $this->render('Back/_partials/alert/deleteCategories.html.twig');
    }
}
