<?php

namespace app\controllers\api;

use app\components\Helper;
use app\models\Doctor;
use app\models\PaymentShares;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use app\models\User;
use yii\filters\Cors;

class UserController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // CORS filter must be added *before* contentNegotiator
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => Helper::ApiAllowedOrigins(),
                'Access-Control-Allow-Methods' => ['POST', 'GET', 'OPTIONS'],
                'Access-Control-Allow-Headers' => ['Content-Type', 'Authorization'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
            ],
        ];

        // This must be AFTER corsFilter
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        return $behaviors;
    }
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }


    public function actionRegister()
    {
        if (Yii::$app->request->isPost) {
            $request = json_decode(Yii::$app->request->rawBody, true);

            $token = Yii::$app->request->headers->get('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $user = User::findOne(['access_token' => $token]);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Invalid token',
                ];
            }

            $user = new User();
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->role = $request['role'];
            $user->verified = 1;
            $user->password_hash = Yii::$app->security->generatePasswordHash($request['password']);
            // $user->auth_key = Yii::$app->security->generateRandomString();
            // $user->access_token = Yii::$app->security->generateRandomString(64); // Temporary token

            $ROLE = "User";
            if ($user->save()) {

                if ($user->role === "doctor") {
                    $doctor = new Doctor();
                    $doctor->user_id = $user->id;
                    $doctor->gender = $request['gender'];
                    $doctor->date_of_birth = $request['date_of_birth'];
                    $doctor->blood_group = $request['blood_group'];
                    $doctor->phone = $request['phone'];
                    $doctor->address = $request['address'];
                    $doctor->specialization = $request['specialization'];
                    $doctor->qualification = $request['qualification'];
                    $doctor->experience = $request['experience'];
                    $doctor->commission_percentage = $request['commission_percentage'];
                    $doctor->save(false); // Skip validation for faster saving

                    // Doctor Share
                    $paymentShare = new PaymentShares();
                    $paymentShare->user_id = $user->id;
                    $paymentShare->percentage = $request['commission_percentage'];
                    $year = date('Y');
                    $month = date('m');
                    $date = date('d');
                    $paymentShare->year = $year;
                    $paymentShare->month = $month;
                    $paymentShare->date = $date;
                    $paymentShare->save(false); // Skip validation for faster saving
                }

                // select only columns from user object or unset other columns
                unset($user->password_hash);
                unset($user->auth_key);
                unset($user->access_token);
                unset($user->refresh_token);
                unset($user->token_expire_at);
                unset($user->created_at);
                unset($user->updated_at);
                $ROLE = "Doctor";

                return [
                    'success' => true,
                    'message' => $ROLE . ' registered successfully',
                    'user_id' => $user->id,
                    'user' => $user
                ];
            }

            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $user->getErrors()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    }

    public function actionEdit($id)
    {
        if (Yii::$app->request->isPost) {
            $request = json_decode(Yii::$app->request->rawBody, true);

            $token = Yii::$app->request->headers->get('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $user = User::findOne(['access_token' => $token]);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Invalid token',
                ];
            }

            $user = User::findOne($id);
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->role = $request['role'];
            if (!empty($request['password'])) {
                $user->password_hash = Yii::$app->security->generatePasswordHash($request['password']);
            }

            // $user->auth_key = Yii::$app->security->generateRandomString();
            // $user->access_token = Yii::$app->security->generateRandomString(64); // Temporary token
            if ($user->save()) {

                // select only columns from user object or unset other columns
                unset($user->password_hash);
                unset($user->auth_key);
                unset($user->access_token);
                unset($user->refresh_token);
                unset($user->token_expire_at);
                unset($user->created_at);
                unset($user->updated_at);

                return [
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user_id' => $user->id,
                    'user' => $user
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $user->getErrors()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            $request = json_decode(Yii::$app->request->rawBody, true);

            $token = Yii::$app->request->headers->get('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $user = User::findOne(['access_token' => $token]);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Invalid token',
                ];
            }

            $userToDelete = User::findOne($request['id']);
            if ($user == $userToDelete) {
                return [
                    'success' => false,
                    'message' => 'You cannot delete yourself!',
                ];
            }

            // $user->auth_key = Yii::$app->security->generateRandomString();
            // $user->access_token = Yii::$app->security->generateRandomString(64); // Temporary token
            if ($userToDelete->delete()) {
                return [
                    'success' => true,
                    'message' => 'User deleted successfully',
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $userToDelete->getErrors()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    }

    public function actionLogin()
    {
        if (Yii::$app->request->isPost) {
            $request = json_decode(Yii::$app->request->rawBody, true);

            $user = User::findOne(['email' => $request['email']]);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found, Invalid credentials',
                ];
            }
            if (!Yii::$app->security->validatePassword($request['password'], $user->password_hash)) {
                return [
                    'success' => false,
                    'message' => 'Password is incorrect',
                ];
            }

            $token = Yii::$app->security->generateRandomString(64);
            $refresh = Yii::$app->security->generateRandomString(128);
            $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $user->access_token = $token;
            $user->refresh_token = $refresh;
            $user->token_expire_at = $expire;
            $user->save(false);

            $user->password_hash = "";

            return [
                'success' => true,
                'message' => 'User logged in successfully',
                'data' => [
                    'user_id' => $user->id,
                    'access_token' => $token,
                    'refresh_token' => $refresh,
                    'expires_at' => $expire,
                    'user' => $user,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    }


    public function actionProfile()
    {
        $token = Yii::$app->request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $token);

        $user = User::findOne(['access_token' => $token]);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid token',
            ];
        }

        return [
            'success' => true,
            'message' => 'User profile fetched successfully',
            'id' => $user->id,
            'username' => $user->username,
        ];
    }

    public function actionUsersList()
    {
        $token = Yii::$app->request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $token);

        $user = User::findOne(['access_token' => $token]);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid token',
            ];
        }

        // Read query parameters from frontend
        $request = Yii::$app->request;
        $page = (int)$request->get('page', 1);
        $perpage = (int)$request->get('perpage', 5);
        $sort = $request->get('sort', 'created_at'); // e.g. username, email
        $order = strtolower($request->get('order', 'desc')) === 'desc' ? SORT_DESC : SORT_ASC;

        // Build query
        $query = User::find()->select(['id', 'username', 'email', 'role', 'verified']);

        // Filter by name (username or email)
        $name = $request->get('username', '');
        if (!empty($name)) {
            $query->andFilterWhere([
                'or',
                ['like', 'username', '%' . $name . '%', false],
                ['like', 'email', '%' . $name . '%', false]
            ]);
        }

        // Filter by email specifically
        $email = $request->get('email', '');
        if (!empty($email)) {
            $query->andFilterWhere(['like', 'email', '%' . $email . '%', false]);
        }

        // Filter by role
        $role = $request->get('role', '');
        if (!empty($role)) {
            $query->andFilterWhere(['like', 'role', '%' . $role . '%', false]);
        }

        // Filter by verified status
        $verified = $request->get('verified', '');
        if ($verified !== '') {
            $query->andFilterWhere(['verified' => $verified == 'true' ? 1 : 0]);
        }

        $total = $query->count();

        $users = $query
            ->orderBy([$sort => $order])
            ->offset(($page - 1) * $perpage)
            ->limit($perpage)
            ->asArray()
            ->all();

        return [
            'success' => true,
            'message' => 'Users fetched successfully',
            'data' => $users,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'perpage' => $perpage
            ]
        ];
    }
}
