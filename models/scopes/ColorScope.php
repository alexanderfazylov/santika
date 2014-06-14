<?php
/**
 * Created by PhpStorm.
 * User: KURT
 * Date: 19.05.14
 * Time: 0:07
 */

namespace app\models\scopes;


use yii\db\ActiveQuery;
use yii\db\Query;

class ColorScope extends ActiveQuery
{
    /**
     * @param string $article
     * @return $this
     */
    public function byArticle($article)
    {
        $this->andWhere(['article' => $article]);
        return $this;
    }
} 