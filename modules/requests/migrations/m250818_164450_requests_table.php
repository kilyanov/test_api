<?php

use app\modules\requests\interface\StatusRequestInterface;
use yii\db\Migration;

/**
 * Class m250818_164450_requests_table
 */
class m250818_164450_requests_table extends Migration
{
    public const string TABLE_NAME = 'request';

    /**
     * @var string
     */
    private string $table = '{{%' . self::TABLE_NAME . '}}';

    /**
     * @var string
     */
    private string $user = '{{%user}}';

    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->notNull()->unique()->comment('ID'),
            'name' => $this->string()->notNull()->comment('Имя'),
            'email' => $this->string()->notNull()->comment('Email'),
            'message' => $this->text()->notNull()->comment('Сообщение'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'status' => $this->smallInteger()->notNull()->defaultValue(StatusRequestInterface::STATUS_ACTIVE),
            'idModerator' => $this->string()->null()->defaultValue(null)->comment('Модератор'),
            'createdAt' => $this->dateTime()->notNull()->comment('Создано'),
            'updatedAt' => $this->dateTime()->notNull()->comment('Обновлено'),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx_idModerator_' . self::TABLE_NAME,
            $this->table,
            ['idModerator']
        );
        $this->addForeignKey(
            'fk-idModerator-' . self::TABLE_NAME,
            $this->table,
            'idModerator',
            $this->user,
            'id'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-idModerator-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
