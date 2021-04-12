<?php

namespace App\Services;

use App\Helpers\Constants;
use App\Models\About;
use App\Services\Contracts\AboutInterface;
use Log;
use Str;
use Validator;

class AboutService implements AboutInterface
{
    /**
     * Eloquent instance
     *
     * @var About
     */
    private $model;

    /**
     * Create a new service instance
     *
     * @param About $about
     * @return void
     */
    public function __construct(About $about)
    {
        $this->model = $about;
    }

    /**
     * Get all about fields
     *
     * @param array $select
     * @return array
     */
    public function getAllFields(array $select = ['*'])
    {
        try {
            $result = $this->model->select($select)->first();

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
     * Store/update data
     *
     * @param array $data
     * @return array
     */
    public function store(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'name' => 'required|string',
                'email' => 'required|email'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $newData['name'] = $data['name'];
            $newData['email'] = $data['email'];
            $newData['phone'] = !empty($data['phone']) ? $data['phone'] : null;
            $newData['address'] = !empty($data['address']) ? $data['address'] : null;
            $newData['description'] = !empty($data['description']) ? $data['description'] : null;

            if (!empty($data['seederCV'])) {
                $newData['cv'] = $data['seederCV'];
            }
            
            $newTagLinesArray = [];
            if (!empty($data['taglines'])) {
                foreach ($data['taglines'] as $key => $tagline) {
                    if ($tagline !== null && $tagline !== '') {
                        array_push($newTagLinesArray, $tagline);
                    }
                }
            }
            $newData['taglines'] = count($newTagLinesArray) ? json_encode($newTagLinesArray) : null;
            
            $newSocialLinksArray = [];
            if (!empty($data['socialLinks'])) {
                foreach ($data['socialLinks'] as $key => $socialLink) {
                    if ($socialLink !== '' && !empty($socialLink['title']) && !empty($socialLink['link']) && !empty($socialLink['iconClass'])) {
                        array_push($newSocialLinksArray, $socialLink);
                    }
                }
            }
            $newData['social_links'] = count($newSocialLinksArray) ? json_encode($newSocialLinksArray) : null;
            
            $existedRecord = $this->getAllFields();

            if ($existedRecord['status'] === Constants::STATUS_CODE_SUCCESS) {
                $existedRecord = $existedRecord['payload'];
                $result = $existedRecord->update($newData);
            } else {
                $newData['avatar'] = 'assets/common/img/avatar/default.png';
                $newData['cover'] = 'assets/common/img/cover/default.png';
                $result = $this->model->create($newData);
            }

            if ($result) {
                return [
                    'message' => 'Data is successfully updated',
                    'payload' => $result,
                    'status' => Constants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Something went wrong',
                    'payload' => null,
                    'status'  => Constants::STATUS_CODE_ERROR
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
     * Process the update avatar request
     *
     * @param array $data
     * @return array
     */
    public function processUpdateAvatarRequest(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'file' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $file = $data['file'];
            $extension = $file->extension() ? $file->extension() : 'png';
            $fileName = Str::random(10). '_'. time() .'.'. $extension;
            $pathName = 'assets/common/img/avatar/';
            
            if (!file_exists($pathName)) {
                mkdir($pathName, 0777, true);
            }

            if ($file->move($pathName, $fileName)) {
                //delete previous avatar
                $oldAvatarResponse = $this->getAllFields(['avatar', 'id']);
                try {
                    if ($oldAvatarResponse['status'] === Constants::STATUS_CODE_SUCCESS && $oldAvatarResponse['payload']->avatar !== 'assets/common/img/avatar/default.png' && file_exists($oldAvatarResponse['payload']->avatar)) {
                        unlink($oldAvatarResponse['payload']->avatar);
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

                $result = $oldAvatarResponse['payload'];

                $updateResponse = $result->update([
                    'avatar' => $pathName.$fileName
                ]);

                if ($updateResponse) {
                    return [
                        'message' => 'Avatar is successfully saved',
                        'payload' => [
                            'file' => $pathName.$fileName
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return [
                        'message' => 'Something went wrong',
                        'payload' => null,
                        'status' => Constants::STATUS_CODE_ERROR
                    ];
                }
            } else {
                return [
                    'message' => 'File could not be saved',
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
     * Process the delete avatar request
     *
     * @param string $file
     * @return array
     */
    public function processDeleteAvatarRequest(string $file)
    {
        try {
            if (!file_exists($file)) {
                return [
                    'message' => 'The file can\'t be found',
                    'payload' => $file,
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }

            if (unlink($file)) {
                $defaultAvatar = 'assets/common/img/avatar/default.png';
                $result = $this->getAllFields();

                if ($result['status'] !== Constants::STATUS_CODE_SUCCESS) {
                    return $result;
                } else {
                    $result = $result['payload'];
                }

                $updateResponse = $result->update([
                    'avatar' => $defaultAvatar
                ]);

                if ($updateResponse) {
                    return [
                        'message' => 'File is deleted successfully',
                        'payload' => [
                            'file' => $defaultAvatar
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return [
                        'message' => 'Something went wrong',
                        'payload' => null,
                        'status' => Constants::STATUS_CODE_ERROR
                    ];
                }
            } else {
                return [
                    'message' => 'File could not be deleted',
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
     * Process the update cover request
     *
     * @param array $data
     * @return array
     */
    public function processUpdateCoverRequest(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'file' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $file = $data['file'];
            $extension = $file->extension() ? $file->extension() : 'png';
            $fileName = Str::random(10). '_'. time() .'.'. $extension;
            $pathName = 'assets/common/img/cover/';
            
            if (!file_exists($pathName)) {
                mkdir($pathName, 0777, true);
            }

            if ($file->move($pathName, $fileName)) {
                //delete previous cover
                $oldCoverResponse = $this->getAllFields(['cover', 'id']);
                try {
                    if ($oldCoverResponse['status'] === Constants::STATUS_CODE_SUCCESS && $oldCoverResponse['payload']->cover !== 'assets/common/img/cover/default.png' && file_exists($oldCoverResponse['payload']->cover)) {
                        unlink($oldCoverResponse['payload']->cover);
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

                $result = $oldCoverResponse['payload'];

                $updateResponse = $result->update([
                    'cover' => $pathName.$fileName
                ]);

                if ($updateResponse) {
                    return [
                        'message' => 'cover is successfully saved',
                        'payload' => [
                            'file' => $pathName.$fileName
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return [
                        'message' => 'Something went wrong',
                        'payload' => null,
                        'status' => Constants::STATUS_CODE_ERROR
                    ];
                }
            } else {
                return [
                    'message' => 'File could not be saved',
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
     * Process the delete cover request
     *
     * @param string $file
     * @return array
     */
    public function processDeleteCoverRequest(string $file)
    {
        try {
            if (!file_exists($file)) {
                return [
                    'message' => 'The file can\'t be found',
                    'payload' => $file,
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }

            if (unlink($file)) {
                $defaultCover = 'assets/common/img/cover/default.png';
                $result = $this->getAllFields();

                if ($result['status'] !== Constants::STATUS_CODE_SUCCESS) {
                    return $result;
                } else {
                    $result = $result['payload'];
                }

                $updateResponse = $result->update([
                    'cover' => $defaultCover
                ]);

                if ($updateResponse) {
                    return [
                        'message' => 'File is deleted successfully',
                        'payload' => [
                            'file' => $defaultCover
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return [
                        'message' => 'Something went wrong',
                        'payload' => null,
                        'status' => Constants::STATUS_CODE_ERROR
                    ];
                }
            } else {
                return [
                    'message' => 'File could not be deleted',
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
     * Process the update CV request
     *
     * @param array $data
     * @return array
     */
    public function processUpdateCVRequest(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'file' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ];
            }
          
            $file = $data['file'];
            $extension = $file->extension() ? $file->extension() : 'pdf';
            $fileName = Str::random(10). '_'. time() .'.'. $extension;
            $pathName = 'assets/common/cv/';
            
            if (!file_exists($pathName)) {
                mkdir($pathName, 0777, true);
            }

            if ($file->move($pathName, $fileName)) {
                //delete previous cv
                $oldCVResponse = $this->getAllFields(['cv', 'id']);
                try {
                    if ($oldCVResponse['status'] === Constants::STATUS_CODE_SUCCESS && $oldCVResponse['payload']->cv !== 'assets/common/cv/default.pdf' && file_exists($oldCVResponse['payload']->cv)) {
                        unlink($oldCVResponse['payload']->cv);
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

                $result = $oldCVResponse['payload'];

                $updateResponse = $result->update([
                    'cv' => $pathName.$fileName
                ]);
                
                if ($updateResponse) {
                    return [
                        'message' => 'CV is successfully saved',
                        'payload' => [
                            'file' => $pathName.$fileName
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return [
                        'message' => 'Something went wrong',
                        'payload' => null,
                        'status' => Constants::STATUS_CODE_ERROR
                    ];
                }
            } else {
                return [
                    'message' => 'File could not be saved',
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
     * Process the delete CV request
     *
     * @param string $file
     * @return array
     */
    public function processDeleteCVRequest(string $file)
    {
        try {
            if (!file_exists($file)) {
                return [
                    'message' => 'The file can\'t be found',
                    'payload' => $file,
                    'status' => Constants::STATUS_CODE_NOT_FOUND
                ];
            }

            if (unlink($file)) {
                $result = $this->getAllFields();
                if ($result['status'] !== Constants::STATUS_CODE_SUCCESS) {
                    return $result;
                } else {
                    $result = $result['payload'];
                }

                $updateResponse = $result->update([
                    'cv' => null
                ]);

                if ($updateResponse) {
                    return [
                        'message' => 'File is deleted successfully',
                        'payload' => [
                            'file' => null
                        ],
                        'status' => Constants::STATUS_CODE_SUCCESS
                    ];
                } else {
                    return [
                        'message'  => 'Something went wrong',
                        'payload' => null,
                        'status'  => Constants::STATUS_CODE_ERROR
                    ];
                }
            } else {
                return [
                    'message'  => 'File could not be deleted',
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
