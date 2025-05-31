<?php

declare(strict_types=1);

namespace app\commands;

use Exception;
use Yii;
use app\common\rbac\CollectionRolls;
use yii\console\Controller;

class RoleController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex(): void
    {
        $auth = Yii::$app->authManager;

        $ROLE_ROOT = $auth->createRole(CollectionRolls::ROLE_ROOT);
        $auth->add($ROLE_ROOT);
        echo 'Create role ROLE_SUPER_ADMIN' . PHP_EOL;
    }
}
