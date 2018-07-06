<?php

namespace app\modules\cms\models;

use app\modules\cms\behaviors\SlugBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\cms\behaviors\NotifyBehavior;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Модель новости.
 *
 * @property $id           int      Идентификатор
 * @property $name         string   Название
 * @property $description  string   Краткое описание
 * @property $content      string   Подробное описание
 * @property $date         string   Дата
 * @property $act          bool     Активность
 * @property $image        string   Изображение
 * @property $created_at   int      Дата создания
 * @property $updated_at   int      Дата обновления
 * @property $slug         string   ЧПУ
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * Метка удаления изображения.
     * 
     * @var bool
     */
    public $delete_image;

    /**
     * Адрес детального просмотра новости.
     *
     * @var string
     */
    public $urlNews = '/news/view';

    /**
     * Папка для загрузки изображений.
     *
     * @var string
     */
    public $dirImages = '@web/uploads/';

    /**
     * @var string Ключ для кэша новости.
     */
    public static $cacheKey = 'news.id.';

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['name', 'slug'],
                'required'
            ],
            [
                ['name', 'slug'],
                'string',
                'max' => 255
            ],
            [
                ['description', 'content'],
                'string'
            ],
            [
                'date',
                'date',
                'format' => 'php:Y-m-d',
            ],
            [
                'image',
                'image',
                'extensions' => 'png,jpeg,jpg,bmp'
            ],
            [
                ['act', 'delete_image'],
                'boolean'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'slug' => 'Адрес страницы',
            'description' => 'Краткое описание',
            'content' => 'Текст новости',
            'date' => 'Дата',
            'image' => 'Изображение',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'act' => 'Активность',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return  [
           [
               'class' => TimestampBehavior::class,
               'attributes' => [
                   ActiveRecord::EVENT_BEFORE_INSERT => ['created_at' , 'updated_at'],
                   ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
               ]
           ],
           /*[
                'class' => NotifyBehavior::class,
                'text' => 'Добавлена новость'
           ]*/
           [
               'class' => SlugBehavior::class,
               'attributes' => [
                   ActiveRecord::EVENT_BEFORE_VALIDATE => ['slug']
               ],
           ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if (!$this->delete_image && !$this->image) {
            unset($this->image);
        } else {
            if ($this->image instanceof UploadedFile) {
                $this->image->saveAs('uploads/' . $this->image->name);
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function fields(): array
    {
        return [
            'id',
            'name',
            'description',
            'content',
            'image',
            'image_url' => function () {
                return $this->getUrlImage();
            },
            'date',
            'created_at' => function (){
                return \Yii::$app->formatter->asDateTime($this->created_at, 'Y-MM-dd, H:i:s');
            },
            'updated_at' => function (){
                return \Yii::$app->formatter->asDateTime($this->created_at, 'Y-MM-dd, H:i:s');
            },
            'act',
            'slug'
        ];
    }

    /**
     * Url-адрес изображения.
     *
     * @return string|null
     */
    public function getUrlImage(): ?string
    {
        $result = null;
        if ($this->image) {
            $result = Url::base(true) . '/uploads/' . $this->image;
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        \Yii::$app->redis->del(self::$cacheKey . $this->id);
    }
}
