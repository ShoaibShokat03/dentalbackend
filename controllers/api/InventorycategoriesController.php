<?php

namespace app\controllers\api;

use app\components\Helper;
use app\models\InventoryCategories;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use app\models\User;
use yii\filters\Cors;

class InventorycategoriesController extends Controller
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

            $categories = new InventoryCategories();
            $categories->name = $request['name'];
            $categories->description = $request['description'];
            $categories->active = intval($request['active']) == 1 ? 1 : 0; // Convert to integer (0 or 1)

            if ($categories->save()) {
                return [
                    'success' => true,
                    'message' => 'Category registered successfully',
                    'category_id' => $categories->id,
                    'category' => $categories
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $categories->getErrors()
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

            $categories = InventoryCategories::findOne($id);
            $categories->name = $request['name'];
            $categories->description = $request['description'];
            $categories->active = intval($request['active']) == 1 ? 1 : 0; // Convert to integer (0 or 1)

            if ($categories->save()) {

                return [
                    'success' => true,
                    'message' => 'Category updated successfully',
                    'category_id' => $categories->id,
                    'category' => $categories
                ];
            }
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $categories->getErrors()
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

            $itemToDelete = InventoryCategories::findOne($request['id']);
            if ($user == $itemToDelete) {
                return [
                    'success' => false,
                    'message' => 'You cannot delete yourself!',
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
        $query = InventoryCategories::find()
            ->select([
                'inventory_categories.*',
                '(SELECT COUNT(*) FROM inventory WHERE inventory.category_id = inventory_categories.id) as inventory_count'
            ])
            ->from('inventory_categories');

        // Filter by name (username or email)
        // $name = $request->get('name', '');
        // if (!empty($name)) {
        //     $query->andFilterWhere([
        //         'or',
        //         ['like', 'name', $name],
        //         ['like', 'description', $name]
        //     ]);
        // }

        // Filter by email specifically
        $name = $request->get('name', '');
        if (!empty($name)) {
            $query->andFilterWhere(['like', 'inventory_categories.name', $name]);
        }

        // Filter by verified status
        $active = $request->get('active', '');
        if ($active !== '') {
            $query->andFilterWhere(['inventory_categories.active' => $active == 'true' ? 1 : 0]);
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
            'message' => 'Data fetched successfully',
            'data' => $users,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'perpage' => $perpage
            ]
        ];
    }
}
