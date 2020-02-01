<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminIndexController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function index()
    {
        return $this->render('Back/index.html.twig');
    }
}
