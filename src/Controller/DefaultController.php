<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(
            [
                'action' => 'index',
                'time' => time()
            ]
        );
    }
}
