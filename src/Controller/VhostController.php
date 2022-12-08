<?php

namespace App\Controller;

use App\Entity\Vhost;
use App\Form\VhostType;
use App\Repository\VhostRepository;
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
