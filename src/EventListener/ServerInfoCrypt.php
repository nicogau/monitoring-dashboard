<?php

namespace App\EventListener;

use App\Entity\ServerInfo;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Exception;

class ServerInfoCrypt {
    private const ALGO = 'aes-256-ctr';
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
            $encryptedData = openssl_encrypt($data, self::ALGO, $this->privatePassword, 0, $iv);
            return $encryptedData;
        }
        catch(Exception $err) {
            throw $err;
        }
    }

    private function decrypt (string $data, string $iv) {
        try {
            $encryptedData = openssl_decrypt($data, self::ALGO, $this->privatePassword, 0, $iv);
            return $encryptedData;
        }
        catch(Exception $err) {
            throw $err;
        }
    }

    /* encrypt entity  before persist in db and generate an initialization vector(iv) to encrypt */
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
        /* generate an initialization vector(iv) to encrypt */
        $iv = random_bytes(16);
        // encode in base64 to persist in db
        $ivBase64 = base64_encode($iv);
        $entity->setIv($ivBase64);

        $entityName =  $entity->getName();
        $entityIp = $entity->getIp();
        $entitySshHostKey =$entity->getSshHostKey();

        if ($entityName) {
                $encryptedName = $this->encrypt($entityName, $iv);
                $entity->setName($encryptedName);
            } 
        if ($entityIp) {
                $encryptedIp = $this->encrypt($entityIp, $iv);
                $entity->setIp($encryptedIp);
            } 
        if ($entitySshHostKey) {
                $encryptedSshKey = $this->encrypt($entitySshHostKey, $iv);
                $entity->setSshhostKey($encryptedSshKey);
            } 

        // Encrypt other fields here
        // ...
        // $encryptedField = $this->encrypt($entity->Getfield()->, $iv);
        // $entity->setField($encryptedField);

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
        $entityIp = $entity->getIp();
        $entitySshHostKey =$entity->getSshHostKey();

        // decode   iv 
        $iv = base64_decode($entity->getIv());


        if ($entityName) {
                $encryptedName = $this->encrypt($entityName, $iv);
                $entity->setName($encryptedName);
        }

        if ($entityIp) {
                $encryptedIp = $this->encrypt($entityIp, $iv);
                $entity->setIp($encryptedIp);
            } 
        if ($entitySshHostKey) {
                $encryptedSshKey = $this->encrypt($entitySshHostKey, $iv);
                $entity->setSshHostKey($encryptedSshKey);
            } 

        // Encrypt other fields here
        // ...
        // $encryptedField = $this->encrypt($entity->Getfield()->, $iv);
        // $entity->setField($encryptedField);
        // $entityManager = $args->getObjectManager();
    }


    /* decrypt after loading ServerInfo entity */
    public function postLoad(LifecycleEventArgs $args) 
    {
        $entity = $args->getObject();

        // this listener only applies on ServerInfo entity 
        if (!$entity instanceof ServerInfo) {
            return;
        }


        if (is_null($entity->getIv())){
            throw new Exception("error while decrypting data");
        }
        
        // decode iv
        $iv = base64_decode($entity->getIv());

        $entityName =  $entity->getName();
        $entityIp = $entity->getIp();
        $entitySshHostKey =$entity->getSshHostKey();

        if ($entityName) {
            $decryptedName = $this->decrypt($entityName, $iv);
            $entity->setName($decryptedName);
        }
        if ($entityIp) {
                $decryptedIp = $this->decrypt($entityIp, $iv);
                $entity->setIp($decryptedIp);
            } 
        if ($entitySshHostKey) {
                $decryptedSshKey = $this->decrypt($entitySshHostKey, $iv);
                $entity->setSshHostKey($decryptedSshKey);
            } 
            // Decrypt other fields here
            // ...
            // $deCryptedField = $this->decrypt($entity->Getfield()->, $iv);
            // $entity->setField($encryptedField);

        // $entityManager = $args->getObjectManager();
    } 
}