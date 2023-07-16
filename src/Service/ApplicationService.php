<?php

namespace App\Service;

use App\Entity\Application;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplicationService extends AbstractService
{
    public function __construct(protected ManagerRegistry $managerRegistry)
    {
    }

    /**
     * @param $contentData
     * @return Application
     */
    public function addApplication($contentData): Application
    {
        $manager = $this->managerRegistry->getManager();
        $application = new Application();
        $application->setName($contentData['name']);
        $application->setCallbackUrl($contentData['callbackUrl']);
        $manager->persist($application);
        $manager->flush();
        return $application;
    }

    /**
     * @param $appId
     * @return Application|object
     */
    public function getAppById($appId)
    {
        $application = $this->managerRegistry->getManager()->getRepository(Application::class)->find($appId);
        if (!$application) {
            throw new NotFoundHttpException("Not found application with id : " . $appId);
        }
        return $application;
    }

}
