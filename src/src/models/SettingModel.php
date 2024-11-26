<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\models
 */
final class SettingModel extends Model
{
    public $key;

    public readonly array $settings;
    public readonly array $settingsInArray;

    public function __construct($config = [])
    {
        $this->settings = (new Query())->select('*')->from('settings')->all();
        if ( $this->settings ) {
            foreach ($this->settings as $setting) {
                $this->key[$setting['category'] . '__' . $setting['key']] = $setting['value'];
                $settingsInArray[$setting['category']][$setting['key']] = [
                    'description' => $setting['description'],
                    'value' => $setting['value'],
                    'required' => $setting['required']
                ];
            }
        }

        $this->settingsInArray = $settingsInArray ?? [];

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['key', 'checkSetting'],
        ];
    }

    public function checkSetting()
    {
        unset($this->key[0]);

        foreach ($this->key as $key => $value) {
            $data = explode('__', $key);
            if ( !isset($this->settingsInArray[$data[0]][$data[1]]) ) {
                $this->addError("key[$key]", Yii::t('models_error', 'Такого ключа не существует'));
            }

            if (
                $this->settingsInArray[$data[0]][$data[1]]['required']
                && !strlen($value)
            ) {
                $this->addError("key[$key]", Yii::t('models_error', 'Поле обязательно должно быть заполнено' . $value));
            }
        }
    }

    public function saveSettings()
    {
        foreach ($this->key as $key => $value) {
            $data = explode('__', $key);
            if ( $this->settingsInArray[$data[0]][$data[1]]['value'] != $value ) {
                Yii::$app->db->createCommand()->update('settings', ['value' => $value], ['category' => $data[0], 'key' => $data[1]])->execute();
            }
        }
    }
}