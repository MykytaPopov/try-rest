<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Inner\ClassroomBundle\Repository\ClassroomRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The classroom entity
 *
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 * @UniqueEntity("name")
 */
class Classroom
{
    use EntityHelper;

    /**
     * The unique identifier of the classroom
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * The unique name of the classroom
     *
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(
     *     min="3",
     *     max="50"
     * )
     */
    protected $name;

    /**
     * The date of the classroom creation
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @Assert\NotBlank()
     */
    protected DateTimeInterface $createdAt;

    /**
     * The active status of the classroom
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="This value should not be blank.")
     * @Assert\Type("bool")
     */
    protected $isActive;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
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

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
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
