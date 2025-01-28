<?php

namespace app\modules\reports\helpers;

use Yii;
use yii\helpers\{
    ArrayHelper,
    Json
};
use yii\bootstrap5\Html;

use app\traits\GetLabelTrait;
use app\modules\reports\{
    entities\ReportDataChangeEntity,
    repositories\ConstantRepository
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\helpers
 */
final class DataChangeHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'report_id' => Yii::t('entities', 'Отчет'),
            'data_id' => Yii::t('entities', 'Запись'),
            'content' => Yii::t('entities', 'Внесенные измененя'),
            'created_at' => Yii::t('entities', 'Внесены'),
            'created_uid' => Yii::t('entities', 'Внес'),
        ];
    }

    public static function operations(): array
    {
        return [
            ReportDataChangeEntity::OPERATION_ADD => Yii::t('entities', 'Добавление'),
            ReportDataChangeEntity::OPERATION_EDIT => Yii::t('entities', 'Редактирование'),
            ReportDataChangeEntity::OPERATION_DELETE => Yii::t('entities', 'Удаление')
        ];
    }

    public static function operationName(string $labelCode): ?string
    {
        return ArrayHelper::getValue(self::operations(), $labelCode);
    }

    public static function getItemForAccordion(array $changes): array
    {
        $constants = ConstantRepository::getAll([], true);
        $items = [];

        foreach ($changes as $row) {
            $content = Json::decode($row->content);

            $itemsContent = '';
            foreach ($content as $key => $description) {
                $item = self::getTextItem($key, $description, $constants);
                if ( $item) {
                    $itemsContent .= Html::tag('li', $item);
                }
            }

            $items[] = [
                'label' => date('d.m.Y H:i:s', $row->created_at) . ' # ' . $row->createdUser->shortName,
                'content' => Html::tag(
                    'div',
                    Html::tag(
                        'div',
                        Html::tag(
                            'ul',
                            $itemsContent, ['class' => 'mb-0']
                        ), ['class' => 'col-12 text-start']
                    ), ['class' => 'row']
                ),
                'contentOptions' => ['class' => 'bg-white'],
            ];
        }

        return $items;
    }

    private static function getTextItem(string $key, array $description, array $constants): ?string
    {
        if ( isset($constants[$key]) ) {
            $message = match($description['operation']) {
                ReportDataChangeEntity::OPERATION_DELETE => 'удалено значение <strong>{value}</strong>',
                ReportDataChangeEntity::OPERATION_EDIT => 'изменено значение с <strong>{oldValue}</strong> на <strong>{value}</strong>',
                ReportDataChangeEntity::OPERATION_ADD => 'добавлено значение <strong>{value}</strong>'
            };

            return Yii::t('views', "<strong>{constant}</strong>: $message", [
                'constant' => $constants[$key],
                'value' => $description['value'],
                'oldValue' => $description['oldValue'] ?? null
            ]);
        }

        return null;
    }
}