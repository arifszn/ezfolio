<?php

namespace App\Services\Contracts;

interface VisitorInterface
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
     * Fetch item by tracking id
     *
     * @param string $trackingId
     * @param array $select
     * @return array
     */
    public function getByTrackingId(string $trackingId, array $select = ['*']);

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

    /**
     * Get visitors stats
     *
     * @param string $startDate UTC start date
     * @param string $endEnd UTC end date
     * @return array
     */
    public function getVisitorsStats($startDate = null, $endEnd = null);

    /**
     * Delete all entries
     *
     * @return array
     */
    public function deleteAll();
}
