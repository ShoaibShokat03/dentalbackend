<?php

namespace app\controllers\api;

use app\components\Helper;
use app\models\Inventory;
use app\models\Invoices;
use app\models\Patients;
use app\models\Prescriptions;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use app\models\User;
use yii\filters\Cors;

class DashboardController extends Controller
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


    public function actionStats()
    {
        $token = Yii::$app->request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $token);
        $user = User::findOne(['access_token' => $token]);
        if (!$user) {
            throw new UnauthorizedHttpException('Invalid token');
        }

        $usersCount = User::find()->count();
        $doctorsCount = User::find()->where(['role' => 'doctor'])->count();
        $patientsCount = User::find()->where(['role' => 'patient'])->count();
        $patientsCount = Patients::find()->count();
        $inventoryCount = Inventory::find()->count();
        $invoicesCount = Invoices::find()->count();
        $priscriptionsCount = Prescriptions::find()->count();
        // $appointmentsCount = Appointments::find()->count();

        return [
            'success' => true,
            'message' => 'Stats fetched successfully',
            'data' => [
                'users_count' => $usersCount,
                'doctors_count' => $doctorsCount,
                'patients_count' => $patientsCount,
                'inventory_count' => $inventoryCount,
                'priscriptions_count' => $priscriptionsCount,
                'invoices_count' => $invoicesCount,
                // 'appointments_count' => $appointmentsCount,
            ]
        ];
    }
}
