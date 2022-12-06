<?php

namespace App\Controller;

use App\Form\VhostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VhostController extends AbstractController
{
    #[Route('/vhost', name: 'app_vhost')]
    public function index(Request $request): Response
    {
        //add form
        $addVhostForm = $this->createFormBuilder()
            ->add('addVhost', VhostType::class)
            ->getForm();
        // remove form
        $removeVhostForm = $this->createFormBuilder()
            ->add('removeVhost', VhostType::class)
            ->getForm();

        // handle form 
        $addVhostForm->handleRequest($request);
        $removeVhostForm->handleRequest($request);
        
        // manage adding a vhost
        if( $addVhostForm->isSubmitted() && $addVhostForm->isValid()){
            dd(' add form valid');
        }
        // manage removing a vhost
        if( $removeVhostForm->isSubmitted() && $removeVhostForm->isValid()){
            dd('remove form valid');
        }


        return $this->renderForm('vhost/index.html.twig', [
            'addVhostForm' => $addVhostForm,
            'removeVhostForm' => $removeVhostForm,
        ]);
    }
}
