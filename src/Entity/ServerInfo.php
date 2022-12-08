<?php

namespace App\Entity;

use App\Repository\ServerInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ServerInfoRepository::class)]
#[UniqueEntity(
    fields: ['ip'],
    message: 'un serveur avec cette adresse existe déjà'
    )]
#[ORM\HasLifecycleCallbacks]
class ServerInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false, unique:true)]
    private ?string $ip = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $diskUsed = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $diskFree = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $diskSize = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $memSize = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $saveStateLast = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $osType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $osVersion = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: Vhost::class)]
    private Collection $vhosts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    public function __construct()
    {
        $this->vhosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getDiskUsed(): ?string
    {
        return $this->diskUsed;
    }

    public function setDiskUsed(?string $diskUsed): self
    {
        $this->diskUsed = $diskUsed;

        return $this;
    }

    public function getDiskFree(): ?string
    {
        return $this->diskFree;
    }

    public function setDiskFree(string $diskFree): self
    {
        $this->diskFree = $diskFree;

        return $this;
    }

    public function getDiskSize(): ?string
    {
        return $this->diskSize;
    }

    public function setDiskSize(?string $diskSize): self
    {
        $this->diskSize = $diskSize;

        return $this;
    }

    public function getMemSize(): ?string
    {
        return $this->memSize;
    }

    public function setMemSize(?string $memSize): self
    {
        $this->memSize = $memSize;

        return $this;
    }

    public function getSaveStateLast(): ?\DateTimeInterface
    {
        return $this->saveStateLast;
    }

    public function setSaveStateLast(?\DateTimeInterface $saveStateLast): self
    {
        $this->saveStateLast = $saveStateLast;

        return $this;
    }

    public function getOsType(): ?string
    {
        return $this->osType;
    }

    public function setOsType(?string $osType): self
    {
        $this->osType = $osType;

        return $this;
    }

    public function getOsVersion(): ?string
    {
        return $this->osVersion;
    }

    public function setOsVersion(?string $osVersion): self
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection<int, Vhost>
     */
    public function getVhosts(): Collection
    {
        return $this->vhosts;
    }

    public function addVhost(Vhost $vhost): self
    {
        if (!$this->vhosts->contains($vhost)) {
            $this->vhosts->add($vhost);
            $vhost->setServer($this);
        }

        return $this;
    }

    public function removeVhost(Vhost $vhost): self
    {
        if ($this->vhosts->removeElement($vhost)) {
            // set the owning side to null (unless already changed)
            if ($vhost->getServer() === $this) {
                $vhost->setServer(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return "{$this->name} {$this->ip}";
    }

}
