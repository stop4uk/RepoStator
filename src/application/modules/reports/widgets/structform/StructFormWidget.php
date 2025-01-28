<?php

namespace app\modules\reports\widgets\structform;

use Yii;
use yii\base\Widget;
use yii\web\NotFoundHttpException;

use app\modules\reports\{
    entities\ReportDataEntity,
    repositories\ConstantRepository
};
use app\modules\users\helpers\RbacHelper;


/**
 * @property ReportDataEntity $model
 * @property string $formId
 * @property string $formField
 * @property bool $view
 *
 * @privat array $constants
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reportrs\widgets\structform
 */
class StructFormWidget extends Widget
{
    public $model;
    public $formId;
    public $formField;
    public $view = false;

    private readonly array $constants;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $groups = RbacHelper::getAllowGroupsArray('data.send.all');
        $this->constants = ConstantRepository::getAllow(
            reports: [$this->model->report_id => $this->model->report_id],
            groups: $groups,
            fullInformation: true
        );
    }

    public function run()
    {
        if ( !$this->model->structure ) {
            Yii::error(Yii::t('exceptions', 'Error form REPORTForm Widget. GROUP:{group}, REPORT:{report}', [
                'group' => $this->model->group_id,
                'report' => $this->model->report_id
            ]), 'Reports.Send');

            throw new NotFoundHttpException(Yii::t('exceptions', 'Структура для данного отчета и группы не найдена. Пожалуйста, обратитесь к администратору'));
        }

        echo $this->render('form', [
            'model' => $this->model,
            'formId' => $this->formId,
            'formField' => $this->formField,
            'constants' => $this->constants,
            'view' => $this->view
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    public function formContents(array $data): array
    {
        $results = [];

        if ( $this->model->structure->use_union_rules ) {
            foreach ($data as $item) {
                if ( isset($this->constants[$item]['union_rules']) && $this->constants[$item]['union_rules'] ) {
                    if ( str_contains('=', $this->constants[$item]['union_rules']) ) {
                        $rules = explode('=', $this->constants[$item]['union_rules']);
                        $results[$rules[0]][$rules[1]][] = $item;
                    } else {
                        $results['###'][] = $item;
                    }
                } else {
                    $results['###'][] = $item;
                }
            }
        } else {
            foreach ($data as $item) {
                $results['###'][] = $item;
            }
        }

        return $results ?? $data;
    }
}
