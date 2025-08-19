<?php

use yii\db\Migration;

class m220905_032809_init extends Migration
{
    public const string TABLE_NAME = 'user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->notNull()->unique()->comment('ID'),
            'username' => $this->string()->notNull()->unique()->comment('Логин'),
            'auth_key' => $this->string(32)->notNull()->comment('Ключ'),
            'password_hash' => $this->string()->notNull()->comment('Пароль'),
            'email' => $this->string()->notNull()->unique()->comment('Email'),
            'accessToken' => $this->string()->null()->defaultValue(null)->comment('Токен регистрации'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
