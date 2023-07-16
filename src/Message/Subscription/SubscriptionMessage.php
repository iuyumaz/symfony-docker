<?php

namespace App\Message\Subscription;

class SubscriptionMessage
{
    protected int $subscriptionId;

    protected string $newStatus;

    /**
     * @return mixed
     */
    public function getSubscriptionId(): mixed
    {
        return $this->subscriptionId;
    }

    /**
     * @param mixed $subscriptionId
     */
    public function setSubscriptionId(mixed $subscriptionId): void
    {
        $this->subscriptionId = $subscriptionId;
    }

    /**
     * @return mixed
     */
    public function getNewStatus(): mixed
    {
        return $this->newStatus;
    }

    /**
     * @param mixed $newStatus
     */
    public function setNewStatus(mixed $newStatus): void
    {
        $this->newStatus = $newStatus;
    }


}
