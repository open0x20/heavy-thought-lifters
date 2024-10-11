<?php

namespace App\Controller;

use App\Api\Login\RequestDtoCredentialsLogin;
use App\Api\Login\RequestDtoCredentialsRegister;
use App\Api\Login\ResponseDtoCredentialsLogin;
use App\Api\Login\ResponseDtoCredentialsRegister;
use App\Exception\ValidationException;
use App\Model\AuthModel;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class AuthController extends AbstractController
{
    /**
     * @throws ValidationException
     */
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    #[OA\RequestBody(
        content: new Model(type: RequestDtoCredentialsLogin::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful login with valid credentials',
        content: new Model(type: ResponseDtoCredentialsLogin::class)
    )]
    public function login(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, AuthModel $authModel): JsonResponse
    {
        // Deserialize the payload
        $requestDto = $serializer->deserialize($request->getContent(), RequestDtoCredentialsLogin::class, 'json');

        // Validate the resulting dto
        $violations = $validator->validate($requestDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        $response = $authModel->login($requestDto);

        // Response
        return $this->json($response, $response->status, $response->headers);
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    #[OA\RequestBody(
        content: new Model(type: RequestDtoCredentialsRegister::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successfully registered a new user',
        content: new Model(type: ResponseDtoCredentialsRegister::class)
    )]
    public function register(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, AuthModel $authModel): JsonResponse
    {
        // Deserialize the payload
        $requestDto = $serializer->deserialize($request->getContent(), RequestDtoCredentialsRegister::class, 'json');

        // Validate the resulting dto
        $violations = $validator->validate($requestDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        $response = $authModel->register($requestDto);

        // Response
        return $this->json($response, $response->status, $response->headers);
    }
}
