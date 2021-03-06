<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\LikeWod;
use App\Entity\Wod;
use App\Entity\Comment;
use App\Form\WodType;
use App\Form\CommentType;
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
        return $this->render('wod/index.html.twig');
    }
    /**
     * @Route("/chronometre", name="stopwatch")
     */
    public function stopwatch()
    {
        return $this->render('wod/stopwatch.html.twig');
    }
    /**
     * @Route("/mentions-legales", name="legalMentions")
     */
    public function mentions()
    {
        return $this->render('wod/mentions.html.twig');
    }
    /**
     * @Route("/communaute", name="community")
     */
    public function community()
    {
        $listWods = $this->getDoctrine()
            ->getRepository(Wod::class)
            ->findBy(array(), array('id' => 'desc'));
        $user = $this->getUser();
        if ($user) {
            $id = $user->getId();

            foreach ($listWods as $wod) {
                $liked = $this->getDoctrine()
                ->getRepository(LikeWod::class)
                ->findBy(array('userId' => $id, 'wodId' => $wod->getId()));
                if (empty($liked)){
                    $wod->liked = 0;
                }else{
                    $wod->liked = 1;
                }
                $todos = $this->getDoctrine()
                ->getRepository(Todo::class)
                ->findBy(array('userId' => $id, 'wodId' => $wod->getId()));
                if (empty($todos)){
                    $wod->todos = 0;
                }else{
                    $wod->todos = 1;
                }
                $likes = $this->getDoctrine()
                ->getRepository(LikeWod::class)
                ->findBy(array('wodId' => $wod->getId()));
                $wod->nbLikes = count($likes);
                $comments = $this->getDoctrine()
                ->getRepository(Comment::class)
                ->findBy(array('wodId' => $wod->getId()));
                $wod->nbComments = count($comments);
            }
        }else {
            foreach ($listWods as $wod) {
                $wod->liked = 0;
                $wod->todos = 0;
                $likes = $this->getDoctrine()
                ->getRepository(LikeWod::class)
                ->findBy(array('wodId' => $wod->getId()));
                $wod->nbLikes = count($likes);
                $comments = $this->getDoctrine()
                ->getRepository(Comment::class)
                ->findBy(array('wodId' => $wod->getId()));
                $wod->nbComments = count($comments);
            }

        }
        
        
        return $this->render('wod/community.html.twig', array(
            'listWods' => $listWods,
        ));
    }
    /**
     * @Route("/entrainement", name="display_wod")
     */
    public function displayWod(Request $request)
    {
        $comment = new Comment();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $wod = $this->getDoctrine()
            ->getRepository(Wod::class)
            ->find($request->query->get('id'));
        $user = $this->getUser();
        $id = $user->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $dateFormatted = $date->format('d/m/Y');
            $comment->setDate($dateFormatted);
            $em->persist($comment);
            $em->flush();
        }
         $liked = $this->getDoctrine()
            ->getRepository(LikeWod::class)
            ->findBy(array('userId' => $id, 'wodId' => $wod->getId()));
            if (empty($liked)){
                $wod->liked = 0;
            }else{
                $wod->liked = 1;
            }
            $todos = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findBy(array('userId' => $id, 'wodId' => $wod->getId()));
            if (empty($todos)){
                $wod->todos = 0;
            }else{
                $wod->todos = 1;
            }
            $likes = $this->getDoctrine()
            ->getRepository(LikeWod::class)
            ->findBy(array('wodId' => $wod->getId()));
            $wod->nbLikes = count($likes);
            $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(array('wodId' => $wod->getId()));
            $wod->nbComments = count($comments);
        return $this->render('wod/uniquewod.html.twig', array(
            'wod' => $wod,
            'comments' => $comments,
            'form' => $form->createView(),
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
        if ($request->query->get('chrono') === 'oui' && $request->request->get('timeStopwatch')) {
            $time = '00:' . explode(':', $request->request->get('timeStopwatch'))[0] . ':' . explode(':', $request->request->get('timeStopwatch'))[1];
            $formattedTime = $this->seconds_from_time($time);
        }else if ($request->query->get('action') === 'modify') {
            $wod = $this->getDoctrine()
                    ->getRepository(Wod::class)
                    ->find($request->query->get('id'));
            $formattedTime = $this->seconds_from_time($wod->getTime());

        }else{
            $formattedTime = '';

        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('wodId') && $request->request->get('action') === 'modify') {
                $wodToModify = $this->getDoctrine()
                    ->getRepository(Wod::class)
                    ->find($request->request->get('wodId'));
                $wodToModify->setName($form->getData()->getName());
                $wodToModify->setWork($form->getData()->getWork());
                $wodToModify->setWeight($form->getData()->getWeight());
                $wodToModify->setDate($form->getData()->getDate());
                $timeFormatted = gmdate("H:i:s", intval($form->getData()->getTime()));
                $wodToModify->setTime($timeFormatted);
                $wodToModify->setComment($form->getData()->getComment());
                $em->persist($wodToModify);
                $em->flush();

                return $this->redirectToRoute('wods_list');
            }else if ($request->request->get('wodId') && !$request->request->get('action')) {
                $todo = $this->getDoctrine()
                    ->getRepository(Todo::class)
                    ->findBy(array('wodId' => intval($request->request->get('wodId'))));
                $em->remove($todo[0]);
                $em->flush();
            }
            $user = $this->getUser();
            $id = $user->getId();
            $username = $user->getUsername();
            $wod = $form->getData();
            $timeFormatted = gmdate("H:i:s", intval($wod->getTime()));
            $wod->setTime($timeFormatted);
            $wod->setUsername($username);
            $wod->setUserId($id);
            $em->persist($wod);
            $em->flush();

            return $this->redirectToRoute('wods_list');
        }
        return $this->render('wod/addwod.html.twig', [
            'form' => $form->createView(),
            'formattedTime' => $formattedTime,
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
         if ($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $wodId = $request->get('wodId');
            $todo = new Todo();
            $em = $this->getDoctrine()->getManager();
            $todo->setUserId($user->getId());
            $todo->setWodId($wodId);
            $em->persist($todo);
            $em->flush();
            
           return new JsonResponse(array('data' => 'done'));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax", 400);
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
            $likes = $this->getDoctrine()
                ->getRepository(LikeWod::class)
                ->findBy(array('wodId' => $wodId));
            $nbLikes = count($likes);
            
           return new JsonResponse(array('data' => $nbLikes));
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
             $likes = $this->getDoctrine()
                ->getRepository(LikeWod::class)
                ->findBy(array('wodId' => $wodId));
            $nbLikes = count($likes);
            
           return new JsonResponse(array('data' => $nbLikes));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax", 400);
    }
    public function seconds_from_time($time) {
        list($h, $m, $s) = explode(':', $time);
        return ($h * 3600) + ($m * 60) + $s;
    }
}
