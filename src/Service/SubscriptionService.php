<?php

namespace App\Service;

use App\Client\GoogleApiMockClient;
use App\Constants\SubscriptionConstants;
use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriptionService extends AbstractService
{
    public function __construct(protected ManagerRegistry $managerRegistry, protected GoogleApiMockClient $googleApiMockClient, protected DeviceService $deviceService)
    {
    }

    /**
     * @param $contentData
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function subscribe($contentData): string
    {
        $device = $this->deviceService->getDeviceByClientToken($contentData['clientToken']);
        if ($this->getRedisClient()->get('subscription_client_token_' . $contentData['clientToken'])) {
            return $this->getRedisClient()->get('subscription_client_token_' . $contentData['clientToken']);
        }
        $response = $this->googleApiMockClient->makeMockRequest($contentData);
        $responseContent = json_decode($response->getBody()->getContents(), true);
        if (!$responseContent['status']) {
            throw new BadRequestHttpException("Not Valid Receipt.");
        }

        return $this->insertNewSubscription($device, $contentData, $responseContent);

    }

    /**
     * @param $clientToken
     * @return string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSubscriptionByClientToken($clientToken): string
    {
        if ($this->getRedisClient()->get('subscription_client_token_' . $clientToken)) {
            return $this->getRedisClient()->get('subscription_client_token_' . $clientToken);
        }
        /** @var SubscriptionRepository $repo */
        $repo = $this->managerRegistry->getManager()->getRepository(Subscription::class);
        /** @var Subscription $subscription */
        $subscription = $repo->findSubscriptionByClientToken($clientToken);
        if (!$subscription) {
            throw new NotFoundHttpException("Not Found Related Entity.");
        }
        $expireTimestamp = $subscription->getExpiresAt()->getTimestamp();
        $ttl = 3600;
        if (in_array($subscription->getStatus(), [SubscriptionConstants::STATUS_STARTED, SubscriptionConstants::STATUS_RENEWED]) && $expireTimestamp < time() + 3600) {
            $ttl = intval(($expireTimestamp - time()) / 2); // 2 ye bölümü kadar cacheleyelim.
        }

        $subscriptionData = json_encode(['status' => $subscription->getStatus(), 'expireDate' => $subscription->getExpiresAt()->format('Y-m-d H:i:s')]);
        $this->getRedisClient()->set($clientToken . ':subscription_client_token', $subscriptionData, expireTTL: $ttl);

        return $subscriptionData;
    }

    /**
     * @param $id
     * @param $status
     * @return Subscription
     */
    public function updateSubscriptionStatus($id, $status): Subscription
    {
        $manager = $this->managerRegistry->getManager();
        $subscriptionRepo = $manager->getRepository(Subscription::class);
        $subscription = $subscriptionRepo->find($id);
        if (null === $subscription) {
            throw new NotFoundHttpException("Not found related subscription, id :  " . $id);
        }
        $subscription->setStatus($status);
        $manager->persist($subscription);
        $manager->flush();
        return $subscription;

    }

    /**
     * @param $device
     * @param $contentData
     * @param $responseContent
     * @return false|string
     */
    protected function insertNewSubscription($device, $contentData, $responseContent): bool|string
    {
        $subscription = new Subscription();
        $subscription->setDevice($device);
        $subscription->setReceipt($contentData['receipt']);
        $datetime = new \DateTime();
        $datetime->setTimestamp($responseContent['expireTimestamp']);
        $subscription->setExpiresAt($datetime);
        $subscription->setStatus(SubscriptionConstants::STATUS_STARTED);
        $manager = $this->managerRegistry->getManager();
        $manager->persist($subscription);
        $manager->flush();
        $subscriptionData = json_encode(['status' => $subscription->getStatus(), 'expireDate' => $subscription->getExpiresAt()->format('Y-m-d H:i:s')]);
        $this->getRedisClient()->set('subscription_client_token_' . $contentData['clientToken'], $subscriptionData, expireTTL: 3600);
        return $subscriptionData;
    }

}
