<?php

namespace App\EventListener;

use App\Entity\ServerInfo;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ServerInfoCrypt {
    private $algo = 'aes-256-ctr';
    private $private_key = '123456';
    
    private function encrypt (string $data, string $iv) {
        $encryptedData = openssl_encrypt($data, $this->algo, $this->private_key, 0, $iv);
        return $encryptedData;
    }

    private function decrypt (string $data, string $iv) {
        $encryptedData = openssl_decrypt($data, $this->algo, $this->private_key, 0, $iv);
        return $encryptedData;
    }

    /* encrypt on create entity and generate an initialization vector(iv) to encrypt */
    public function prePersist(LifecycleEventArgs $args): void
    {
        /* encrypted ServerInfo field :
         *    - name 
         */
        $entity = $args->getObject();

        // this listener only applies on ServerInfo entity 
        if (!$entity instanceof ServerInfo) {
            return;
        }
        $entityName =  $entity->getName();

        if ($entityName) {
            $iv = random_bytes(16);
            // encode in base64 to persist in db
            $ivBase64 = base64_encode($iv);
            // dd(base64_decode($iv));
            $entity->setIv($ivBase64);
            $encryptedName = $this->encrypt($entityName, $iv);
            $entity->setName($encryptedName);
        }

        // $entityManager = $args->getObjectManager();
    }

    /* encrypt on update*/
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // this listener only applies on ServerInfo entity 
        if (!$entity instanceof ServerInfo) {
            return;
        }
        $entityName =  $entity->getName();

        if ($entityName) {
            $iv = base64_decode($entity->getIv());
            $encryptedName = $this->encrypt($entityName, $iv);
            $entity->setName($encryptedName);
        }

        // $entityManager = $args->getObjectManager();
    }


    /* decrypt after loading ServerInfo entity */
    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // this listener only applies on ServerInfo entity 
        if (!$entity instanceof ServerInfo) {
            return;
        }

        $entityName =  $entity->getName();
        if ($entityName && !is_null($entity->getIv())) {
            $iv = base64_decode($entity->getIv());
            // dd(base64_decode($iv));
            $decryptedName = $this->decrypt($entityName, $iv);
            $entity->setName($decryptedName);
        }
        $entityManager = $args->getObjectManager();
        // dd('inside postLoad', $entity);

    } 
}