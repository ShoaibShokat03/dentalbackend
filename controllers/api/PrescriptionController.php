<?php

namespace app\controllers\api;

use app\components\Helper;
use app\models\Prescriptions;
use app\models\Patients;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use app\models\User;
use app\models\PrescriptionItems;
use yii\filters\Cors;

class PrescriptionController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

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

    private function generateMRN()
    {
        $year = date('Y');
        $prefix = 'MRN';
        
        // Get the last prescription for this year
        $lastPrescription = Prescriptions::find()
            ->where(['like', 'mrn_number', $prefix . $year])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($lastPrescription) {
            // Extract the number part and increment
            $lastNumber = (int)substr($lastPrescription->mrn_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First prescription of the year
            $newNumber = 1;
        }

        // Format: MRN-YYYY-XXXX (where XXXX is a 4-digit number)
        return $prefix . '-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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

            $prescription = new Prescriptions();
            $prescription->patient_id = $request['patient_id'];
            $prescription->mrn_number = $this->generateMRN(); // Auto-generate MRN
            $prescription->doctor_id = $user->id;
            $prescription->prescription_date = $request['prescription_date'] ?? date('Y-m-d');
            $prescription->diagnosis = $request['diagnosis'] ?? null;
            $prescription->notes = $request['notes'] ?? null;

            $prescriptionItems = $request['medicines'] ?? [];

            if ($prescription->save()) {
                foreach ($prescriptionItems as $item) {
                    $prescriptionItem = new PrescriptionItems();
                    $prescriptionItem->prescription_id = $prescription->id;
                    $prescriptionItem->medicine_name = $item['medicineName'];
                    $prescriptionItem->dosage = $item['dosage'] ?? null;
                    $prescriptionItem->frequency = $item['frequency'] ?? null;
                    $prescriptionItem->duration = $item['days'] ?? null;
                    $prescriptionItem->instructions = $item['description'] ?? null;
                    $prescriptionItem->save();
                }
                return [
                    'success' => true,
                    'message' => 'Prescription created successfully',
                    'prescription' => $prescription,
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $prescription->getErrors(),
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

            $prescription = Prescriptions::findOne($id);
            if (!$prescription) {
                return [
                    'success' => false,
                    'message' => 'Prescription not found',
                ];
            }
            $prescription->prescription_date = $request['prescription_date'] ?? $prescription->prescription_date;
            $prescription->diagnosis = $request['diagnosis'] ?? $prescription->diagnosis;
            $prescription->notes = $request['notes'] ?? $prescription->notes;

            if ($prescription->save()) {
                return [
                    'success' => true,
                    'message' => 'Prescription updated successfully',
                    'prescription' => $prescription,
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $prescription->getErrors(),
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

            $prescription = Prescriptions::findOne($request['id']);
            if (!$prescription) {
                return [
                    'success' => false,
                    'message' => 'Prescription not found',
                ];
            }
            if ($prescription->delete()) {
                return [
                    'success' => true,
                    'message' => 'Prescription deleted successfully',
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $prescription->getErrors(),
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    }

    public function actionList()
    {
        if (!Yii::$app->request->isGet) {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    
        $token = Yii::$app->request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $token);
    
        $user = User::findOne(['access_token' => $token]);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid token',
            ];
        }
    
        $request = Yii::$app->request;
        $page = (int)$request->get('page', 1);
        $perpage = (int)$request->get('perpage', 5);
        $sort = $request->get('sort', 'created_at');
        $order = strtolower($request->get('order', 'desc')) === 'desc' ? SORT_DESC : SORT_ASC;
    
        $query = Prescriptions::find()->with(['patient', 'doctor']);
    
        $total = $query->count();
    
        $data = $query
            ->orderBy([$sort => $order])
            ->offset(($page - 1) * $perpage)
            ->limit($perpage)
            ->asArray()
            ->all();
    
        $patients = User::find()->where(['role' => 'patient'])->asArray()->all();
    
        return [
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => $data,
            'patients' => $patients,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'perpage' => $perpage
            ]
        ];
    }
    public function actionView($id)
    {
        if (!Yii::$app->request->isGet) {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    
        $token = Yii::$app->request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $token);
    
        $user = User::findOne(['access_token' => $token]);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid token',
            ];
        }

        $prescription = Prescriptions::findOne($id);
        if (!$prescription) {
            return [
                'success' => false,
                'message' => 'Prescription not found',
            ];
        }
        return [
            'success' => true,
            'message' => 'Prescription fetched successfully',
            'prescription' => $prescription,
            'doctor' => $prescription->doctor,
            'patient' => $prescription->patient,
            'prescriptionItems' => $prescription->prescriptionItems,
        ];
    }
}
