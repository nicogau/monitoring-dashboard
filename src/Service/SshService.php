<?php

namespace App\Service;

use Exception;

class SshService {
  private const VHOSTACTIVE = 'a2query -s';
  private const SQLDBCMD = 'which mysql && echo "SHOW DATABASES" | sudo mysql'; 
  private const OSINFOCMD = 'hostnamectl';
  public function __construct(){}

  private function ssh(string $cmd, string $host)
  {
    $res = [];
    $retStatus = null;

    try {
      exec("ssh {$host} {$cmd}", $res, $resCode);
      return ['data' => $res, 'retStatus' => $retStatus];
    }
    catch(Exception $err) {
        throw $err;
    }
  }

  public function osInfo(string $host)
  {
    $res = $this->ssh(self::OSINFOCMD, $host);
    return $res;
  }

  public function listVhostInfo(string $host)
  {

  }

  public function listMaterialArch(string $host)
  {

  }

  public function listToolsVersion(string $host)
  {
  }

  public function listSqlDbs (string $host) 
  {
    $res = $this->ssh(self::SQLDBCMD, $host);
    return $res;
  }

}

