<?php


use yii\behaviors\{BlameableBehavior, TimestampBehavior};
use yii\db\{ActiveQuery, ActiveRecord};

/**
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
 * @property int $created_at Дата и время добавления
 * @property int|null $created_uid
 * @property int|null $updated_at Дата и время обновления
 */
final class AttachFileEntity extends ActiveRecord
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
            ['storage', 'in', 'range' => array_keys(AttachFileHelper::getStorageName(asList: true))],
            ['modelName', 'string', 'max' => 100],
            ['modelKey', 'string', 'max' => 36],
            ['file_hash', 'string', 'max' => 32],
            ['file_extension', 'string', 'max' => 4],
            ['file_mime', 'string', 'max' => 30],
            ['file_status', 'in', 'range' => array_keys(AttachFileHelper::getFileStatus(asList: true))],
            ['file_type', 'string', 'max' => 24],
            [['file_size', 'file_version', 'created_at', 'created_uid', 'updated_at'], 'integer'],
            ['file_tags', 'safe'],

            [['storage', 'name', 'modelName', 'modelKey', 'file_hash', 'file_size', 'file_extension', 'file_mime', 'file_version', 'file_status'], 'required']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => '#',
            'storage' => Yii::t('entities', 'Хранилище'),
            'name' => Yii::t('entities', 'Название'),
            'modelName' => Yii::t('entities', 'Модель'),
            'modelKey' => Yii::t('entities', 'Ключ модели'),
            'file_type' => Yii::t('entities', 'Тип'),
            'file_hash' => Yii::t('entities', 'Хэш'),
            'file_size' => Yii::t('entities', 'Размер'),
            'file_extension' => Yii::t('entities', 'Расширение'),
            'file_mime' => Yii::t('entities', 'MIME тип'),
            'file_tags' => Yii::t('entities', 'Теги'),
            'file_version' => Yii::t('entities', 'Версия'),
            'file_status' => Yii::t('entities', 'Статус'),
            'created_at' => Yii::t('entities', 'Загружен'),
            'created_uid' => Yii::t('entities', 'Загрузил'),
            'updated_at' => Yii::t('entities', 'Обновлен'),
        ];
    }

    public static function find(): ActiveQuery
    {
        return new AttachFileQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return '{{%attachedFiles}}';
    }
}
