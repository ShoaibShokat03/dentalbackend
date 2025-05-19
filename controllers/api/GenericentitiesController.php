<?php

namespace app\controllers\api;

use app\components\Helper;
use app\models\GenericEntities;
use app\models\GenericRecords;
use app\models\Inventory;
use app\models\InventoryCategories;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use app\models\User;
use yii\filters\Cors;

class GenericentitiesController extends Controller
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

            $ge = new GenericEntities();
            $ge->entity_name = $request['entity_name'];
            $ge->entity_type = $request['entity_type'];
            $ge->active = intval($request['active']) == 1 ? 1 : 0; // Convert to integer (0 or 1)

            if ($ge->save()) {
                return [
                    'success' => true,
                    'message' => 'Generic Entity added successfully',
                    'item_id' => $ge->id,
                    'item' => $ge
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $ge->getErrors()
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

            $ge = GenericEntities::findOne($id);
            $ge->entity_name = $request['entity_name'];
            $ge->entity_type = $request['entity_type'];
            $ge->active = intval($request['active']) == 1 ? 1 : 0; // Convert to integer (0 or 1)

            if ($ge->save()) {

                GenericRecords::updateAll(
                    ['entity_type' => $ge->entity_type],
                    ['entity_type' => $ge->entity_type]
                );

                return [
                    'success' => true,
                    'message' => 'ge updated successfully',
                    'item_id' => $ge->id,
                    'item' => $ge
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $ge->getErrors()
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


            $itemToDelete = GenericEntities::findOne($request['id']);
            $genericRecordsCount = GenericRecords::find()->where(['entity_type' => $itemToDelete->entity_type])->count();

            if ($genericRecordsCount > 0) {
                return [
                    'success' => false,
                    'message' => 'Cannot delete this entity because it has related records.',
                ];
            }

            if ($itemToDelete->delete()) {
                return [
                    'success' => true,
                    'message' => 'Item deleted successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $itemToDelete->getErrors()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Method is not correct!',
            ];
        }
    }

    public function actionView()
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

    public function actionList()
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
        $query = GenericEntities::find()
            ->select([
                'generic_entities.*',
                'items' => GenericRecords::find()
                    ->select('count(*)')
                    ->where(['entity_type' => new \yii\db\Expression('CONVERT(generic_entities.entity_type USING utf8mb4)')])
            ]);

        $entity_type = $request->get('entity_type', '');
        if (!empty($entity_type)) {
            $query->andFilterWhere(['like', 'generic_entities.entity_type', $entity_type]);
        }

        $name = $request->get('name', '');
        if (!empty($name)) {
            $query->andFilterWhere(['like', 'generic_entities.name', $name]);
        }


        // Filter by verified status
        $active = $request->get('active', '');
        if ($active !== '') {
            $query->andFilterWhere(['generic_entities.active' => $active]);
        }

        $total = $query->count();

        $data = $query
            ->orderBy([$sort => $order])
            ->offset(($page - 1) * $perpage)
            ->limit($perpage)
            ->asArray()
            ->all();

        $categories = InventoryCategories::find()->asArray()->all();

        return [
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => $data,
            'categories' => $categories,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'perpage' => $perpage
            ]
        ];
    }
}
