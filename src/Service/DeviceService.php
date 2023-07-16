<?php

namespace App\Service;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class DeviceService extends AbstractService
{

    public function __construct(protected ManagerRegistry $managerRegistry, protected ApplicationService $applicationService, protected PasswordHasherFactoryInterface $passwordHasherFactory)
    {
    }

    /**
     * @param $contentData
     * @return bool|mixed|\Redis|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \RedisException
     */
    public function register($contentData)
    {
        $redisClient = $this->getRedisClient();
        if ($redisClient->get('device_uid_app_id' . $contentData['uid'] . '_' . $contentData['application']['id'])) {
            return $redisClient->get('device_uid_app_id' . $contentData['uid'] . '_' . $contentData['application']['id']);
        }

        $manager = $this->managerRegistry->getManager();
        $deviceClientToken = $this->getDeviceClientToken($manager, $contentData);
        if ($deviceClientToken) {
            return $deviceClientToken;
        }
        $device = $this->insertNewDevice($manager, $contentData);

        return $device->getClientToken();
    }

    /**
     * @param $clientToken
     * @return Device
     */
    public function getDeviceByClientToken($clientToken)
    {
        $manager = $this->managerRegistry->getManager();
        /** @var DeviceRepository $deviceRepo */
        $deviceRepo = $manager->getRepository(Device::class);
        $device = $deviceRepo->findOneByClientToken($clientToken);
        if (!$device) {
            throw new NotFoundHttpException("Related entity not found.");
        }
        return $device;
    }

    /**
     * @param $manager
     * @param $contentData
     * @return bool|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \RedisException
     */
    protected function getDeviceClientToken($manager, $contentData): bool|string|null
    {
        /** @var DeviceRepository $deviceRepo */
        $deviceRepo = $manager->getRepository(Device::class);
        $device = $deviceRepo->findOneByUid($contentData['uid']);
        if ($device) {
            $this->getRedisClient()->set('device_uid_app_id' . $contentData['uid'] . '_' . $contentData['application']['id'], $device->getClientToken());
            return $device->getClientToken();
        }
        return false;
    }

    /**
     * @param $manager
     * @param $contentData
     * @return Device
     * @throws \RedisException
     */
    protected function insertNewDevice($manager, $contentData): Device
    {
        // TODO tokeni JWT ile hazırlayıp verirsek daha güzel olur.
        $device = new Device();
        $application = $this->applicationService->getAppById($contentData['application']['id']);
        $device->setUid($contentData['uid']);
        $device->setApplication($application);
        $device->setLanguage($contentData['language']);
        $device->setOperatingSystem($contentData['operatingSystem']);
        $device->setClientToken($this->passwordHasherFactory->getPasswordHasher($device)->hash($contentData['uid'] . $application->getId()));
        $manager->persist($device);
        $manager->flush();
        $this->getRedisClient()->set('device_uid_app_id' . $contentData['uid'] . '_' . $contentData['application']['id'], $device->getClientToken());
        return $device;

    }

}
