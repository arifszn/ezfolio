<?php

namespace App\Services\Contracts;

interface ServiceInterface
{
    /**
     * Get all fields
     *
     * @param array $select
     * @return array
     */
    public function getAll(array $select = ['*']);

    /**
     * Store/update data
     *
     * @param array $data
     * @return array
     */
    public function store(array $data);

    /**
     * Fetch item by id
     *
     * @param int $id
     * @param array $select
     * @return array
     */
    public function getById(int $id, array $select = ['*']);

    /**
     * Get all fields with paginate
     *
     * @param array $data
     * @param array $select
     * @return array
     */
    public function getAllWithPaginate(array $data, array $select = ['*']);

    /**
     * Delete items by id array
     *
     * @param array $ids
     * @return array
     */
    public function deleteByIds(array $ids);
}
