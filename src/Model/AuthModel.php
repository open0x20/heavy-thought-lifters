<?php

namespace App\Model;

use App\Api\Login\RequestDtoCredentialsLogin;
use App\Api\Login\RequestDtoCredentialsRegister;
use App\Api\Login\ResponseDtoCredentialsLogin;
use App\Api\Login\ResponseDtoCredentialsRegister;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AuthModel
{
    private EntityManagerInterface $entityManager;
    private CustomerRepository $customerRepository;
    private ParameterBagInterface $parameterBag;

    public function __construct(EntityManagerInterface $entityManager, CustomerRepository $customerRepository, ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        $this->customerRepository = $customerRepository;
        $this->parameterBag = $parameterBag;
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

    public function register(RequestDtoCredentialsRegister $dto): ResponseDtoCredentialsRegister
    {
        if ($this->parameterBag->get('register_endpoint_enabled') !== true) {
            return new ResponseDtoCredentialsRegister('Registering currently disabled', 403);
        }

        $customer = $this->customerRepository->findOneBy(['email' => $dto->email]);

        if ($customer !== null) {
            return new ResponseDtoCredentialsRegister('Registration failed', 400);
        }

        $customer = new Customer();
        $customer->setEmail($dto->email);
        $customer->setPassword(password_hash($dto->password, PASSWORD_DEFAULT));
        $customer->setLoginTypeId(0);
        $customer->setLastLogin(new \DateTime());
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return new ResponseDtoCredentialsRegister('Successfully registered', 200);
    }
}