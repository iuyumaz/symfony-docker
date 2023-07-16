<?php

namespace App\Message\Event\Subscription;

use App\Client\OutsideMockClient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;

class EventFactory
{

    public function __construct(protected ManagerRegistry $managerRegistry, protected OutsideMockClient $mockClient, protected MessageBusInterface $bus)
    {
    }

    /**
     * @param $eventName
     * @return mixed
     * @throws \Exception
     */
    public function create($eventName): AbstractEvent
    {
        $className = "\\App\\Message\\Event\\Subscription\\" . ucfirst($eventName) . "Event";
        if (!class_exists($className)) {
            throw new \Exception("Event Not Found.");
        }
        return new $className($this->managerRegistry->getManager(), $this->mockClient, $this->bus);
    }

}
