<?php

namespace App\Entity;

use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="`customer`")
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Email déjà utilisé"
 * )
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @groups({"Full", "detail"})
     * @OA\Property(example="251", description="The unique identifier of the user.")
     *
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="customers")
    */
    private $client;

    /**
    * @ORM\Column(type="string", length=255)
    * @groups({"Full", "detail", "create"})
    * @OA\Property(example="Alex")
    */
    private $firstname;

    /**
    * @ORM\Column(type="string", length=255)
    * @groups({"Full", "detail", "create"})
    */
    private $lastname;

    /**
    * @ORM\Column(type="string", length=10)
    * @groups({"Full", "detail", "create"})
    * @OA\Property(example="0607080910")
    */
    private $phoneNumber;

    /**
    * @ORM\Column(type="string", length=255)
    * @OA\Property(example="Alex.duchien@voila.fr")
    * @groups({"Full", "detail", "create"})
    */
    private $email;

    /**
    * @ORM\Column(type="string", length=255)
    * @groups({"Full", "detail", "create"})
    */
    private $password;

    /**
    * @ORM\Column(type="datetime")
    * @groups({"Full", "detail"})
    */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
