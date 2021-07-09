<?php

namespace sindll\cas;

use Yii;
use phpCAS;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        if (Yii::$app->session) {
            session_name(Yii::$app->session->getName());
        }

        $cas = Yii::$app->params['cas'];
        $this->setup($cas);

        if (Yii::$app->user->getIsGuest()) {
            if (phpCAS::isAuthenticated()) {
                $user = phpCAS::getUser();

                $class = Yii::$app->user->identityClass;
                $identity = $class::findIdentity($user);

                $duration = 0;
                if (isset($cas['duration'])) {
                    $duration = $cas['duration'];
                }
                Yii::$app->user->login($identity, $duration);
            }
        }
    }

    private function setup($cas)
    {
        if (isset($cas['log'])) {
            $logger = new Logger('cas');
            $logger->pushHandler(new StreamHandler(Yii::getAlias($cas['log'])));

            phpCAS::setLogger($logger);
        }

        phpCAS::client(CAS_VERSION_2_0, $cas['host'], $cas['port'], $cas['uri']);
        phpCAS::setNoCasServerValidation();

        phpCAS::handleLogoutRequests();
    }
}
