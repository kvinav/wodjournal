<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WodController extends AbstractController
{
    /**
     * @Route("/", name="wod")
     */
    public function index()
    {
        return $this->render('wod/index.html.twig', [
            'controller_name' => 'WodController',
        ]);
    }
}
