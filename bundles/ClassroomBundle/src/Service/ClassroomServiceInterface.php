<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Service;

use Inner\ClassroomBundle\Entity\Classroom;

interface ClassroomServiceInterface
{
    /**
     * Find all resources
     *
     * @return Classroom[]
     */
    public function getAll(): array;

    /**
     * Find resource by specified identifier
     *
     * @param int $id The identifier to find resource
     *
     * @return Classroom
     */
    public function get(int $id): Classroom;

    /**
     * Create resource using passed data
     *
     * @param array $data The data to create resource
     *
     * @return Classroom
     */
    public function create(array $data): Classroom;

    /**
     * Update resource with specified identifier
     *
     * @param int   $id   The identifier to find resource
     * @param array $data The data to update resource
     *
     * @return Classroom
     */
    public function update(int $id, array $data): Classroom;

    /**
     * Delete resource by specified identifier
     *
     * @param int $id The identifier to delete resource
     */
    public function delete(int $id): void;
}
