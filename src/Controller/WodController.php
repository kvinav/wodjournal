<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\Wod;
use App\Form\WodType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class WodController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('index.html.twig');
    }
    /**
     * @Route("/communaute", name="community")
     */
    public function community()
    {
        $listWods = $this->getDoctrine()
            ->getRepository(Wod::class)
            ->findBy(array(), array('id' => 'desc'));

        return $this->render('wod/home.html.twig', array(
            'listWods' => $listWods,
        ));
    }
    /**
     * @Route("/journal/ajout-wod", name="wod")
     */
    public function addWod(Request $request)
    {
        $wod = new Wod();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(WodType::class, $wod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $id = $user->getId();
            $username = $user->getUsername();
            $wod = $form->getData();
            $wod->setUsername($username);
            $wod->setUserId($id);
            $em->persist($wod);
            $em->flush();
        }
        return $this->render('wod/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/journal/liste-wods", name="wods_list")
     */
    public function listWods(Request $request)
    {

        $user = $this->getUser();
        $id = $user->getId();
        $listWods = $this->getDoctrine()
            ->getRepository(Wod::class)
            ->findBy(array('userId' => $id), array('id' => 'desc'));
        
        return $this->render('wod/listwods.html.twig', [
            'listWods' => $listWods,
        ]);
    }
    
    /**
     * @Route("/journal/list/service", name="liste_service")
     */
    public function listServiceAction(Request $request)
    {

        if ($request->isXmlHttpRequest()){
            $choice = $request->get('choice');
            $userId = $request->get('userId');
             if ($choice === 'Wods effectués') {
                $listWods = $this->getDoctrine()
                    ->getRepository(Wod::class)
                    ->findBy(array('userId' => $userId), array('id' => 'desc'));
             }else if ($choice === 'A faire plus tard') {
                 $todos = $this->getDoctrine()
                    ->getRepository(Todo::class)
                    ->findBy(array('userId' => $userId));
                $listWods = array();
                    foreach ($todos as $todo) {

                    $listWods[] = $this->getDoctrine()
                        ->getRepository(Wod::class)
                        ->findBy(array('id' => $todo->getWodId()));
                     }
             }else{
                $listWods = array();
             }
               $response = new Response(json_encode(serialize($listWods)));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
           //return new JsonResponse(array('data' => json_encode(serialize($listWods))));

        }
        return new Response("Erreur : Ce n'est pas une requête Ajax", 400);
    }
    /**
     * @Route("/journal/ajout-todo", name="add_todolist")
     */
    public function addWodToList(Request $request)
    {

        $user = $this->getUser();
        $todo = new Todo();
        $em = $this->getDoctrine()->getManager();
        $todo->setUserId($user->getId());
        $todo->setWodId($request->query->get('wodId'));
        $em->persist($todo);
        $em->flush();
        return $this->redirectToRoute('community');
    }
    /**
     * @Route("/journal/ma-liste", name="todo_list")
     */
    public function toDoList(Request $request)
    {

        $user = $this->getUser();
        $todos = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findBy(array('userId' => $user->getId()));
        $listWods = array();
        foreach ($todos as $todo) {

            $listWods[] = ($this->getDoctrine()
                ->getRepository(Wod::class)
                ->findBy(array('id' => $todo->getWodId())))[0];
        }
        return $this->render('wod/listwods.html.twig', [
            'listWods' => $listWods,
        ]);
    }
}
