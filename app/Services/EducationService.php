<?php

namespace App\Services;

use App;
use App\Constant;
use App\Models\About;
use App\Models\Education;
use App\Services\Contracts\EducationContract;
use Constants;
use Log;
use Validator;

class EducationService implements EducationContract
{
    /**
     * Eloquent instance
     * 
     * @var Education
     */
    private $model;

    /**
     * Create a new service instance
     * 
     * @param Education $education 
     * @return void
     */
    public function __construct(Education $education)
    {
        $this->model = $education;
    }

    /**
     * Get all fields
     * 
     * @param array $select
     * @return array 
     */
    public function getAllFields(array $select = ['*'])
    {
        try {
            $response = $this->model->select($select)->get();
            if ($response) {
                return [
                    'message' => 'Data is fetched successfully',
                    'payload' => $response,
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'No result found',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Store/update data
     * 
     * @param array $data 
     * @return array 
     */
    public function store(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'institution' => 'required|string',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Validation Error',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $newData['institution'] = $data['institution'];
            $newData['period'] = !empty($data['period']) ? $data['period'] : null;
            $newData['degree'] = !empty($data['degree']) ? $data['degree'] : null;
            $newData['cgpa'] = !empty($data['cgpa']) ? $data['cgpa'] : null;
            $newData['department'] = !empty($data['department']) ? $data['department'] : null;
            $newData['thesis'] = !empty($data['thesis']) ? $data['thesis'] : null;
            
            if (!empty($data['id'])) {
                $response = $this->getById($data['id'], ['id']);
                if ($response['status'] !== Constants::STATUS_CODE_SUCCESS) {
                    return $response;
                } else {
                    $existingData = $response['payload'];
                }
                $response = $existingData->update($newData);
            } else {
                $response = $this->model->create($newData);
            }

            if ($response) {
                return [
                    'message' => !empty($data['id']) ? 'Data is successfully updated' : 'Data is successfully saved',
                    'payload' => $response,
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Fetch data by id
     * 
     * @param int $educationId
     * @param array $select
     * @return array
     */
    public function getById(int $educationId, array $select = ['*'])
    {
        try {
            $data = $this->model->select($select)->where('id', $educationId)->first();
            
            if ($data) {
                return [
                    'message' => 'Data is fetched successfully',
                    'payload' => $data,
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'No result is found',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Get all fields with paginate
     * 
     * @param array $data
     * @param array $select
     * @return array 
     */
    public function getAllFieldsWithPaginate(array $data, array $select = ['*'])
    {
        try {
            $perPage  = !empty($data['params']) && !empty(json_decode($data['params'])->pageSize) ? json_decode($data['params'])->pageSize : 10;
            
            if (!empty($data['sorter']) && count(json_decode($data['sorter'], true))) {
                $sorter = json_decode($data['sorter'], true);
                foreach ($sorter as $key => $value) {
                    $sortBy = $key;
                    $sortType = ($value === 'ascend' ? 'asc' : 'desc');
                }
            } else {
                $sortBy = 'created_at';
                $sortType = 'desc';
            }
            
            $result = $this->model->select($select)->orderBy($sortBy, $sortType);

            if (!empty($data['params']) && !empty(json_decode($data['params'])->keyword) && json_decode($data['params'])->keyword !== '') {
                $searchQuery = json_decode($data['params'])->keyword;
                $columns = !empty($data['columns']) ? $data['columns'] : null;
                
                if ($columns) {
                    $result->where(function($query) use ($columns, $searchQuery) {
                        foreach ($columns as $key => $column) {
                            if (!empty(json_decode($column)->search) && json_decode($column)->search === true) {
                                $fieldName = json_decode($column)->dataIndex;
                                $query->orWhere($fieldName, 'like', '%' . $searchQuery . '%');
                            }
                        }
                    });
                }
            }

            $result = $result->paginate($perPage); 
            
            if ($result) {
                return [
                    'message' => 'Data is fetched successfully',
                    'payload' => $result,
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'No result found',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Delete data by id array
     * 
     * @param array $ids
     * @return array
     */
    public function deleteByIds(array $ids)
    {
        try {
            $data = $this->model->whereIn('id', $ids)->delete();
            
            if ($data) {
                return [
                    'message' => 'Data is deleted successfully',
                    'payload' => $data,
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Nothing to Delete',
                    'payload' => null,
                    'status' => Constants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => Constants::STATUS_CODE_ERROR
            ];
        }
    }
}