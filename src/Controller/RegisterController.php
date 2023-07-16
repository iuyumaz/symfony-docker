<?php

namespace App\Controller;

use App\Constraints\DeviceConstraints;
use App\Exception\ValidationException;
use App\Service\DeviceService;
use App\Service\Validation\ValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    /**
     * @param Request $request
     * @param DeviceService $deviceService
     * @param ValidationService $validationService
     * @return JsonResponse
     * @throws ValidationException
     */
    #[Route('/api/register', name: 'app_register', methods: 'POST')]
    public function register(Request $request, DeviceService $deviceService, ValidationService $validationService): JsonResponse
    {
        $contentData = json_decode($request->getContent(), true);
        $errors = $validationService->validate($contentData, DeviceConstraints::verify());
        if (count($errors) > 0) {
            throw new ValidationException(message: 'Validation Exception', errors: $errors);
        }
        $clientToken = $deviceService->register($contentData);

        return $this->json([
            'token' => $clientToken
        ]);
    }

}
