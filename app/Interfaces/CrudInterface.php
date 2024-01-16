<?php

namespace App\Interfaces;

interface CrudInterface
{
    /**
     * Get All Data
     *
     * @return array All Data
     */
    public function getAll();

    /**
     * Get Paginated Data
     *
     * @param int   Page No
     * @return array Paginated Data
     */
    public function getPaginatedData(int $perPage);

    /**
     * Create New Item
     *
     * @return object Created Item
     */
    public function create(array $data);

    /**
     * Delete Item By Id
     *
     * @return object Deleted Item
     */
    public function delete(int $id);

    /**
     * Get Item Details By ID
     *
     * @return object Get Item
     */
    public function getByID(int $id);

    /**
     * Update By Id and Data
     *
     * @return object Updated Information
     */
    public function update(int $id, array $data);
}
