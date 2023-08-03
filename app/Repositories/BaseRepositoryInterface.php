<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Modified from: https://github.com/Okipa/laravel-base-repository
 */
interface BaseRepositoryInterface
{
    /**
     * Set the repository model class to instantiate.
     */
    public function setModel(string $modelClass): BaseRepository;

    /**
     * Set the repository request to use.
     */
    public function setRequest(Request $request): BaseRepository;

    /**
     * Create multiple model instances from the request data.
     * The use of this method suppose that your request is correctly formatted.
     * If not, you can use the $exceptFromSaving and $addToSaving attributes to do so.
     */
    public function createOrUpdateMultipleFromRequest(
        array $attributesToAddOrReplace = [],
        array $attributesToExcept = [],
        bool $saveMissingModelFillableAttributesToNull = true
    ): Collection;

    /**
     * Create one or more model instances from data array.
     * The use of this method suppose that your array is correctly formatted.
     */
    public function createOrUpdateMultipleFromArray(
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Collection;

    /**
     * Create or update a model instance from data array.
     * The use of this method suppose that your array is correctly formatted.
     */
    public function createOrUpdateFromArray(
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Model;

    /**
     * Update a model instance from its primary key.
     */
    public function updateByPrimary(
        int $primary,
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Model;

    /**
     * Create or update a model instance from the request data.
     * The use of this method suppose that your request is correctly formatted.
     * If not, you can use the $exceptFromSaving and $addToSaving attributes to do so.
     */
    public function createOrUpdateFromRequest(
        array $attributesToAddOrReplace = [],
        array $attributesToExcept = [],
        bool $saveMissingModelFillableAttributesToNull = true
    ): Model;

    /**
     * Delete a model instance from the request data.
     */
    public function deleteFromRequest(
        array $attributesToAddOrReplace = [],
        array $attributesToExcept = []
    ): ?bool;

    /**
     * Delete a model instance from a data array.
     */
    public function deleteFromArray(array $data): bool;

    /**
     * Delete a model instance from its primary key.
     */
    public function deleteByPrimary(int $primary): ?bool;

    /**
     * Delete multiple model instances from their primary keys.
     */
    public function deleteMultipleFromPrimaries(array $instancePrimaries): int;

    /**
     * Paginate array results.
     */
    public function paginateArrayResults(
        array $data,
        int $perPage = 20
    ): LengthAwarePaginator;

    /**
     * Find one model instance from its primary key value.
     */
    public function findOneByPrimary(
        int $primary,
        bool $throwsExceptionIfNotFound = true
    ): ?Model;

    /**
     * Find one model instance from an associative array.
     */
    public function findOneFromArray(
        array $data,
        bool $throwsExceptionIfNotFound = true
    ): ?Model;

    /**
     * Find multiple model instances from a « where » parameters array.
     */
    public function findMultipleFromArray(array $data): Collection;

    /**
     * Get all model instances from database.
     */
    public function getAll(
        array $columns = ['*'],
        string $orderBy = 'default',
        string $orderByDirection = 'asc'
    ): Collection;

    /**
     * Instantiate a model instance with an attributes array.
     */
    public function make(array $data): Model;

    /**
     * Get the model unique storage instance or create one.
     */
    public function modelUniqueInstance(): Model;

    /**
     * Add the missing model fillable attributes with a null value.
     */
    public function setMissingFillableAttributesToNull(array $data): array;

    /**
     * Find multiple model instances from an array of ids.
     */
    public function findMultipleFromPrimaries(array $primaries): Collection;
}
