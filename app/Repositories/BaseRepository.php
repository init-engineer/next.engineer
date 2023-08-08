<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Modified from: https://github.com/Okipa/laravel-base-repository
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The repository associated main model.
     */
    protected $model;

    /**
     * The repository associated request.
     */
    protected Request $request;

    /**
     * Default attributes to automatically except from request treatments.
     */
    protected array $defaultAttributesToExcept = [
        '_token',
        '_method',
    ];

    /**
     * Automatically except defined $defaultAttributesToExcept from the request treatments.
     */
    protected bool $exceptDefaultAttributes = true;

    public function __construct()
    {
        if ($this->model) {
            $this->setModel($this->model);
        }
        $this->setRequest(request());
    }

    /**
     * Set the repository request to use.
     */
    public function setRequest(Request $request): BaseRepository
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Create multiple model instances from the request data.
     * The use of this method suppose that your request is correctly formatted.
     * If not, you can use the $exceptFromSaving and $addToSaving attributes to do so.
     */
    public function createOrUpdateMultipleFromRequest(
        array $attributesToAddOrReplace = [],
        array $attributesToExcept = [],
        bool $saveMissingModelFillableAttributesToNull = true
    ): Collection {
        $this->exceptAttributesFromRequest($attributesToExcept);
        $this->addOrReplaceAttributesInRequest($attributesToAddOrReplace);

        return $this->createOrUpdateMultipleFromArray($this->request->all(), $saveMissingModelFillableAttributesToNull);
    }

    /**
     * Except attributes from request.
     */
    protected function exceptAttributesFromRequest(array $attributesToExcept = []): void
    {
        if ($this->exceptDefaultAttributes) {
            $attributesToExcept = array_merge($this->defaultAttributesToExcept, $attributesToExcept);
        }
        $this->request->replace($this->request->except($attributesToExcept));
    }

    /**
     * Add or replace attributes in request.
     */
    protected function addOrReplaceAttributesInRequest(array $attributesToAddOrReplace = []): void
    {
        $attributesToAddOrReplaceArray = [];
        foreach ($attributesToAddOrReplace as $key => $value) {
            Arr::set($attributesToAddOrReplaceArray, $key, $value);
        }
        $newRequestAttributes = array_replace_recursive($this->request->all(), $attributesToAddOrReplaceArray);
        $this->request->replace($newRequestAttributes);
    }

    /**
     * Create one or more model instances from data array.
     * The use of this method suppose that your array is correctly formatted.
     */
    public function createOrUpdateMultipleFromArray(
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Collection {
        $models = new Collection();
        foreach ($data as $instanceData) {
            $models->push($this->createOrUpdateFromArray($instanceData, $saveMissingModelFillableAttributesToNull));
        }

        return $models;
    }

    /**
     * Create or update a model instance from data array.
     * The use of this method suppose that your array is correctly formatted.
     */
    public function createOrUpdateFromArray(
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Model {
        $primary = $this->getModelPrimaryFromArray($data);

        return $primary
            ? $this->updateByPrimary($primary, $data, $saveMissingModelFillableAttributesToNull)
            : $this->getModel()->create($data);
    }

    /**
     * Get model primary value from a data array.
     */
    protected function getModelPrimaryFromArray(array $data): mixed
    {
        return Arr::get($data, $this->getModel()->getKeyName());
    }

    /**
     * Get the repository model.
     */
    protected function getModel(): Model
    {
        if ($this->model instanceof Model) {
            return $this->model;
        }
        throw new ModelNotFoundException(
            'You must declare your repository $model attribute with an Illuminate\Database\Eloquent\Model '
            .'namespace to use this feature.'
        );
    }

    /**
     * Set the repository model class to instantiate.
     */
    public function setModel(string $modelClass): BaseRepository
    {
        $this->model = app($modelClass);

        return $this;
    }

    /**
     * Update a model instance from its primary key.
     */
    public function updateByPrimary(
        int $primary,
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Model {
        $instance = $this->getModel()->findOrFail($primary);
        $data = $saveMissingModelFillableAttributesToNull ? $this->setMissingFillableAttributesToNull($data) : $data;
        $instance->update($data);

        return $instance;
    }

    /**
     * Add the missing model fillable attributes with a null value.
     */
    public function setMissingFillableAttributesToNull(array $data): array
    {
        $fillableAttributes = $this->getModel()->getFillable();
        $dataWithMissingAttributesToNull = [];
        foreach ($fillableAttributes as $fillableAttribute) {
            $dataWithMissingAttributesToNull[$fillableAttribute] =
                isset($data[$fillableAttribute]) ? $data[$fillableAttribute] : null;
        }

        return $dataWithMissingAttributesToNull;
    }

    /**
     * Create or update a model instance from the request data.
     * The use of this method suppose that your request is correctly formatted.
     * If not, you can use the $exceptFromSaving and $addToSaving attributes to do so.
     */
    public function createOrUpdateFromRequest(
        array $attributesToAddOrReplace = [],
        array $attributesToExcept = [],
        bool $saveMissingModelFillableAttributesToNull = true
    ): Model {
        $this->exceptAttributesFromRequest($attributesToExcept);
        $this->addOrReplaceAttributesInRequest($attributesToAddOrReplace);

        return $this->createOrUpdateFromArray($this->request->all(), $saveMissingModelFillableAttributesToNull);
    }

    /**
     * Delete a model instance from the request data.
     */
    public function deleteFromRequest(
        array $attributesToAddOrReplace = [],
        array $attributesToExcept = []
    ): ?bool {
        $this->exceptAttributesFromRequest($attributesToExcept);
        $this->addOrReplaceAttributesInRequest($attributesToAddOrReplace);

        return $this->deleteFromArray($this->request->all());
    }

    /**
     * Delete a model instance from a data array.
     */
    public function deleteFromArray(array $data): bool
    {
        $primary = $this->getModelPrimaryFromArray($data);

        return $this->getModel()->findOrFail($primary)->delete();
    }

    /**
     * Delete a model instance from its primary key.
     */
    public function deleteByPrimary(int $primary): ?bool
    {
        return $this->getModel()->findOrFail($primary)->delete();
    }

    /**
     * Delete multiple model instances from their primary keys.
     */
    public function deleteMultipleFromPrimaries(array $instancePrimaries): int
    {
        return $this->getModel()->destroy($instancePrimaries);
    }

    /**
     * Force delete a model instance from its primary key.
     */
    public function forceDeleteByPrimary(int $primary): ?bool
    {
        return $this->getModel()->findOrFail($primary)->forceDelete();
    }

    public function restoryByPrimary(int $primary): ?bool
    {
        return $this->getModel()->findOrFail($primary)->restory();
    }

    /**
     * Paginate array results.
     */
    public function paginateArrayResults(
        array $data,
        int $perPage = 20
    ): LengthAwarePaginator {
        $page = $this->request->input('page', 1);
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_slice($data, $offset, $perPage, false),
            count($data),
            $perPage,
            $page,
            [
                'path' => $this->request->url(),
                'query' => $this->request->query(),
            ]
        );
    }

    /**
     * Find one model instance from its primary key value.
     */
    public function findOneByPrimary(
        int $primary,
        bool $throwsExceptionIfNotFound = true
    ): ?Model {
        return $throwsExceptionIfNotFound
            ? $this->getModel()->findOrFail($primary)
            : $this->getModel()->find($primary);
    }

    /**
     * Find one model instance from an associative array.
     */
    public function findOneFromArray(
        array $data,
        bool $throwsExceptionIfNotFound = true
    ): ?Model {
        return $throwsExceptionIfNotFound
            ? $this->getModel()->where($data)->firstOrFail()
            : $this->getModel()->where($data)->first();
    }

    /**
     * Find multiple model instances from a « where » parameters array.
     */
    public function findMultipleFromArray(array $data): Collection
    {
        return $this->getModel()->where($data)->get();
    }

    /**
     * Get all model instances from database.
     */
    public function getAll(
        array $columns = ['*'],
        string $orderBy = 'default',
        string $orderByDirection = 'asc'
    ): Collection {
        $orderBy = $orderBy === 'default' ? $this->getModel()->getKeyName() : $orderBy;

        return $this->getModel()->orderBy($orderBy, $orderByDirection)->get($columns);
    }

    /**
     * Instantiate a model instance with an attributes array.
     */
    public function make(array $data): Model
    {
        return app($this->getModel()->getMorphClass())->fill($data);
    }

    /**
     * Get the model unique storage instance or create one.
     */
    public function modelUniqueInstance(): Model
    {
        $modelInstance = $this->getModel()->first();
        if (! $modelInstance) {
            $modelInstance = $this->getModel()->create([]);
        }

        return $modelInstance;
    }

    /**
     * Find multiple model instances from an array of ids.
     */
    public function findMultipleFromPrimaries(array $primaries): Collection
    {
        return $this->getModel()->findMany($primaries);
    }
}
