<?php

namespace App\Controller;

use App\Constraints\ApplicationConstraints;
use App\Entity\Application;
use App\Exception\ValidationException;
use App\Service\ApplicationService;
use App\Service\Validation\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    /**
     * @throws ValidationException
     */
    #[Route('/api/application', name: 'app_application', methods: 'POST')]
    public function index(Request $request, ValidationService $validationService, ApplicationService $applicationService): JsonResponse
    {
        $contentData = json_decode($request->getContent(), true);
        $errors = $validationService->validate($contentData, ApplicationConstraints::verify());
        if (count($errors) > 0) {
            throw new ValidationException(message: 'Validation Exception', errors: $errors);
        }
        /** @var Application $application */
        $application = $applicationService->addApplication($contentData);

        return $this->json([
            'id' => $application->getId(),
            'name' => $application->getName(),
            'callbackUrl' => $application->getCallbackUrl()
        ]);
    }
}
