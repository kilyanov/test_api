<?php

declare(strict_types=1);

namespace app\commands;

use app\common\interface\StatusAttributeInterface;
use app\common\rbac\CollectionRolls;
use app\models\User;
use Exception;
use Symfony\Component\Uid\Uuid;
use Yii;
use yii\console\Controller;

class UserController extends Controller
{

    /**
     * @throws Exception
     */
    public function actionIndex(): void
    {
        $auth = Yii::$app->authManager;
        $userRoot = new User([
            'id' => Uuid::v6()->__toString(),
            'username' => 'admin',
            'email' => 'test@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $userRoot->setScenario(User::SCENARIO_CREATE);
        $userRoot->setPassword('admin');
        $userRoot->generateAuthKey();
        $userRoot->save();
        $rootRole = $auth->getRole(CollectionRolls::ROLE_ROOT);
        $auth->assign($rootRole, $userRoot->id);
        echo 'ADD OK ' . CollectionRolls::ROLE_ROOT . ' ID# ' . $userRoot->id . PHP_EOL;
    }
}
