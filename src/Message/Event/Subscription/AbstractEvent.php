<?php

namespace App\Message\Event\Subscription;

use App\Client\OutsideMockClient;
use App\Entity\Subscription;
use App\Message\Subscription\SubscriptionMessage;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractEvent
{

    public function __construct(protected ObjectManager $manager, protected OutsideMockClient $mockClient, protected MessageBusInterface $bus)
    {
    }

    /**
     * @param SubscriptionMessage $message
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessageToClient(SubscriptionMessage $message): void
    {
        $subscription = $this->manager->getRepository(Subscription::class)->find($message->getSubscriptionId());
        if (null === $subscription) {
            return;
        }
        $contentData = [
            'appID' => $subscription->getDevice()?->getApplication()?->getId(),
            'deviceID' => $subscription->getDevice()?->getId(),
            'event' => $message->getNewStatus()
        ];
        $this->mockClient->setBaseUri($subscription->getDevice()?->getApplication()?->getCallbackUrl());
        try {
            $this->mockClient->makeMockRequest($contentData);
        } catch (\Exception $e) {
            sleep(20); // Requeue için 20 saniye bıraktım.
            $this->bus->dispatch($message);
        }
    }


}
