<?php

namespace App\Controller;

use App\Form\AddVhostType;
use App\Form\VhostListType;
use App\Repository\VhostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VhostController extends AbstractController
{
    #[Route('/vhost', name: 'app_vhost')]
    public function index(Request $request, EntityManagerInterface $em, VhostRepository $VhostRepo): Response
    {
        //add form
        $addVhostForm = $this->createFormBuilder()
            ->add('addVhost', AddVhostType::class)
            ->add('addbtn',SubmitType::class, [
                'label' => 'valider'
            ])
            ->getForm();

        // remove form
        $removeVhostForm = $this->createFormBuilder()
            ->add('removeVhost', VhostListType::class)
            ->add('removebtn',SubmitType::class, [
                'label' => 'valider'
            ])
            ->getForm();

        // handle add form 
        $addVhostForm->handleRequest($request);

        // manage adding a vhost
        if( $addVhostForm->isSubmitted() && $addVhostForm->isValid()){
            $vhost = $addVhostForm->getData()['addVhost'];
            // dd($vhost);
            $vhostInDb = $VhostRepo->findByHostname($vhost->getHostName());
            // if no vhost with this ip in database, add it
            if(empty($vhostInDb)) {
                $em->persist($vhost);
                $em->flush();

                $this->addFlash('notice', "le vhost ({$vhost->getHostname()}) a bien été ajouté à la liste ");
            } else {
                $this->addFlash('error', "le serveur ({$vhost->getHostname()}) est déjà en base de données ");
            } 
        }

        // handle remove form 
        // dd($request);
        $removeVhostForm->handleRequest($request);

        if( $removeVhostForm->isSubmitted() && $removeVhostForm->isValid()){
            $vhost = $removeVhostForm->getData()['removeVhost']['vhost'];
            try {
                $em->remove($vhost);
                $em->flush();
                $this->addFlash('notice', "le vhost ({$vhost->getHostname()}) a bien été supprimé de la liste ");
            }
            catch(Exception $e){
                $this->addFlash('error', "aucun autre vhost à supprimer");
            }
        } 

        if (!$removeVhostForm->getClickedButton()) {
            $removeVhostForm->clearErrors();
        }
        if (!$addVhostForm->getClickedButton()) {
            $addVhostForm->clearErrors();
        }
        return $this->renderForm('vhost/index.html.twig', [
            'addVhostForm' => $addVhostForm,
            'removeVhostForm' => $removeVhostForm,
        ]);
    }
}
