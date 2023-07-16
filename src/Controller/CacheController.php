<?php

namespace App\Controller;

use App\Exception\ValidationException;
use App\Service\CacheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    /**
     * @param CacheService $cacheService
     * @return JsonResponse
     */
    #[Route('/api/clear-cache', name: 'app_application')]
    public function cacheClear(CacheService $cacheService): JsonResponse
    {
        $cacheService->flushAll();
        return $this->json([
            'message' => 'All cache cleared.'
        ]);
    }
}
