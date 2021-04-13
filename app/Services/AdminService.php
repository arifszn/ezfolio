<?php

namespace App\Services;

use App;
use CoreConstants;
use App\Models\Admin;
use App\Models\AdminPasswordResets;
use App\Notifications\AdminResetPasswordNotification;
use App\Services\Contracts\AdminInterface;
use Auth;
use Carbon\Carbon;
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
}
