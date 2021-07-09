<?php

namespace sindll\cas\controllers;

use Yii;
use yii\web\Controller;
use phpCAS;

class PassportController extends Controller
{
    public function actionLogin($redirect = null)
    {
        if (phpCAS::checkAuthentication()) {
            return $this->goBack($redirect);
        }

        phpCAS::forceAuthentication();
    }

    public function actionLogout($redirect = null)
    {
        if (!$redirect) {
            $redirect = Url::toRoute(['/cas/passport/login'], true);
        }
        if (phpCAS::checkAuthentication()) {
            phpCAS::logout(['service' => $redirect]);
        } else {
            return $this->redirect($redirect);
        }
    }
}
