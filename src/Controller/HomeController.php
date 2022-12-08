<?php

namespace App\Controller;

use App\Repository\ServerInfoRepository;
use App\Repository\VhostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ServerInfoRepository $serverRepo, VhostRepository $vhostRepo): Response
    {
        $servers = $serverRepo->findAll();

        return $this->render('home/index.html.twig', [
            'servers' => $servers,
        ]);
    }
}
