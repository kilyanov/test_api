<?php

namespace app\modules\requests\models\query;

use app\common\db\traits\DefaultActiveQueryTrait;
use app\modules\requests\models\Request;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class RequestQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Request[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|ActiveRecord|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
