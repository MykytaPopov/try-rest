<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Inner\ClassroomBundle\Repository\ClassroomRepository;

/**
 * The classroom entity
 *
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 */
class Classroom
{
    /**
     * The unique identifier of the classroom
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * The unique name of the classroom
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * The date of the classroom creation
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private DateTimeInterface $createdAt;

    /**
     * The active status of the classroom
     *
     * @ORM\Column(type="boolean")
     */
    private bool $isActive;

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

    public function setCreatedAt(DateTimeInterface $createdAt): self
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
