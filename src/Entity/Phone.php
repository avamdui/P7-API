<?php
namespace App\Entity;

use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
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
 *      ),
 *        exclusion = @Hateoas\Exclusion({"list"})
 * )
 */
class Phone
{
    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "detail"})
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "detail"})
     */
    private $brand;
    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"list", "detail"})
     */
    private $modelName;

    /**
     * @Groups({"list", "detail"})
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @Groups({"list", "detail"})
     * @ORM\Column(type="string", length=255)
     */
    private $description;
    /**
     * @Groups({"detail"})
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */

    private $price;

    /**
     * @Groups({"list", "detail"})
     * @ORM\Column(type="integer"))
     */
    private $stock;

    /**
     * @Groups({"detail"})
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
