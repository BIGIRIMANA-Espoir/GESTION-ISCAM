<?php

namespace App\Contracts;

interface RepositoryInterface
{
    /**
     * Get all records
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find a record by its ID
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    /**
     * Find records by a specific column value
     *
     * @param string $column
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhere(string $column, $value);

    /**
     * Paginate records
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15);

    /**
     * Create a new record
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Update a record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);

    /**
     * Delete a record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);
}