<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\LikeWod;
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

        foreach ($listWods as $wod) {
            $liked = $this->getDoctrine()
            ->getRepository(LikeWod::class)
            ->findBy(array('userId' => $wod->getUserId(), 'wodId' => $wod->getId()));
            if (empty($liked)){
                $wod->liked = 0;
            }else{
                $wod->liked = 1;
            }
        }
        
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

            return $this->redirectToRoute('wods_list');
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
        $todos = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findBy(array('userId' => $user->getId()));
        $listWodsTodo = array();
        foreach ($todos as $todo) {

            $listWodsTodo[] = ($this->getDoctrine()
                ->getRepository(Wod::class)
                ->findBy(array('id' => $todo->getWodId())))[0];
        }
        return $this->render('wod/listwods.html.twig', [
            'listWods' => $listWods,
            'listWodsTodo' => $listWodsTodo,
        ]);
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
    /**
     * @Route("/journal/like", name="like_wod")
     */
    public function likeWod(Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $wodId = $request->get('wodId');
        
            $like = new LikeWod();
            $like->setWodId($wodId);
            $like->setUserId($user->getId());
            $em = $this->getDoctrine()->getManager();
            $em->persist($like);
            $em->flush();
            
           return new JsonResponse(array('data' => json_encode(serialize($like))));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax", 400);
    }
    /**
     * @Route("/journal/unlike", name="unlike_wod")
     */
    public function unlikeWod(Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $wodId = $request->get('wodId');
        
            $likeArray = $this->getDoctrine()
                ->getRepository(LikeWod::class)
                ->findBy(array('wodId' => $wodId, 'userId' => $user->getId()));
            $like = $this->getDoctrine()
                ->getRepository(LikeWod::class)
                ->find($likeArray[0]->getId());
            $em = $this->getDoctrine()->getManager();
            $em->remove($like);
            $em->flush();
            
           return new JsonResponse(array('data' => json_encode('done')));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax", 400);
    }
}
