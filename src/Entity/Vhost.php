<?php

namespace App\Entity;

use App\Repository\VhostRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VhostRepository::class)]
#[UniqueEntity(
    fields: ['hostname'],
    message: 'un hôte virtuel avec cette adresse existe déjà'
    )]
#[ORM\HasLifecycleCallbacks]
class Vhost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $tlsExpDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tlsRegistrarName = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $hostname = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'vhosts')]
    private ?ServerInfo $server = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tlsDayleft = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getTlsExpDate(): ?\DateTimeInterface
    {
        return $this->tlsExpDate;
    }

    public function setTlsExpDate(?\DateTimeInterface $tlsExpDate): self
    {
        $this->tlsExpDate = $tlsExpDate;

        return $this;
    }

    public function getTlsRegistrarName(): ?string
    {
        return $this->tlsRegistrarName;
    }

    public function setTlsRegistrarName(?string $tlsRegistrarName): self
    {
        $this->tlsRegistrarName = $tlsRegistrarName;

        return $this;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(?string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function getServer(): ?ServerInfo
    {
        return $this->server;
    }

    public function setServer(?ServerInfo $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function __toString(): string 
    {
        $exp_date_msg = $this->tlsExpDate ? `{cert: $this->tlsExpDate}days left` : '';
        return "{$this->hostname} {$exp_date_msg}";
    }

    public function getTlsDayleft(): ?string
    {
        return $this->tlsDayleft;
    }

    public function setTlsDayleft(?string $tlsDayleft): self
    {
        $this->tlsDayleft = $tlsDayleft;

        return $this;
    }
}
