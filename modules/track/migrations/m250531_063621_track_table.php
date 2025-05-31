<?php

use yii\db\Migration;

/**
 * Class m250531_063621_track_table
 */
class m250531_063621_track_table extends Migration
{
    public const TABLE_NAME = 'track';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    /**
     * @return void
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'track_number' => $this->string()->notNull()->unique()->comment('Номер трека'),
            'status' =>  $this->string()->notNull()->comment('Статус'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-track_number-'.self::TABLE_NAME,
            $this->table,
            'track_number'
        );
        $this->createIndex(
            'idx-status-'.self::TABLE_NAME,
            $this->table,
            'status'
        );
    }

    /**
     * @return void
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
