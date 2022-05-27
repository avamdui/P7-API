<?php

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="`customer`")
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Email déjà utilisé"
 * )

 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_customer",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *        exclusion = @Hateoas\Exclusion({"detail"})
 * )
  * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_customers",
 *          absolute = true
 *      ),
 *        exclusion = @Hateoas\Exclusion({"list"})
 * )
   * @Hateoas\Relation(
 *      "Detail",
 *      href = @Hateoas\Route(
 *          "api_customer",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *        exclusion = @Hateoas\Exclusion({"list"})
 * )
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_customers",
 *          absolute = true
 *      ),
 *        exclusion = @Hateoas\Exclusion({"detail"})
 * )
  * @Hateoas\Relation(
 *      "listAll",
 *      href = @Hateoas\Route(
 *          "api_customers",
 *          absolute = true
 *      ),
 *        exclusion = @Hateoas\Exclusion({"detail"})
 * )
* @Hateoas\Relation(
 *     "create",
 *     href=@Hateoas\Route(
 *         "api_customer_create",
 *         absolute=true
 *     ),
 *        exclusion = @Hateoas\Exclusion({"detail"})

 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "api_delete_customer_id",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *        exclusion = @Hateoas\Exclusion({ "detail"})
 * )
  * @Hateoas\Relation(
 *     "Client",
 *     embedded = @Hateoas\Embedded("expr(object.getClient())"
 *      ),
 *     exclusion = @Hateoas\Exclusion({"detail"})
 * )
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "detail"})
     * @OA\Property(example="251", description="The unique identifier of the user.")
     *
     */
    private $id;

    /**
    * @Assert\NotBlank
    * @Groups({"none"})
    * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="customers")
    */
    private $client;

    /**
    * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank(message="The value should not be blank")
    * @Assert\Type(type="string", message="The value should be a string")
    * @Groups({"list", "detail", "create"})
    * @OA\Property(example="Alex")
    */
    private $firstname;

    /**
     * @Assert\NotBlank
    * @ORM\Column(type="string", length=255)
    * @Groups({"list", "detail", "create"})
    */
    private $lastname;

    /**
    * @Assert\NotBlank
    * @ORM\Column(type="string", length=25)
    * @Groups({"list", "detail", "create"})
    * @OA\Property(example="0607080910")
    */
    private $phoneNumber;

    /**
    * @Assert\NotBlank

    * @ORM\Column(type="string", length=255)
    * @OA\Property(example="Alex.duchien@voila.fr")
    * @Groups({"list", "detail", "create"})
    */
    private $email;

    /**
     * @Assert\NotBlank
    * @ORM\Column(type="string", length=255)
    * @Groups({"list", "detail", "create"})
    */
    private $password;

    /**
     * @Assert\NotBlank
    * @ORM\Column(type="datetime")
    * @Groups({"detail"})
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
