<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/todo', name: 'app_')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos = array(
                'achat'=>'acheter clé',
                'cours'=>'finaliser cours',
                'correction'=>'corriger mmes examens'
            );
            $session->set('todos',$todos);
        }
        return $this->render('/index.html.twig');
    }

    #[Route('/todo/addToDo/{key}/{task}', name: 'app_.add')]
    public function addToDo(Request $request, $key, $task) {
        $session = $request->getSession();
        if($session->has('todos')){
        $todos = $session->get('todos');
        if(isset($todos[$key])){
            $this->addFlash('danger',"le todo $key existe déjà");
        }
        else{
            $todos[$key] = $task;
            $this->addFlash('success',"le todo $key est ajouté");
            $session->set('todos',$todos);
        }
        }
        else{
            $this->addFlash('danger',"le liste n'est pas encore initialisée");
        }
        return $this->redirectToRoute('app_');
    }

    #[Route('/todo/deleteToDo/{key}', name: 'app_.delete')]
    public function deleteToDo(Request $request, $key) {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(isset($todos[$key])){
                unset($todos[$key]);
                $this->addFlash('success',"le todo $key est supprimé");
                $session->set('todos',$todos);
            }
            else{

                $this->addFlash('danger',"le todo $key n'existe pas déjà");
            }
        }
        else{
            $this->addFlash('danger',"le liste n'est pas encore initialisée");
        }
        return $this->redirectToRoute('app_');
    }

    #[Route('/todo/resetToDo', name: 'app_.reset')]
    public function resetToDo(Request $request) {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            $todos = array();
            $session->set('todos',$todos);
        }
        else{
            $this->addFlash('info',"le liste n'est pas encore initialisée");
        }
        return $this->redirectToRoute('app_');
    }
}
