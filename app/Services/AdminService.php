<?php

namespace App\Services;

use App;
use CoreConstants;
use App\Models\Admin;
use App\Models\AdminPasswordResets;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Visitor;
use App\Notifications\AdminResetPasswordNotification;
use App\Services\Contracts\AdminInterface;
use App\Services\Contracts\PortfolioConfigInterface;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Log;
use Str;
use Validator;

class AdminService implements AdminInterface
{
    /**
     * Eloquent instance
     *
     * @var Admin
     */
    private $model;
    
    /**
     * Instantiate a new instance.
     *
     * @param Admin $admin
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \App\Admin|null
     */
    private function guard()
    {
        return Auth::guard('admins');
    }

    /**
     * Get the corresponding model
     *
     * @return Admin
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Handle login
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleLogin(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $credentials['email'] = $data['email'];
            $credentials['password'] = $data['password'];

            if ($token = $this->guard()->attempt($credentials)) {
                $tokenDetails = [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $this->guard()->factory()->getTTL() * 60
                ];

                return [
                    'message' => 'Successfully logged in',
                    'payload' => [
                        'admin' => $this->guard()->user(),
                        'token' => $tokenDetails
                    ],
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Incorrect credentials',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_UNAUTHORIZED
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong.',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Handle signup
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleSignup(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'email' => 'required|max:255|email|unique:admins',
                'password' => 'required|min:5|confirmed',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $newData['email'] = $data['email'];
            $newData['password'] = Hash::make($data['password']);

            $result = $this->createAdmin($newData);

            if ($result['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
                return $result;
            } else {
                $admin = $result['payload'];
            }

            $token = $this->guard()->login($admin);

            $tokenDetails = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ];
            
            return [
                'message' => 'Signup is successful',
                'payload' => [
                    'admin' => $this->guard()->user(),
                    'token' => $tokenDetails
                ],
                'status' => CoreConstants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong.',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Store a new admin to database
     *
     * @param array $data
     * @return array
     */
    private function createAdmin(array $data)
    {
        try {
            $admin = $this->model->create($data);
            return [
                'message' => 'Successfully created.',
                'payload' => $admin,
                'status' => CoreConstants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong.',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Handle forget password
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleForgetPassword(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'email' => 'required|max:255|email',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $result = $this->getByEmail($data['email']);

            if ($result['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
                return $result;
            } else {
                $admin = $result['payload'];
            }

            return $this->sendPasswordResetEmail($admin);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong.',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Fetch data by email
     *
     * @param string $email
     * @param array $select
     * @return array
     */
    public function getByEmail(string $email, array $select = ['*'])
    {
        try {
            $data = $this->model->select($select)->where('email', $email)->first();
            
            if ($data) {
                return [
                    'message' => 'Admin found.',
                    'payload' => $data,
                    'status' => CoreConstants::STATUS_CODE_SUCCESS
                ];
            } else {
                return [
                    'message' => 'Found no admin with that email address.',
                    'payload' => null,
                    'status' => CoreConstants::STATUS_CODE_NOT_FOUND
                ];
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong.',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Send password reset mail by creating reset token
     *
     * @param Admin $admin
     * @return array
     */
    private function sendPasswordResetEmail(Admin $admin)
    {
        $result = $this->createPasswordResetToken($admin->email);

        if ($result['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
            return $result;
        } else {
            $token = $result['payload'];
        }

        $admin->notify(new AdminResetPasswordNotification($token));

        return [
            'message' => 'We have emailed your password reset link!',
            'payload' => null,
            'status' => CoreConstants::STATUS_CODE_SUCCESS
        ];
    }

    /**
     * Return password reset token
     *
     * @param string $email
     * @return array
     */
    private function createPasswordResetToken(string $email)
    {
        //delete existing tokens
        $this->deletePasswordResetToken($email);

        $token = Str::random(80);
        return $this->savePasswordResetToken($token, $email);
    }

    /**
     * delete password reset token
     *
     * @param string $token
     * @param string $email
     * @return array
     */
    private function deletePasswordResetToken(string $email)
    {
        AdminPasswordResets::where('email', $email)->delete();

        return [
            'message' => 'Token is deleted successfully.',
            'payload' => null,
            'status' => CoreConstants::STATUS_CODE_SUCCESS
        ];
    }

    /**
     * Store password reset token
     *
     * @param string $token
     * @param string $email
     * @return array
     */
    private function savePasswordResetToken(string $token, string $email)
    {
        $entry = AdminPasswordResets::insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        return [
            'message' => 'Successfully created.',
            'payload' => $token,
            'status' => CoreConstants::STATUS_CODE_SUCCESS
        ];
    }

    /**
     * Handle reset password
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function handleResetPassword(array $data)
    {
        try {
            $validate = Validator::make($data, [
                'email' => 'required|max:255|email',
                'token' => 'required',
                'password' => 'required|min:5|confirmed',
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $result = $this->getByEmail($data['email']);

            if ($result['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
                return $result;
            } else {
                $admin = $result['payload'];
            }

            $result = $this->validatePasswordResetToken($data['token'], $data['email']);

            if ($result['status'] !== CoreConstants::STATUS_CODE_SUCCESS) {
                return $result;
            }
            
            //delete token
            $this->deletePasswordResetToken($data['email']);

            //reset password
            return $this->resetPassword($admin, $data['password']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [
                'message' => 'Something went wrong.',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Reset password for the admin
     *
     * @param Admin $admin
     * @param string $password
     * @return array
     */
    private function resetPassword(Admin $admin, string $password)
    {
        $admin->password = Hash::make($password);
        $admin->save();

        return [
            'message' => 'Your password has been reset!',
            'payload' => $admin,
            'status' => CoreConstants::STATUS_CODE_SUCCESS
        ];
    }
    
    /**
     * Validate password reset token
     *
     * @param string $token
     * @param string $email
     * @return array
     */
    private function validatePasswordResetToken(string $token, string $email)
    {
        $data = AdminPasswordResets::where('token', $token)
                ->where('email', $email)
                ->where('created_at', '>', Carbon::now()->subHours(1))
                ->first();

        if ($data) {
            return [
                'message' => 'Token found.',
                'payload' => $data,
                'status' => CoreConstants::STATUS_CODE_SUCCESS
            ];
        } else {
            return [
                'message' => 'Invalid password reset token. Please try again',
                'payload' => null,
                'status' => CoreConstants::STATUS_CODE_NOT_FOUND
            ];
        }
    }

    /**
     * Get the authenticated admin
     *
     * @return JsonResponse
     */
    public function me()
    {
        $me = $this->guard()->user();

        return [
            'message' => 'Admin found',
            'payload' => $me,
            'status' => CoreConstants::STATUS_CODE_SUCCESS
        ];
    }

    /**
     * Refresh a token
     *
     * @return JsonResponse
     */
    public function refreshToken()
    {
        try {
            $token = $this->guard()->refresh();

            $tokenDetails = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ];

            return [
                'message' => 'Token refreshed',
                'payload' => $tokenDetails,
                'status' => CoreConstants::STATUS_CODE_SUCCESS
            ];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            if ($th instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return [
                    'message' => 'Token is Blacklisted',
                    'payload' => CoreConstants::TOKEN_BLACKLISTED,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return [
                    'message' => 'Token is Expired',
                    'payload' => CoreConstants::TOKEN_EXPIRED,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return [
                    'message' => 'Token is Invalid',
                    'payload' => CoreConstants::TOKEN_INVALID,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ];
            }

            return [
                'message' => 'Something went wrong.',
                'payload' => $th->getMessage(),
                'status' => CoreConstants::STATUS_CODE_ERROR
            ];
        }
    }

    /**
     * Change login credentials
     *
     * @param array $data
     * @return array
     */
    public function changeCredential(array $data)
    {
        
        try {
            $validate = Validator::make($data, [
                'id' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validate->fails()) {
                return [
                    'message' => 'Bad Request',
                    'payload' => $validate->errors(),
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ];
            }

            $newData['email'] = $data['email'];
            $newData['password'] = Hash::make($data['password']);

            $admin = $this->model
                    ->where('id', $this->guard()->id())
                    ->first();

            $admin->update([
                'email' => $newData['email'],
                'password' => $newData['password'],
            ]);

            return [
                'message' => 'Credential is successfully updated',
                'payload' => $admin,
                'status' => CoreConstants::STATUS_CODE_SUCCESS
            ];
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
     * Fetch admin information by admin id
     *
     * @param int $adminId
     * @param array $select
     * @return array
     */
    public function getAdminById(int $adminId, array $select = ['*'])
    {
        try {
            $admin = $this->model->select($select)->where('id', $adminId)->first();
            
            if ($admin) {
                return [
                    'message' => 'Data is fetched successfully',
                    'payload' => $admin,
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
     * Get stats
     *
     * @param string $todayStartDate
     * @param string $todayEndDate
     * @param string $thisWeekStartDate
     * @param string $thisWeekEndDate
     * @param string $thisMonthStartDate
     * @param string $thisMonthEndDate
     * @param boolean $visitorData
     * @param boolean $messageData
     * @param boolean $skillData
     * @param boolean $educationData
     * @param boolean $experienceData
     * @param boolean $projectData
     * @param boolean $currentTemplate
     * @return array
     */
    public function getStats(
        string $todayStartDate = null,
        string $todayEndDate = null,
        string $thisWeekStartDate = null,
        string $thisWeekEndDate = null,
        string $thisMonthStartDate = null,
        string $thisMonthEndDate = null,
        bool $visitorData = true,
        bool $messageData = true,
        bool $skillData = true,
        bool $educationData = true,
        bool $experienceData = true,
        bool $projectData = true,
        bool $serviceData = true,
        bool $currentTemplate = true
    ) {
        try {
            if ($todayStartDate) {
                $todayStartDate = Carbon::parse($todayStartDate)->format('Y-m-d H:i:s');
            }

            if ($todayEndDate) {
                $todayEndDate = Carbon::parse($todayEndDate)->format('Y-m-d H:i:s');
            }

            if ($thisWeekStartDate) {
                $thisWeekStartDate = Carbon::parse($thisWeekStartDate)->format('Y-m-d H:i:s');
            }

            if ($thisWeekEndDate) {
                $thisWeekEndDate = Carbon::parse($thisWeekEndDate)->format('Y-m-d H:i:s');
            }

            if ($thisMonthStartDate) {
                $thisMonthStartDate = Carbon::parse($thisMonthStartDate)->format('Y-m-d H:i:s');
            }
            
            if ($thisMonthEndDate) {
                $thisMonthEndDate = Carbon::parse($thisMonthEndDate)->format('Y-m-d H:i:s');
            }

            $data = [];
            
            if ($visitorData) {
                $visitorModel = resolve(Visitor::class);

                $data['visitors']['total'] = $visitorModel->count();

                if ($todayStartDate && $todayEndDate) {
                    $data['visitors']['totalToday'] = $visitorModel
                                                            ->where('created_at', '>=', $todayStartDate)
                                                            ->where('created_at', '<=', $todayEndDate)
                                                            ->count();
                }

                if ($thisWeekStartDate && $thisWeekEndDate) {
                    $data['visitors']['totalThisWeek'] = $visitorModel
                                                            ->where('created_at', '>=', $thisWeekStartDate)
                                                            ->where('created_at', '<=', $thisWeekEndDate)
                                                            ->count();
                }

                if ($thisMonthStartDate && $thisMonthEndDate) {
                    $data['visitors']['totalThisMonth'] = $visitorModel
                                                            ->where('created_at', '>=', $thisMonthStartDate)
                                                            ->where('created_at', '<=', $thisMonthEndDate)
                                                            ->count();
                }

                $lastThirtyDaysVisitors = $visitorModel
                                            ->where('created_at', '>=', Carbon::now()->subMonth())
                                            ->select(
                                                DB::raw('Date(created_at) as date'),
                                                DB::raw('COUNT(*) as "count"')
                                            )
                                            ->groupBy('date')
                                            ->get();
                                            
                if (!empty($lastThirtyDaysVisitors)) {
                    $data['visitors']['trend'] = $lastThirtyDaysVisitors;
                } else {
                    $data['visitors']['trend'] = [];
                }
            }

            if ($messageData) {
                $messageModel = resolve(Message::class);

                $data['message']['total'] = $messageModel->count();

                if ($todayStartDate && $todayEndDate) {
                    $data['message']['totalToday'] = $messageModel
                                                            ->where('created_at', '>=', $todayStartDate)
                                                            ->where('created_at', '<=', $todayEndDate)
                                                            ->count();
                }

                if ($thisWeekStartDate && $thisWeekEndDate) {
                    $data['message']['totalThisWeek'] = $messageModel
                                                            ->where('created_at', '>=', $thisWeekStartDate)
                                                            ->where('created_at', '<=', $thisWeekEndDate)
                                                            ->count();
                }

                if ($thisMonthStartDate && $thisMonthEndDate) {
                    $data['message']['totalThisMonth'] = $messageModel
                                                            ->where('created_at', '>=', $thisMonthStartDate)
                                                            ->where('created_at', '<=', $thisMonthEndDate)
                                                            ->count();
                }
            }

            if ($skillData) {
                $skillModel = resolve(Skill::class);

                $data['skills']['total'] = $skillModel->count();
            }

            if ($educationData) {
                $educationModel = resolve(Education::class);

                $data['educations']['total'] = $educationModel->count();
            }

            if ($experienceData) {
                $experienceModel = resolve(Experience::class);

                $data['experiences']['total'] = $experienceModel->count();
            }

            if ($projectData) {
                $projectModel = resolve(Project::class);

                $data['projects']['total'] = $projectModel->count();
            }

            if ($serviceData) {
                $serviceModel = resolve(Service::class);

                $data['services']['total'] = $serviceModel->count();
            }

            if ($currentTemplate) {
                $templateResponse = resolve(PortfolioConfigInterface::class)->getConfigByKey(CoreConstants::PORTFOLIO_CONFIG__TEMPLATE, ['setting_value']);

                if ($templateResponse['status'] === CoreConstants::STATUS_CODE_SUCCESS) {
                    $data['currentTemplate'] = $templateResponse['payload']->setting_value;
                }
            }

            return [
                'message' => 'Stats are fetched Successfully',
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
}
