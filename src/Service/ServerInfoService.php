<?php

namespace App\Service;

use Exception;
use LogicException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ServerInfoService {
    private  string $scriptFolder;
    private const GETTLSSCRIPT = 'gettls.sh';
    private const HELLOSCRIPT = 'hello.sh';

    public function __construct(private KernelInterface $kernel, private ValidatorInterface $validator){
        $this->scriptFolder = "{$this-> kernel->getProjectDir()}/scripts";
    }

    private function getScript(string $scriptName) {
        return "{$this->scriptFolder}/{$scriptName}";
    }

    private function validateJson(string $data) {
        $errors = $this->validator->validate($data, new Assert\Json );
        return $errors;
    }

    /**
     * get tls certificate info for a given domain 
     *
     * @param string $domain
     * @return void
     */
    public function getTlsCert(string $domain) {
        $process = new Process(['bash', $this->getScript($this::GETTLSSCRIPT), $domain]);
        $process->run();

        if (!$process->isSuccessful()){
            throw new LogicException('failed to start tls script');
        }
        // succeeded
        // remove \n and \r\n
        $cleanOutput = str_replace(["\n", "\r"],"",$process->getOutput());
         /* dd($process->getOutput()); */
        // need to validate data is json format
        $jsonError =  $this->validateJson($cleanOutput);
        if ( count($jsonError) > 0){
            throw new LogicException('invalid json format');
        }

        $tlsData = json_decode($process->getOutput()); 
        // convert timestamp to  Datetime
        if ( isset($tlsData->exp) 
          && !empty($tlsData->exp) 
          && strlen($tlsData->exp ) > 0
          && intval($tlsData->exp) == $tlsData->exp
        ) {
          try {
            $tlsData->exp = (new \DateTime())->setTimestamp($tlsData->exp);
          }
          catch(Exception $err) {
            throw new LogicException('invalid exp date format');
          }
        } else {
          $tlsData->exp = null;
        }
        return $tlsData;
    }
}
