<?php

namespace App\Listener;

use App\Entity\Subscription;
use App\Message\Subscription\SubscriptionMessage;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class SubscriptionListener implements EventSubscriber
{

    public function __construct(protected MessageBusInterface $bus)
    {
    }

    public function getSubscribedEvents(): array
    {

        return ['postUpdate'];
    }

    /**
     * @param PostUpdateEventArgs $args
     */
    public function postUpdate(PostUpdateEventArgs $args)
    {
        /** @var Subscription $subscription */
        $subscription = $args->getObject();
        if ($subscription instanceof Subscription !== true) {
            return;
        }
        $uow = $args->getObjectManager()->getUnitOfWork();
        $uow->computeChangeSets();

        $changeSet = $uow->getEntityChangeSet($subscription);
        if (!isset($changeSet['status'])) {
            return;
        }
        $newStatus = $changeSet['status'][1];

        $message = new SubscriptionMessage();
        $message->setSubscriptionId($subscription->getId());
        $message->setNewStatus($newStatus);
        $this->bus->dispatch($message);
    }
}
