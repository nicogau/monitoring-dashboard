<?php

namespace App\Service;

use Exception;

class SshService {
  /*  check all vhost
    https://serverfault.com/questions/742352/how-can-i-list-the-current-apache-2-virtual-hosts-from-the-command-line
    a2query -s
    apache2ctl -S
  */
  private const VHOSTACTIVECMD = '"/usr/sbin/a2query -s"';
  private const SQLDBCMD = '"which mysql &> /dev/null && echo \"SHOW DATABASES\" | sudo mysql"';
  private const OSINFOCMD = '"hostnamectl"';
  /* tools version */
  private const TOOLVERSION = [
    'php' => '"php --version | head -n 1 | cut -d\" \" -f 2"',
    'mysql' => '"mysql --version | head -n 1 "',
    // 'apache' => ''
  ];
  private const DISKINFO = '"df -h | grep  \" /$\""';

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

  public function diskInfo(string $host)
  {
    $res = $this->ssh(self::DISKINFO, $host);
    return $res;
  }

  public function listVhostInfo(string $host)
  {
    $res = $this->ssh(self::VHOSTACTIVECMD, $host);
    return $res;
  }

  public function listMaterialArch(string $host)
  {

  }

  public function listToolsVersion(string $host)
  {
    $res = [];
    foreach(self::TOOLVERSION as $tool => $cmd) {
      $res[$tool] = $this->ssh($cmd, $host);
    }
    return $res;
  }

  public function listSqlDbs (string $host) 
  {
    $res = $this->ssh(self::SQLDBCMD, $host);
    return $res;
  }

}

