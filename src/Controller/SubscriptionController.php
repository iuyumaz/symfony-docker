<?php

namespace App\Controller;

use App\Constraints\SubscriptionConstraints;
use App\Entity\Subscription;
use App\Exception\ValidationException;
use App\Service\SubscriptionService;
use App\Service\Validation\ValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @param Request $request
     * @param SubscriptionService $subscriptionService
     * @param ValidationService $validationService
     * @return JsonResponse
     * @throws ValidationException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    #[Route('/api/subscription-check', name: 'app_subscription', methods: 'POST')]
    public function check(Request $request, SubscriptionService $subscriptionService, ValidationService $validationService): JsonResponse
    {
        $response = $subscriptionService->getSubscriptionByClientToken($this->getClientTokenFromHeader($request));
        return $this->json(json_decode($response, true));
    }

    /**
     * @param $id
     * @param Request $request
     * @param SubscriptionService $subscriptionService
     * @param ValidationService $validationService
     * @return JsonResponse
     * @throws ValidationException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    #[Route('/api/subscription/{id}', name: 'app_subscription_update', requirements: ['type' => '\d+'], methods: 'PUT')]
    public function update($id, Request $request, SubscriptionService $subscriptionService, ValidationService $validationService): JsonResponse
    {
        $contentData = json_decode($request->getContent(), true);
        $errors = $validationService->validate($contentData, SubscriptionConstraints::verifyUpdate());
        if (count($errors) > 0) {
            throw new ValidationException(message: 'Validation Exception', errors: $errors);
        }
        $contentData = json_decode($request->getContent(), true);

        $subscription = $subscriptionService->updateSubscriptionStatus($id, $contentData['status']);

        return $this->json([
            'id' => $subscription->getId(),
            'status' => $subscription->getStatus()
        ]);
    }
}
