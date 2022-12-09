<?php

namespace App\Controller;

use App\Entity\Vhost;
use App\Form\VhostType;
use App\Repository\VhostRepository;
use App\Service\VhostInfoService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vhost')]
class VhostController extends AbstractController
{
    #[Route('/', name: 'app_vhost_index', methods: ['GET'])]
    public function index(VhostRepository $vhostRepository): Response
    {
        return $this->render('vhost/index.html.twig', [
            'vhosts' => $vhostRepository->findAll(),
        ]);
    }

    #[Route('/updatetlsdata', name: 'app_vhost_updatetlsdata', methods: ['POST'])]
    public function updateTlsData(
      Request $request, 
      VhostRepository $vhostRepository, 
      VhostInfoService $serverInfo,
      EntityManagerInterface $em
    ): Response
    {
        if ($this->isCsrfTokenValid('updatetls', $request->request->get('_token'))) {
            $vhostList = $vhostRepository->findAll();
            if ( count($vhostList) > 0) {
              // vhost list is not empty
              try {
                $vhost = $vhostList[0];
                // TODO: code the logic to get tls data for a given server
                // and update vhost in base
                $tlsInfo = (object) $serverInfo->getTlsCert($vhost->getHostname());
                // does a certificate exist?
                if ( isset($tlsInfo->cert) && $tlsInfo->cert) {
                  $vhost->setTlsRegistrarName($tlsInfo->issuer);
                  $vhost->setTlsExpDate($tlsInfo->exp);
                  $vhost->setTlsDayleft($tlsInfo->days_left);
                  $em->persist($vhost);
                  $em->flush();
                }

                  $this->addFlash('notice', "mise à jour des informations éffectué");
              } 
              catch(Exception $err) {
                  $this->addFlash('error', "impossible de récuperer les informations");
              }
            } 
        }

        return $this->redirectToRoute('app_vhost_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_vhost_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VhostRepository $vhostRepository): Response
    {
        $vhost = new Vhost();
        $form = $this->createForm(VhostType::class, $vhost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vhostRepository->save($vhost, true);

            return $this->redirectToRoute('app_vhost_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vhost/new.html.twig', [
            'vhost' => $vhost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vhost_show', methods: ['GET'])]
    public function show(Vhost $vhost): Response
    {
        return $this->render('vhost/show.html.twig', [
            'vhost' => $vhost,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vhost_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vhost $vhost, VhostRepository $vhostRepository): Response
    {
        $form = $this->createForm(VhostType::class, $vhost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vhostRepository->save($vhost, true);

            return $this->redirectToRoute('app_vhost_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vhost/edit.html.twig', [
            'vhost' => $vhost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vhost_delete', methods: ['POST'])]
    public function delete(Request $request, Vhost $vhost, VhostRepository $vhostRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vhost->getId(), $request->request->get('_token'))) {
            $vhostRepository->remove($vhost, true);
        }

        return $this->redirectToRoute('app_vhost_index', [], Response::HTTP_SEE_OTHER);
    }


}
