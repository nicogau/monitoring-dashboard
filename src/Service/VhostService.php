<?php

namespace App\Service;

use App\Entity\Vhost;
use App\Repository\VhostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class VhostService {
  public function __construct( private VhostRepository $vhostRepo, private TlsService $tlsService, private EntityManagerInterface $em) {} 

    private function updateTls(Vhost $vhost): ?Vhost {
      try {
        // get tls info
        $tlsInfo = (object) $this->tlsService->getTlsCert($vhost->getHostname());
        // does a certificate exist?
        /* if ( isset($tlsInfo->cert) && $tlsInfo->cert) { */
          $vhost->setTlsRegistrarName($tlsInfo->issuer);
          $vhost->setTlsExpDate($tlsInfo->exp);
          $vhost->setTlsDayleft($tlsInfo->days_left);

          return $vhost;
        /* } */
      }
      catch(Exception $err) {
        throw $err;
      }
      // no tls certificate
      return null;
    }

    public function updateTlsInfoById( string $id): ?Vhost {

      $vhost = $this->vhostRepo->find($id); 
      if ($vhost) {
        $updateVhost = $this->updateTls($vhost);
        if ($updateVhost) {
          try {
            $this->em->persist($vhost);
            $this->em->flush();
          }
          catch(Exception $err) {
            // error while trying to update a Vhost
            throw $err;
          }
        }
      }
      // cannot update 
      return null; 
    }

}
