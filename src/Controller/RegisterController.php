<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use JMS\Serializer\SerializerBuilder;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

class RegisterController extends AbstractController
{
    /**
     * @Route("/api/v1/register", name="register", methods={"POST"})
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=201,
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
     *        @OA\Property(property="email", type="string", example="keting40412412@yandex.ru"),
     *        @OA\Property(property="password", type="string", example="123456a"),
     *     ),
     * )
     */
    public function index(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTManager,
        RefreshTokenManagerInterface $refreshTokenManager
    ): Response
    {
        $serializer = SerializerBuilder::create()->build();
        $userDto = $serializer->deserialize($request->getContent(), UserDto::class, 'json');
        
        $errors = $validator->validate($userDto);
        if (count($errors) > 0) {
            $response = new JsonResponse(['data' => (string)$errors], 400);
            return $response;
        }

        $user = User::fromDTO($userDto);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);
    
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $response = new JsonResponse(['data' => (string)$errors], 401);
            return $response;
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    
        $refreshToken = $refreshTokenManager->create();
        $refreshToken->setUsername($user->getEmail());
        $refreshToken->setRefreshToken();
        $refreshToken->setValid((new \DateTime())->modify('+1 day'));
        $refreshTokenManager->save($refreshToken);
    
        $data = [
            'token' => $JWTManager->create($user),
            'refresh_token' => $refreshToken->getRefreshToken(),
        ];
        
        $response = new JsonResponse($data, 201);
        return $response;

    }
}
