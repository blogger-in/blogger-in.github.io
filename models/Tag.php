<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $title
 *
 * @property ArticleTag[] $articleTags
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'article_id'])
            ->viaTable('article_tag', ['tag_id' => 'id']);
    }

    public function getAll()
    {
        return Tag::find()->all();
    }

    public function getTitle()
    {
        $title = $this->title;
        return $title;
    }

    public function getArticlesCount()
    {
        return $this->getArticles()->count();
    }

    public static function getArticlesByTag($id)
    {
        // build a DB query to get all articles //with status = 1

        $getID = ArticleTag::find()->where(['tag_id'=>$id])->all();
       // echo '<pre>'; var_dump($getID);

        $article_id = ArrayHelper::getColumn($getID,'article_id');


        //echo '<pre>'; var_dump($article_id);

        // get the total number of articles (but do not fetch the article data yet)
        $query = Article::find()->where(['id'=>$article_id]);
        $count = $query->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>6]);

        // limit the query using the pagination and retrieve the articles
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $data ['articles'] = $articles;
        $data ['pagination'] = $pagination;
        return $data;
    }
}
