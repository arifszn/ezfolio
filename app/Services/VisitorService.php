<?php

namespace App\Services;

use CoreConstants;
use App\Models\Visitor;
use App\Services\Contracts\VisitorInterface;
use Carbon\Carbon;
use DB;
use Log;
use Validator;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class VisitorService implements VisitorInterface
{
    /**
     * Eloquent instance
     *
     * @var Visitor
     */
    private $model;

    /**
     * Create a new service instance
     *
     * @param Visitor $visitor
     * @return void
     */
    public function __construct(Visitor $visitor)
    {
        $this->model = $visitor;
    }

    /**
     * Get all fields
     *
     * @param array $select
     * @return array
     */
    public function getAll(array $select = ['*'])
    {
        try {
            $result = $this->model->select($select)->get();
            
            if ($result) {
                return [
                    'message' => 'Data is fetched successfully',
                    'payload' => $result,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'No result found',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
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
                'tracking_id' => 'required',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Validation Error',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $agent = new Agent();

            $trackingId = $data['tracking_id'];
            $isNew = true;
            $existingData = $this->getByTrackingId($trackingId);

            if ($existingData['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                $isNew = false;
            }

            $ip = request()->getClientIp();
            $isDesktop = $agent->isDesktop();
            $browser = $agent->browser();
            $platform = $agent->platform();
            $locationJson = Location::get($ip);

            $newData['tracking_id'] = $trackingId;
            $newData['is_new'] = $isNew;
            $newData['ip'] = $ip;
            $newData['is_desktop'] = $isDesktop;
            $newData['browser'] = $browser;
            $newData['platform'] = $platform;
            $newData['location'] = $locationJson ? $locationJson->countryName : 'Unknown';
            
            if (isset($data['id'])) {
                $result = $this->getById($data['id'], ['id']);
                if ($result['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
                    return $result;
                } else {
                    $existingData = $result['payload'];
                }
                $result = $existingData->update($newData);
            } else {
                $result = $this->model->create($newData);
            }

            if ($result) {
                return [
                    'message' => isset($data['id']) ? 'Data is successfully updated' : 'Data is successfully saved',
                    'payload' => $result,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Store item for seed
     *
     * @param array $request
     * @return array
     */
    public function forceStore(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'tracking_id' => 'required',
                'is_new' => 'required',
                'ip' => 'required',
                'is_desktop' => 'required',
                'browser' => 'required',
                'platform' => 'required',
                'location' => 'required',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Validation Error',
                    'payload' => $validate->errors(),
                    'status'  => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $newData['tracking_id'] = $data['tracking_id'];
            $newData['is_new'] = $data['is_new'];
            $newData['ip'] = $data['ip'];
            $newData['is_desktop'] = $data['is_desktop'];
            $newData['browser'] = $data['browser'];
            $newData['platform'] = $data['platform'];
            $newData['location'] = $data['location'];
            isset($data['created_at']) && $newData['created_at'] = $data['created_at'];

            $response = $this->model->create($newData);

            if ($response) {
                return [
                    'message' => 'Data is successfully saved',
                    'payload' => $response,
                    'status'  => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status'  => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Fetch item by tracking id
     *
     * @param string $trackingId
     * @param array $select
     * @return array
     */
    public function getByTrackingId(string $trackingId, array $select = ['*'])
    {
        try {
            $data = $this->model->select($select)->where('tracking_id', $trackingId)->first();
            
            if ($data) {
                return [
                    'message' => 'Data is fetched successfully',
                    'payload' => $data,
                    'status'  => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'No result is found',
                    'payload' => null,
                    'status'  => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Fetch item by id
     *
     * @param int $id
     * @param array $select
     * @return array
     */
    public function getById(int $id, array $select = ['*'])
    {
        try {
            $data = $this->model->select($select)->where('id', $id)->first();
            
            if ($data) {
                return [
                    'message' => 'Data is fetched successfully',
                    'payload' => $data,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'No result is found',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
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
    public function getAllWithPaginate(array $data, array $select = ['*'])
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
                    $result->where(function ($query) use ($columns, $searchQuery) {
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
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'No result found',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Delete items by id array
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
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Nothing to Delete',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Get visitors stats
     *
     * @param string $startDate UTC start date
     * @param string $endEnd UTC end date
     * @return array
     */
    public function getVisitorsStats($startDate = null, $endEnd = null)
    {
        try {
            $result = $this->model;

            if ($startDate) {
                $startDate = Carbon::parse($startDate)->format('Y-m-d H:i:s');
                $result = $result->where('created_at', '>=', $startDate);
            }
            if ($endEnd) {
                $endEnd = Carbon::parse($endEnd)->format('Y-m-d H:i:s');
                $result = $result->where('created_at', '<=', $endEnd);
            }

            //visitors
            $data['visitors']['total'] = $totalVisitors = (clone $result)->count();
            $data['visitors']['new'] = (clone $result)->where('is_new', CoreConstants::TRUE)->count();
            $data['visitors']['old'] = (clone $result)->where('is_new', CoreConstants::FALSE)->count();
            
            //location
            $data['location'] = (clone $result)->select('location', DB::raw('count(*) as total'))->groupBy('location')->get();

            //device
            if ($totalVisitors) {
                $data['device']['desktop'] = ((clone $result)->where('is_desktop', CoreConstants::TRUE)->count());
                $data['device']['mobile'] = ((clone $result)->where('is_desktop', CoreConstants::FALSE)->count());

                // $data['device']['mobile'] = ((clone $result)->where('is_desktop', CoreConstants::FALSE)->count() * 100) / $totalVisitors;
            } else {
                $data['device']['desktop'] = 0;
                $data['device']['mobile'] = 0;
            }

            //browser
            $data['browser'] = (clone $result)->select('browser', DB::raw('count(*) as total'))->groupBy('browser')->get();

            //platform
            $data['platform'] = (clone $result)->select('platform', DB::raw('count(*) as total'))->groupBy('platform')->get();
            

            return [
                'message' => 'Data is fetched Successfully',
                'payload' => $data,
                'status'  => CoreConstants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Delete all entries
     *
     * @return array
     */
    public function deleteAll()
    {
        try {
            $data = $this->model->truncate();
            
            if ($data) {
                return [
                    'message' => 'Stats are reset successfully',
                    'payload' => null,
                    'status'  => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Nothing to remove',
                    'payload' => null,
                    'status'  => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong',
                'payload' => $th->getMessage(),
                'status'  => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }
}
