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
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(WodType::class, $wod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $wod = $form->getData();
            $em->persist($wod);
            $em->flush();
        }
        return $this->render('wod/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/liste-wods", name="wods_list")
     */
    public function listWods(Request $request)
    {

        $listWods = $this->getDoctrine()
            ->getRepository(Wod::class)
            ->findAll();

        return $this->render('wod/listwods.html.twig', [
            'listWods' => $listWods,
        ]);
    }
}
