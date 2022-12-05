<?php

namespace App\Controller;

use App\Kernel;
use App\Service\ServerInfoService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

class TestScriptController extends AbstractController
{
    #[Route('/testscript', name: 'app_test_script')]
    public function index( ServerInfoService $serverInfo ): Response
    {
        $domain="www.pointvirgule.net";

        try {
            $tlsData =  $serverInfo->getSsl($domain);
            dd($tlsData);
        } catch(Exception $e) {
            dd($e);
        }

        return $this->render('test_script/index.html.twig', [
            'controller_name' => 'TestScriptController',
        ]);
    }
}
