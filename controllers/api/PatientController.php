<?php

namespace app\controllers\api;

use app\components\Helper;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use app\models\Patients;
use app\models\User;
use yii\filters\Cors;

class PatientController extends Controller
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


    public function actionCreate()
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

            $patientUser = User::findOne(['email' => $request['email']]);
            if (!$patientUser) {
                // Create User First
                $patientUser = new User();
                $patientUser->username = $request['email'];
                $patientUser->email = $request['email'];
                $patientUser->role = 'patient';
                $patientUser->password_hash = Yii::$app->security->generatePasswordHash("12345678");
                if (!$patientUser->save()) {
                    return [
                        'success' => false,
                        'message' => 'Patient not registered successfully',
                        'errors' => $patientUser->getErrors()
                    ];
                }
            }

            $patient = new Patients();
            $patient->user_id = $patientUser->id;
            $patient->full_name = $request['full_name'];
            $patient->email = $request['email'];
            $patient->father_name = $request['father_name'];
            $patient->contact_number = $request['contact_number'];
            $patient->gender = $request['gender'];
            $patient->age = $request['age'];
            $patient->address = $request['address'];
            $patient->medical_history = $request['medical_history'];
            $patient->allergies = $request['allergies'];
            $patient->created_by = $user->id;
            $patient->updated_by = $user->id;

            if ($patient->save()) {
                return [
                    'success' => true,
                    'message' => 'Patient registered successfully',
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $patient->getErrors()
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

            $patient = Patients::findOne($id);
            $patient->full_name = $request['full_name'];
            $patient->email = $request['email'];
            $patient->father_name = $request['father_name'];
            $patient->contact_number = $request['contact_number'];
            $patient->gender = $request['gender'];
            $patient->age = $request['age'];
            $patient->address = $request['address'];
            $patient->medical_history = $request['medical_history'];
            $patient->created_by = $user->id;
            $patient->updated_by = $user->id;

            // $user->auth_key = Yii::$app->security->generateRandomString();
            // $user->access_token = Yii::$app->security->generateRandomString(64); // Temporary token
            if ($patient->save()) {
                return [
                    'success' => true,
                    'message' => 'Patient updated successfully',
                    'user_id' => $patient->id
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $patient->getErrors()
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

            $userToDelete = Patients::findOne($request['id']);
            if ($user == $userToDelete) {
                return [
                    'success' => false,
                    'message' => 'You cannot delete yourself!',
                ];
            }

            $USER = User::findOne($userToDelete->user_id);
            $USER->delete();

            if ($userToDelete->delete()) {
                return [
                    'success' => true,
                    'message' => 'Patient deleted successfully',
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


    public function actionView($id)
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

        $patient = Patients::findOne($id);
        if (!$patient) {
            throw new UnauthorizedHttpException('Invalid token');
        }


        return [
            'success' => true,
            'message' => 'Patient profile fetched successfully',
            'data' => [
                'patient' => $patient,
                'appointments' => $patient->getAppointments()
            ]
        ];
    }

    public function actionPatientsList()
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
        $query = Patients::find()->select(['id', 'user_id', 'full_name', 'father_name', 'email', 'contact_number', 'gender', 'age', 'medical_history', 'address', 'allergies']);

        $full_name = $request->get('full_name', '');
        if (!empty($name)) {
            $query->andFilterWhere([
                'or',
                ['like', 'full_name', $full_name],
                ['like', 'email', $full_name]
            ]);
        }

        $father_name = $request->get('father_name', '');
        if (!empty($name)) {
            $query->andFilterWhere(['like', 'father_name', $father_name]);
        }

        $email = $request->get('email', '');
        if (!empty($email)) {
            $query->andFilterWhere(['like', 'email', $email]);
        }

        $gender = $request->get('gender', '');
        if (!empty($name)) {
            $query->andFilterWhere(['like', 'gender', $gender]);
        }

        $contact_number = $request->get('contact_number', '');
        if (!empty($name)) {
            $query->andFilterWhere(['like', 'contact_number', $contact_number]);
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
            'message' => 'Patients fetched successfully',
            'data' => $users,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'perpage' => $perpage
            ]
        ];
    }
}
