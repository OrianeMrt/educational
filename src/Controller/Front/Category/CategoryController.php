<?php

namespace App\Controller\Front\Category;

use App\Repository\Category\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="category_index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->render('front/category/index.html.twig', [
            'category' => $categoryRepository->findAll()
        ]);
    }
}
