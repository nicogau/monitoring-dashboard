<?php

namespace App\Controller;

use App\Form\AddServerInfoType;
use App\Form\ServerInfoListType;
use App\Repository\ServerInfoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    /**
     * show forms to add or delete a server 
     *
     * @return Response
     */
    #[Route('/server', name: 'app_server')]
    public function index(Request $request, ServerInfoRepository $serverInfoRepo, EntityManagerInterface $em): Response
    {
        //add form
        $addServerForm = $this->createFormBuilder()
            ->add('addServer', AddServerInfoType::class)
            ->add('addbtn',SubmitType::class, [
                'label' => 'valider'
            ])
            ->getForm();
        // remove form
        $removeServerForm = $this->createFormBuilder()
            ->add('removeServer', ServerInfoListType::class)
            ->add('removebtn',SubmitType::class, [
                'label' => 'valider'
            ])
            ->getForm();

        // handle add form 
        $addServerForm->handleRequest($request);

        // manage adding a server
        if( $addServerForm->isSubmitted() && $addServerForm->isValid()){
            $server = $addServerForm->getData()['addServer'];
            // dd($server);
            $serverInDb = $serverInfoRepo->findByIp($server->getIp());
            // if no server with this ip in database, add it
            if(empty($serverInDb)) {
                $em->persist($server);
                $em->flush();

                $this->addFlash('notice', "le serveur ({$server->getIp()}) a bien été ajouté à la liste ");
            } else {
                $this->addFlash('error', "le serveur ({$server->getIp()}) est déjà en base de données ");
            } 
        }

        // handle remove form 
        $removeServerForm->handleRequest($request);
        if( $removeServerForm->isSubmitted() && $removeServerForm->isValid()){
            // dd($removeServerForm->getData()['removeServer']['server']);
            $server = $removeServerForm->getData()['removeServer']['server'];
            try {
                $em->remove($server);
                $em->flush();
                $this->addFlash('notice', "le serveur ({$server->getIp()}) a bien été supprimé de la liste ");
            }
            catch(Exception $e){
                $this->addFlash('error', "aucun autre serveur à supprimer");
            }
        } 

        if (!$removeServerForm->getClickedButton()) {
            $removeServerForm->clearErrors();
        }
        if (!$addServerForm->getClickedButton()) {
            $addServerForm->clearErrors();
        }
        return $this->renderForm('server/index.html.twig', [
            'addServerForm' => $addServerForm,
            'removeServerForm' => $removeServerForm,
        ]);
    }
}
