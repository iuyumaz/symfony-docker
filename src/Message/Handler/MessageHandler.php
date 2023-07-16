<?php

namespace App\Message\Handler;

use App\Message\Event\Subscription\EventFactory;
use App\Message\Subscription\SubscriptionMessage;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MessageHandler
{
    public function __construct(protected EventFactory $eventFactory)
    {
    }

    /**
     * @param SubscriptionMessage $message
     * @return void
     * @throws \Exception
     * @throws GuzzleException
     */
    public function __invoke(SubscriptionMessage $message)
    {
        if ($this->eventFactory instanceof EventFactory) {
            $event = $this->eventFactory->create($message->getNewStatus());
            $event->sendMessageToClient($message);
        }
    }

}
