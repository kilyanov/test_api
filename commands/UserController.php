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
        $userModerator = new User([
            'username' => 'moderator',
            'email' => 'moderator@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $userModerator->setScenario(User::SCENARIO_CREATE);
        $userModerator->setPassword('moderator');
        $userModerator->generateAuthKey();
        $userModerator->save();
        $moderatorRole = $auth->getRole(CollectionRolls::ROLE_MODERATOR);
        $auth->assign($moderatorRole, $userModerator->id);
        echo 'ADD OK ' . CollectionRolls::ROLE_MODERATOR . ' ID# ' . $userModerator->id . PHP_EOL;
        $userUser = new User([
            'username' => 'user',
            'email' => 'user@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $userUser->setScenario(User::SCENARIO_CREATE);
        $userUser->setPassword('user');
        $userUser->generateAuthKey();
        $userUser->save();
        $userRole = $auth->getRole(CollectionRolls::ROLE_USER);
        $auth->assign($userRole, $userUser->id);
        echo 'ADD OK ' . CollectionRolls::ROLE_USER . ' ID# ' . $userUser->id . PHP_EOL;
    }
}
