<?php

namespace App\Controller;

use App\Constraints\PurchaseConstraints;
use App\Exception\ValidationException;
use App\Service\SubscriptionService;
use App\Service\Validation\ValidationService;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    /**
     * @throws ValidationException
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    #[Route('/api/purchase', name: 'app_purchase', methods: 'POST')]
    public function purchase(Request $request, ValidationService $validationService, SubscriptionService $subscriptionService): JsonResponse
    {
        $contentData = json_decode($request->getContent(), true);
        $errors = $validationService->validate($contentData, PurchaseConstraints::verify());
        if (count($errors) > 0) {
            throw new ValidationException(message: 'Validation Exception', errors: $errors);
        }
        $contentData['clientToken'] = $this->getClientTokenFromHeader($request);
        $response = $subscriptionService->subscribe($contentData);
        return $this->json(json_decode($response, true));
    }
}
