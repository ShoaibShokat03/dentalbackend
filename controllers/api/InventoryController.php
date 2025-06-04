<?php

namespace app\controllers\api;

use app\components\Helper;
use app\models\Inventory;
use app\models\InventoryCategories;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use app\models\User;
use yii\filters\Cors;

class InventoryController extends Controller
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

            $inventory = new Inventory();
            $inventory->category_id = $request['category_id'];
            $inventory->name = $request['name'];
            $inventory->description = $request['description'];
            $inventory->code  = $request['code'];
            $inventory->quantity  = $request['quantity'];
            $inventory->cost_price  = $request['cost_price'];
            $inventory->selling_price  = $request['selling_price'];
            $inventory->expiry_date  = $request['expiry_date'];
            $inventory->active = intval($request['active']) == 1 ? 1 : 0; // Convert to integer (0 or 1)

            if ($inventory->save()) {
                return [
                    'success' => true,
                    'message' => 'Inventory added successfully',
                    'category_id' => $inventory->id,
                    'category' => $inventory
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $inventory->getErrors()
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

            $inventory = Inventory::findOne($id);
            $inventory->category_id = $request['category_id'];
            $inventory->name = $request['name'];
            $inventory->description = $request['description'];
            $inventory->code  = $request['code'];
            $inventory->quantity  = $request['quantity'];
            $inventory->cost_price  = $request['cost_price'];
            $inventory->selling_price  = $request['selling_price'];
            $inventory->expiry_date  = $request['expiry_date'];
            $inventory->active = intval($request['active']) == 1 ? 1 : 0; // Convert to integer (0 or 1)

            if ($inventory->save()) {

                return [
                    'success' => true,
                    'message' => 'Inventory updated successfully',
                    'category_id' => $inventory->id,
                    'category' => $inventory
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $inventory->getErrors()
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

            $itemToDelete = Inventory::findOne($request['id']);

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
        $query = Inventory::find()
            ->select(['inventory.*', 'inventory_categories.name as category_name'])
            ->leftJoin('inventory_categories', 'inventory.category_id = inventory_categories.id');

        $category_id = $request->get('category_id', '');
        if (!empty($category_id)) {
            $query->andFilterWhere(['like', 'inventory.category_id', $category_id]);
        }

        $name = $request->get('name', '');
        if (!empty($name)) {
            $query->andFilterWhere(['like', 'inventory.name', $name]);
        }

        $code = $request->get('code', '');
        if ($code !== '') {
            $query->andFilterWhere(['inventory.code' => $code]);
        }

        $quantity = $request->get('quantity', '');
        if ($quantity !== '') {
            $query->andFilterWhere(['inventory.quantity' => $quantity]);
        }

        // Filter by verified status
        $active = $request->get('active', '');
        if ($active !== '') {
            $query->andFilterWhere(['inventory.active' => $active]);
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
