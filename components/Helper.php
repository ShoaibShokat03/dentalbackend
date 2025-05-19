<?php


namespace app\components;

use Yii;

class Helper
{
    public static function checkLogin()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect('login');
        }
    }

    public static function checkAdmin()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == 'admin' || Yii::$app->user->identity->role == 'moderator') {
                return Yii::$app->response->redirect('/admin/dashboard');
            }
        }
    }

    public static function ApiAllowedOrigins()
    {
        return [
            'http://localhost',
            'http://localhost:5173',
            'http://localhost:5173/',
            'http://localhost:5174',
            'http://localhost:5174/',
            'https://leightonbuzzardairportcabs.co.uk/dental/',
            'https://leightonbuzzardairportcabs.co.uk/dental',
            'https://leightonbuzzardairportcabs.co.uk',
            'https://leightonbuzzardairportcabs.co.uk/',
        ];
    }
}
