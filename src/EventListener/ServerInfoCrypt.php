<?php

namespace App\EventListener;

use App\Entity\ServerInfo;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Exception;

class ServerInfoCrypt {
    private $algo = 'aes-256-ctr';
    //  using symfony vault to keep secrets
    //  configuring vault and a passphrase for encrypting the database:
    //  https://symfony.com/doc/6.1/configuration/secrets.html
    //  read the doc for the production mode
    //
    //  $privatePassword argument is injected from service.yaml
    //
     
    public function __construct(private string $privatePassword) {}
    
    private function encrypt (string $data, string $iv): string | false {
        try {
            $encryptedData = openssl_encrypt($data, $this->algo, $this->privatePassword, 0, $iv);
            return $encryptedData;
        }
        catch(Exception $err) {
            throw $err;
        }
    }

    private function decrypt (string $data, string $iv) {
        try {
            $encryptedData = openssl_decrypt($data, $this->algo, $this->privatePassword, 0, $iv);
            return $encryptedData;
        }
        catch(Exception $err) {
            throw $err;
        }
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
            try {
                $iv = random_bytes(16);
                // encode in base64 to persist in db
                $ivBase64 = base64_encode($iv);
                // dd(base64_decode($iv));
                $entity->setIv($ivBase64);
                $encryptedName = $this->encrypt($entityName, $iv);
                $entity->setName($encryptedName);
            } 
            catch (Exception $err) {
                throw $err;
            }
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
            try {
                $iv = base64_decode($entity->getIv());
                $encryptedName = $this->encrypt($entityName, $iv);
                $entity->setName($encryptedName);
            }
            catch(Exception $err) {
                throw $err;
            }
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
            try {
                $iv = base64_decode($entity->getIv());
                // dd(base64_decode($iv));
                $decryptedName = $this->decrypt($entityName, $iv);
                $entity->setName($decryptedName);
            }
            catch (Exception $err) {
                throw $err;
            }
        }
        // $entityManager = $args->getObjectManager();
        // dd('inside postLoad', $entity);

    } 
}