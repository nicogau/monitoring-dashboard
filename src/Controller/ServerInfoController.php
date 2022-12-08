<?php

namespace App\Controller;

use App\Entity\ServerInfo;
use App\Form\ServerInfoType;
use App\Repository\ServerInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serverinfo')]
class ServerInfoController extends AbstractController
{
    #[Route('/', name: 'app_server_info_index', methods: ['GET'])]
    public function index(ServerInfoRepository $serverInfoRepository): Response
    {
        return $this->render('server_info/index.html.twig', [
            'server_infos' => $serverInfoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_server_info_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServerInfoRepository $serverInfoRepository): Response
    {
        $serverInfo = new ServerInfo();
        $form = $this->createForm(ServerInfoType::class, $serverInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serverInfoRepository->save($serverInfo, true);

            return $this->redirectToRoute('app_info', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('server_info/new.html.twig', [
            'server_info' => $serverInfo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_server_info_show', methods: ['GET'])]
    public function show(ServerInfo $serverInfo): Response
    {
        return $this->render('server_info/show.html.twig', [
            'server_info' => $serverInfo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_server_info_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServerInfo $serverInfo, ServerInfoRepository $serverInfoRepository): Response
    {
        $form = $this->createForm(ServerInfoType::class, $serverInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serverInfoRepository->save($serverInfo, true);

            return $this->redirectToRoute('app_info', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('server_info/edit.html.twig', [
            'server_info' => $serverInfo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_server_info_delete', methods: ['POST'])]
    public function delete(Request $request, ServerInfo $serverInfo, ServerInfoRepository $serverInfoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serverInfo->getId(), $request->request->get('_token'))) {
            $serverInfoRepository->remove($serverInfo, true);
        }

        return $this->redirectToRoute('app_info', [], Response::HTTP_SEE_OTHER);
    }
}
