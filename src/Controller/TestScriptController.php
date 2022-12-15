<?php

namespace App\Controller;

use App\Form\TestVhostType;
use App\Service\SshService;
use App\Service\TlsService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  test a given domain and get  infos on tls certificate 
 */
class TestScriptController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index( Request $request, TlsService $tlsService ): Response
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
                /* $tlsData =  json_decode(json_encode($serverInfo->getTlsCert($domain)), true); */
                $tlsData =  $tlsService->getTlsCert($domain);
                /* dd($tlsData); */

            } catch(Exception $e) {
                $this->addFlash('error', "impossible de récuperer les informations");
            }
        }

        return $this->renderForm('test_script/index.html.twig', [
            'testVhostForm' => $testVhostForm,
            'tlsData' => $tlsData
        ]);
    }

    #[Route('/testssh', name: 'app_test_ssh')]
    public function testssh( SshService $sshService ): Response
    {
         $testServerSSHConfigAlias = 'vps3';

         // os Info
        $osInfo = $sshService->osInfo($testServerSSHConfigAlias);

         // disk Info
        $diskInfo = $sshService->diskInfo($testServerSSHConfigAlias);

         // list db
        $sqlDbList = $sshService->listSqlDbs($testServerSSHConfigAlias);

        // list vhost
        $vhostList = $sshService->listVhostInfo($testServerSSHConfigAlias);

        // tools version
        $toolsList = $sshService->listToolsVersion($testServerSSHConfigAlias);

        return $this->renderForm('test_script/test_ssh.html.twig', [
          'osInfo' =>  $osInfo,
          'diskInfo' => $diskInfo,
          'sqlDbList' => $sqlDbList,
          'vhostList' => $vhostList,
          'toolsList' => $toolsList,
        ]);
    }
}
