<?php

namespace App\Model;

use App\Api\Login\RequestDtoCredentialsLogin;
use App\Api\Login\ResponseDtoCredentialsLogin;
use App\Repository\CustomerRepository;

class LoginModel
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function login(RequestDtoCredentialsLogin $dto): ResponseDtoCredentialsLogin
    {
        $customer = $this->customerRepository->findOneBy(['email' => $dto->email]);

        if ($customer === null) {
            return new ResponseDtoCredentialsLogin('Login failed', 400);
        }

        if (password_verify($dto->password, $customer->getPassword())) {
            return new ResponseDtoCredentialsLogin('Login successful', 200);
        }

        return new ResponseDtoCredentialsLogin('Login failed', 400);
    }
}