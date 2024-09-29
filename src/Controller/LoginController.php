<?php

namespace App\Controller;

use App\Api\Login\ApiLoginCredentialsLoginRequest;
use App\Api\Login\ApiLoginCredentialsLoginResponse;
use App\Exception\ValidationException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class LoginController extends AbstractController
{
    /**
     * @throws ValidationException
     */
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    #[OA\RequestBody(
        content: new Model(type: ApiLoginCredentialsLoginRequest::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Authenticates a user with valid credentials',
        content: new Model(type: ApiLoginCredentialsLoginResponse::class)
    )]
    public function login(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        // Deserialize the payload
        $requestDto = $serializer->deserialize($request->getContent(), ApiLoginCredentialsLoginRequest::class, 'json');

        // Validate the resulting dto
        $violations = $validator->validate($requestDto);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        // Processing
        //$data = TrackModel::create($addDto);

        // Response
        //return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);

        //$request->getContent();
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LoginController.php',
            'request' => $requestDto,
        ]);
    }
}
