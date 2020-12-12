<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Doctrine\ORM\Mapping as ORM;
use Inner\ClassroomBundle\Repository\ClassroomRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The classroom entity
 *
 * @ApiResource(
 *     normalizationContext={
 *         "allow_extra_attributes"=false,
 *         "groups"={"classroom:read"}
 *     },
 *     denormalizationContext={
 *         "allow_extra_attributes"=false,
 *         "groups"={"classroom:write"}
 *     },
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"}
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isActive"})
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="isActive_idx", columns={"is_active"})})
 */
class Classroom
{
    /**
     * The unique identifier of the classroom
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"classroom:read"})
     */
    private int $id;

    /**
     * The unique name of the classroom
     *
     * @ORM\Column(type="string", length=50, unique=true)
     * @Groups({"classroom:read", "classroom:write"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     max="50"
     * )
     */
    private string $name;

    /**
     * The date of the classroom creation
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @Groups({"classroom:read"})
     */
    private \DateTimeInterface $createdAt;

    /**
     * The active status of the classroom
     *
     * @ORM\Column(type="boolean")
     * @Groups({"classroom:read", "classroom:write"})
     * @Assert\NotBlank()
     */
    private bool $isActive;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
