<?php

namespace app\modules\cms\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * Генерация slug.
 */
class SlugBehavior extends AttributeBehavior
{
    /**
     * Поле, по которому генерируем slug.
     */
    public $attribute = 'name';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'getSlug'
        ];
    }

    /**
     * Генерация slug.
     */
    public function getSlug(): void
    {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        $result = strtr($this->owner->{$this->attribute}, $converter);
        $result = strtolower($result);
        $result = preg_replace('~[^-a-z0-9_]+~u', '-', $result);
        $result = trim($result, "-");
        $this->owner->slug = $result;
        if ($this->isExist()) {
            $this->owner->slug.= rand(1, 1000000);
        }
    }

    /**
     * Проверка на существование slug.
     *
     * @return bool
     */
    public function isExist(): bool
    {
        $query = $this->owner::find()
            ->where(['slug' => $this->owner->slug]);
        if (!$this->owner->isNewRecord) {
            $query->andWhere(['<>', 'id', $this->owner->id]);
        }
        $model = $query->one();
        return $model ? true : false;
    }
}
