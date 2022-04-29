<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    /**

     */
    public function list(CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        $customers = $customerRepository->findAll();
        $data = $serializer->serialize($customers, 'json', ['groups' => 'phone:readall']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    /**

     */
    public function show(Customer $customer, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {

        $id = $customer->getId();
        $customer = $entityManager->getRepository(Customer::class)->findOneBy(['id' => $id]);
        return  $customer;
    }
}
