<?php

namespace App\Controller;

use App\Entity\Wod;
use App\Form\WodType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WodController extends AbstractController
{
    /**
     * @Route("/", name="wod")
     */
    public function index(Request $request)
    {
        $wod = new Wod();
        $form = $this->createForm(WodType::class, $wod);
        return $this->render('wod/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
