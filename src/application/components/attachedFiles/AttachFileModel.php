<?php

namespace app\components\attachfiles;

use Yii;
use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

/**
 * This is the model class for table "attach_file".
 *
 * @property int $id
 * @property string $storage
 * @property string $name Название файла
 * @property string $model Модель
 * @property int $modelKey ID записи
 * @property string|null $file_type
 * @property string $file_hash Хэш
 * @property string $file_path Путь до каталога с файлом
 * @property int $file_size Размер
 * @property string $file_extension Расширение файла
 * @property string $file_mime MIME
 * @property array|null $file_tags Теги файла
 * @property integer $file_version
 * @property integer $file_status
 * @property integer|null $customer_id
 * @property int $created_at Дата и время добавления
 * @property int|null $created_uid
 * @property int|null $updated_at Дата и время обновления
 */
final class AttachFileModel extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => time(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_uid'],
                ],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            ['storage', 'default', 'value' => AttachFileHelper::STORAGE_LOCAL],
            ['file_hash', 'default', 'value' => Yii::$app->getSecurity()->generateRandomString(32)],
            ['file_version', 'default', 'value' => 1],
            ['file_status', 'default', 'value' => AttachFileHelper::FSTATUS_ACTIVE],

            [['storage', 'name', 'file_path'], 'string', 'max' => 255],
            ['modelName', 'string', 'max' => 100],
            ['modelKey', 'string', 'max' => 36],
            ['file_hash', 'string', 'max' => 32],
            ['file_extension', 'string', 'max' => 4],
            ['file_mime', 'string', 'max' => 30],
            ['file_status', 'in', 'range' => array_keys(AttachFileHelper::FSTATUSES)],
            ['file_type', 'string', 'max' => 24],
            [['file_size', 'file_version', 'customer_id', 'created_at', 'created_uid', 'updated_at'], 'integer'],
            ['file_tags', 'safe'],

            [['storage', 'name', 'modelName', 'modelKey', 'file_hash', 'file_size', 'file_extension', 'file_mime', 'file_version', 'file_status'], 'required']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => '#',
            'storage' => '',
            'name' => 'Название',
            'modelName' => 'Модель',
            'modelKey' => 'Ключ модели',
            'file_type' => 'Тип',
            'file_hash' => 'Хэш',
            'file_size' => 'Размер',
            'file_extension' => 'Расширение',
            'file_mime' => 'MIME тип',
            'file_tags' => 'Теги',
            'file_version' => 'Версия',
            'file_status' => 'Статус',
            'customer_id' => 'Организация',
            'created_at' => 'Загружен',
            'created_uid' => 'Пользователь',
            'updated_at' => 'Дата и время обновления',
        ];
    }

    public static function find(): ActiveQuery
    {
        return new AttachFileQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return '{{%attachfile}}';
    }
}
