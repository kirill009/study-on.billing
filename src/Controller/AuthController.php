<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class AuthController extends AbstractController
{
    /**
     * @Route("/api/v1/auth", name="login", methods={"POST"})
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=200,
     *     description="Success"
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Error"
     * )
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="username", type="string", example="keting404@yandex.ru"),
     *        @OA\Property(property="password", type="string", example="123456a"),
     *     ),
     * )
     */
    public function index(): void
    {
    
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/AuthController.php',
//        ]);
    }
}
