<?php

namespace App\Service;

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
        // need to validate data is json format
        $jsonError =  $this->validateJson($process->getOutput());
        if ( count($jsonError) > 0){
            throw new LogicException('invalid json format');
        }
        return  json_decode($process->getOutput());
    }
}