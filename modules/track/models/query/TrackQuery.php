<?php

declare(strict_types=1);

namespace app\modules\track\models\query;

use app\common\db\traits\DefaultActiveQueryTrait;
use app\modules\track\models\Track;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class TrackQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Track[]|array
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
