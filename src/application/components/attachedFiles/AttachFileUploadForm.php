<?php

namespace app\components\attachedFiles;

use Yii;
use yii\base\Model;

final class AttachFileUploadForm extends Model
{
    public $modelClass;
    public $modelKey;
    public $modelType;
    public $uploadFile;

    private $workModel = null;

    public function rules(): array
    {
        $workModel = $this->getWorkModel();
        $rules = [
            [['modelClass', 'modelKey', 'modelType'], 'required'],
            [['modelClass', 'modelKey', 'modelType'], 'string'],
        ];

        if (
            isset($workModel['attachRules'][$this->modelType]['rules'])
            && is_array($workModel['attachRules'][$this->modelType]['rules'])
        ) {
            foreach($workModel['attachRules'][$this->modelType]['rules'] as $rule) {
                array_unshift($rule, 'uploadFile');
                $rules[] = $rule;
            }
        }

        if (isset($workModel['attachRules'][$this->modelType]['maxFiles'])) {
            $rules[] = ['uploadFile', 'checkCountOfExistFiles'];
        }

        return $rules;
    }

    public function checkCountOfExistFiles($attribute)
    {
        $workModel = $this->getWorkModel();
        $countExist = count($workModel->getAttachedFilesByType($this->modelType));

        if (($countExist + 1) > $workModel['attachRules'][$this->modelType]['maxFiles']) {
            $this->addError($attribute, 'Необходимые документы с типом "' . $workModel->getAttachedFileTypeName($this->modelType) . '" уже прикреплены');
        }
    }

    public function getWorkModel()
    {
        if ($this->workModel === null) {
            $object = Yii::createObject($this->modelClass);
            $this->workModel = $object->find()->where([$object->modelKey => $this->modelKey])->limit(1)->one();
        }

        return $this->workModel;
    }

    public static function createFromParams(string $params): self
    {
        return new self(unserialize(base64_decode($params)));
    }

    public static function createModelFromParams(string $params): Model
    {
        $model = self::createFromParams($params);
        return $model->getWorkModel();
    }
}
