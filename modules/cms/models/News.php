<?php

namespace app\modules\cms\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\cms\behaviors\HookBehavior;

/**
 * Модель новости.
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
                ['name'],
                'required'
            ],
            [
                'name',
                'string',
                'max' => 255
            ],
            [
                ['description', 'content', 'slug'],
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
            'act' => 'Активность'
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
           [
                'class' => HookBehavior::class,
                'text' => 'Добавлена новость'
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
            if ($this->image) {
                $this->image->saveAs('uploads/' . $this->image->name);
            }
        }
        return parent::beforeSave($insert);
    }
}
