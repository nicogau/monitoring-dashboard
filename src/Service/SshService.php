<?php

namespace App\Service;

use Exception;
use LogicException;
use Symfony\Component\Process\Process;

class SshService {
  private $osInfoCmd = 'hostnamectl';
  private $vhostActive = 'a2query -s';
  private $sshKeyPass = 'blabla';
  private $serverSSHConfigAlias = 'vps3';

  public function __construct() 
  { 
  }

  public function sshCmd(Array $commands )
  {
    /*
      * adding ssh-agent and ssh-add with pass phrase
      * https://stackoverflow.com/questions/27022516/controlling-an-interactive-process-with-php-using-symfony-process
     */ 

    $process = new Process(array_merge([ 'ssh' ], $commands ));
    $process->run();

    if (!$process->isSuccessful()){
            throw new LogicException('failed to use ssh');
    }
  }

  public function testPty()
  {
    /*
      * adding ssh-agent and ssh-add with pass phrase
      * https://stackoverflow.com/questions/27022516/controlling-an-interactive-process-with-php-using-symfony-process
     */ 

    $process = new Process(
      ['ssh', 
      $this->serverSSHConfigAlias,
      null,
      $this->sshKeyPass . "\n",   // Append a line feed to simulate pressing ENTER
      ]);
    try {

      $process->setPty(true);
      $process->mustRun(function($type, $buffer){
        echo($buffer);
      });

      if (!$process->isSuccessful()){
              dd($process->errors);
              /* throw new LogicException('failed to use ssh'); */
      }
      $output = $process->getOutput();

      dd($output);
    } 
    catch(Exception $e){
      dd($e);
    }

    return $output;

  }

}

