<?php

declare(strict_types=1);

namespace app\modules\auth\modules\v1\forms;

use app\modules\user\models\User;
use Yii;
use yii\base\Model;

class AuthForm extends Model
{
    /**
     * @var string|null
     */
    public ?string $username = null;

    /**
     * @var string|null
     */
    public ?string $password = null;

    /**
     * @var User|null
     */
    private ?User $user = null;

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required',],
            [['username', 'password'], 'string',],
            ['password', 'authorization']
        ];
    }

    /**
     * @return void
     */
    public function authorization(): void
    {
        if (!$this->hasErrors()) {
            if (!$this->getUser() || !$this->getUser()->validatePassword($this->password)) {
                $this->addError('password', 'Неправильное имя пользователя или пароль.');
            }
        }
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->getUser()->login($this->getUser());
        }

        return false;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->user === null) {
            $this->user = User::find()->where([
                'username' => $this->username,
            ])->blocked()->one();
        }

        return $this->user;
    }
}
