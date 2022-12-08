<?php

namespace App\Controller;

use App\Form\TestVhostType;
use App\Kernel;
use App\Service\ServerInfoService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  test a given domain and get  infos on tls certificate 
 */
class TestScriptController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index( Request $request, ServerInfoService $serverInfo ): Response
    {
        $tlsData = null;

        //add form
        $testVhostForm = $this->createFormBuilder()
            ->add('testVhost', TestVhostType::class)
            ->add('addbtn',SubmitType::class, [
                'label' => 'valider'
            ])
            ->getForm();

        $testVhostForm->handleRequest($request);

        if ($testVhostForm->isSubmitted() && $testVhostForm->isValid()) {
            try {
                $domain = $testVhostForm->getData()['testVhost']['hostname'];
                $tlsData =  json_decode(json_encode($serverInfo->getTlsCert($domain)), true);
                // dd($tlsData);
                // dd($serverInfo->getTlsCert($domain));
            } catch(Exception $e) {
                $this->addFlash('error', "impossible de récuperer les informations");
            }
        }

        return $this->renderForm('test_script/index.html.twig', [
            'testVhostForm' => $testVhostForm,
            'tlsData' => $tlsData
        ]);
    }
}
