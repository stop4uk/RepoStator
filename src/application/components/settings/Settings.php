<?php

namespace app\components\settings;

use Yii;
use yii\base\{
    Component,
    InvalidArgumentException
};
use yii\db\Query;
use yii\helpers\Json;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\components\settings
 */
final class Settings extends Component
{
    public array $preLoad = ['system'];
    protected array $items = [];

    public function init(): void
    {
        parent::init();
        if (!empty($this->preLoad)) {
            $this->load($this->preLoad);
        }
    }

    public function get(
        string $category,
        mixed $key = null,
        mixed $default = null
    ): string|null {
        $value = $default;
        $this->load($category);

        if ($key === null) {
            $value = isset($this->items[$category]) && !empty($this->items[$category])
                ? $this->items[$category]
                : $default;
        } elseif (is_string($key)) {
            $value = $this->items[$category][$key] ?? $default;
        } elseif (is_array($key)) {
            $value = [];
            foreach ($key as $val) {
                $value[$val] = $this->items[$category][$val]
                    ?? ((
                        is_array($default)
                        && isset($default[$val])
                    )
                        ? $default[$val]
                        : null
                    );
            }
        }

        return $value;
    }

    public function set(
        string $category,
        string $key,
        string|int|null $value = null
    ): void {
        if (array_key_exists($key, $this->items[$category])) {
            Yii::$app->getDb()->createCommand('
                UPDATE {{%settings}} SET `value` = :value WHERE
                category = :category AND `key` = :key
            ', compact('category', 'key', 'value'))->execute();
        }

        $this->items[$category][$key] = $value;
    }

    public function load(string|array|null $categories = null): void
    {
        if (is_string($categories)) {
            $categories = [$categories];
        }

        foreach ($categories as $idx => $category) {
            if (isset($this->items[$category])) {
                unset($categories[$idx]);
            } else {
                $this->items[$category] = [];
            }
        }

        if (empty($categories)) {
            return;
        }

        /**
         * Так как, после инициализации приложения таблицы с настройками еще нет - оборачиваем в try/catch,
         * чтобы все прошло без ошибок и проверяем наличие результата
         */
        try {
            $result = (new Query())
                ->select(['category', 'key', 'value'])
                ->from('{{%settings}}')
                ->where(['category' => $categories])
                ->all();
        } catch (\Throwable $throwable) {}

        if (isset($result)) {
            foreach ($result as $row) {
                try {
                    $this->items[$row['category']][$row['key']] = Json::decode($row['value']);
                } catch (InvalidArgumentException $ex) {
                    $this->items[$row['category']][$row['key']] = $row['value'];
                }
            }
        }
    }
}
