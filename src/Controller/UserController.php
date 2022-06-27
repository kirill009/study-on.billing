<?php

namespace App\Controller;

use App\Entity\User;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/users/current", name="current", methods={"GET"})
     *
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=200,
     *     description="Success"
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="username", type="string"),
     *        @OA\Property(property="roles", type="araay"),
     *        @OA\Property(property="balance", type="float"),
     *     ),
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Error"
     * )
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $response = new JsonResponse(['data' => 'User not found'], 404);
        } else {
            $entityManager = $this->getDoctrine()->getManager();
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $user->getUsername()]);
            $data = [
                'username' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'balance' => $user->getBalance(),
            ];
            $response = new JsonResponse($data, 200);
        }
        
        return $response;
    }
}
