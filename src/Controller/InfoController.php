<?php

namespace App\Controller;

use App\Repository\ServerInfoRepository;
use App\Repository\VhostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    #[Route('/', name: 'app_info')]
    public function index(ServerInfoRepository $serverRepo, VhostRepository $vhostRepo): Response
    {
        $servers = $serverRepo->findAll();
        $orphansVhost = $vhostRepo->findByServer(null) ;
        /* dd($orphansVhost); */

        return $this->render('info/index.html.twig', [
            'servers' => $servers,
            'orphansVhost' => $orphansVhost,
        ]);
    }
}
