<?php

namespace App\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="`phone`")
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 * @UniqueEntity(
 *  fields={"modelName"},
 *  message="Modèle déja enregistré"
 * )
 * @UniqueEntity(
 *  fields={"ref"},
 *  message="Référence déjà utilisé"
 * )
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_phone",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true,
 *      )
 * )
 */
class Phone
{
    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    * @groups({"Full", "detail"})
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
    * @groups({"Full", "detail"})
     */
    private $brand;
    /**
     * @ORM\Column(type="string", length=100)
    * @groups({"Full", "detail"})
     */
    private $modelName;

    /**
    * @groups({"Full", "detail"})
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
    * @groups({"Full", "detail"})
     * @ORM\Column(type="string", length=255)
     */
    private $description;
    /**
    * @groups({"detail"})
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */

    private $price;

    /**
    * @groups({"Full", "detail"})
     * @ORM\Column(type="integer"))
     */
    private $stock;

    /**
     *  @Groups("phone:showone")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function setModelName(string $modelName): self
    {
        $this->modelName = $modelName;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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
