<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Device::class, cascade: ['persist', 'remove'], inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(name: 'device_id', referencedColumnName: 'id', nullable: false)]
    private ?Device $device = null;

    #[ORM\Column(length: 255)]
    private ?string $receipt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expires_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): static
    {
        $this->device = $device;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReceipt(): ?string
    {
        return $this->receipt;
    }

    /**
     * @param string|null $receipt
     */
    public function setReceipt(?string $receipt): void
    {
        $this->receipt = $receipt;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expires_at;
    }

    /**
     * @param \DateTimeInterface|null $expires_at
     */
    public function setExpiresAt(?\DateTimeInterface $expires_at): void
    {
        $this->expires_at = $expires_at;
    }

}
