<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SitePageController extends AbstractController
{
    /**
     * Homepage
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home/index.html.twig', [
        ]);
    }

}
