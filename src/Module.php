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

        $session = Yii::$app->session;
        if ($session) {
            session_name($session->getName());
            $session->open();
        }

        $cas = Yii::$app->params['cas'];
        $this->setup($cas);

        if (phpCAS::isAuthenticated()) {
            $user = phpCAS::getUser();

            $class = Yii::$app->user->identityClass;
            $identity = $class::findIdentity($user);

            Yii::$app->user->setIdentity($identity);
        }
    }

    private function setup($cas)
    {
        if (isset($cas['log'])) {
            $logger = new Logger('cas');
            $logger->pushHandler(new StreamHandler(Yii::getAlias($cas['log'])));

            phpCAS::setLogger($logger);
        }

        phpCAS::client($cas['host'], $cas['host'], $cas['port'], $cas['path']);
        phpCAS::setNoCasServerValidation();

        $handleLogoutRequest = [
            'check_client'    => false,
            'allowed_clients' => [],
        ];
        if (isset($cas['handle_logout_request'])) {
            $handleLogoutRequest = array_merge($handleLogoutRequest, $cas['handle_logout_request']);
        }

        phpCAS::handleLogoutRequests($handleLogoutRequest['check_client'], $handleLogoutRequest['allowed_clients']);
    }
}
