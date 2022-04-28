<?php

namespace App\Entity;

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
 */
class Phone
{
    /**
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("phone:showone")
     */
    private $id;

    /**
     * 
     * @ORM\Column(type="string", length=255)
     * @Groups("phone:showone")
     * @Groups("phone:readall")
     */
    private $brand;
    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"phone:showone"})
     * @Groups("phone:readall")
     */
    private $modelName;

    /**
     * @Groups("phone:readall")
     * @Groups("phone:showone")
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @Groups("phone:showone")
     * @Groups("phone:readall")
     * @ORM\Column(type="string", length=255)
     */
    private $description;
    /**
     *  @Groups("phone:showone")
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */

    private $price;

    /**
     *  @Groups("phone:showone")
     * @Groups("phone:readall")
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
