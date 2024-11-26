<?php

namespace app\useCases\reports\models;

use Yii;
use yii\helpers\{
    ArrayHelper,
    Json
};

use app\components\{
    base\BaseModel,
    base\BaseAR
};
use app\useCases\reports\{
    entities\ReportDataChangeEntity,
    entities\ReportEntity,
    entities\ReportStructureEntity,
    repositories\DataBaseRepository,
    repositories\ReportBaseRepository,
    repositories\StructureBaseRepository,
    helpers\DataHelper
};
use app\useCases\users\{
    entities\group\GroupEntity,
    entities\user\UserEntity,
    repositories\group\GroupRepository
};

/**
 * @property int $report_id
 * @property int $report_datetime
 * @property int $group_id
 * @property int $struct_id
 * @property string $content
 *
 * @property ReportStructureEntity $structure
 * @property array $structureContent
 * @property bool $form_control
 *
 * @property-read ReportEntity $report
 * @property-read GroupEntity $group
 * @property-read ReportDataChangeEntity[] $changes
 * @property-read UserEntity $createdUser
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\models\report
 */
final class DataModel extends BaseModel
{
    public $report_id;
    public $report_datetime;
    public $group_id;
    public $struct_id;
    public $content;

    public ?ReportStructureEntity $structure = null;
    public array $structureContent = [];

    public bool $form_control;

    public readonly ReportEntity $report;
    public readonly GroupEntity $group;
    public readonly UserEntity|null $createdUser;
    public readonly array|null $changes;

    public function init()
    {
        if ( !$this->isNewEntity ) {
            foreach (['report', 'group', 'structure', 'changes', 'createdUser'] as $relation) {
                $this->{$relation} = $this->entity->{$relation};
            }

            if ( $this->content ) {
                $this->content = Json::decode($this->content);
            }
        } else {
            $this->group = GroupRepository::get($this->group_id);
            $this->report = ReportBaseRepository::get($this->report_id);
            $this->structure = StructureBaseRepository::getByReportAndGroup($this->report_id, $this->group_id);
            $this->createdUser = null;
            $this->changes = [];
        }

        if ( $this->structure !== null ) {
            $this->struct_id = $this->structure->id;
            $this->structureContent = Json::decode($this->structure->content);
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            [['report_id', 'group_id', 'struct_id', 'report_datetime'], 'required'],
            [['report_id', 'group_id', 'struct_id', 'report_datetime'], 'integer'],

            ['content', 'checkContent'],
            ['report_id', 'checkAccess'],
            ['report_id', 'checkReportUnique', 'when' => fn($model) => ( !$model->isNewEntity && $model->report->left_period)],

            ['form_control', 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return DataHelper::labels();
    }

    public function checkReportUnique()
    {
        if ( $this->report_id && $this->group_id && $this->report_datetime ) {
            $periodsList = DataHelper::getTimePeriods($this->report, $this->report_datetime);
            $periodsArray = ArrayHelper::map($periodsList, 'start', 'end');

            if ( !in_array($this->report_datetime, array_keys($periodsArray)) && !$this->form_control ) {
                $this->addError('content', Yii::t('models_error', 'Указанное в запросе время не ' .
                    'подходит под периоды отправки сведений'));
            }

            $query = DataBaseRepository::getAllBy(['report_id' => $this->report_id, 'group_id' => $this->group_id], [])->all();
            if ( $query ) {
                foreach ($query as $row) {
                    if ( $this->report_datetime == $row->report_datetime && !$this->form_control ) {
                        $this->addError('content', Yii::t('models_error', 'Сведения по отчету от ' .
                            'имени этой группы и за данный период уже были переданы'));

                        break;
                    }
                }
            }
        }
    }

    public function checkAccess()
    {
        if (
            !$this->hasErrors()
            && $this->entity->scenario == BaseAR::SCENARIO_INSERT
        ) {
            if ( $this->report->left_period ) {
                if ( !$this->form_control ) {
                    $periods = DataHelper::getTimePeriods($this->report, time(), true);

                    if ( $periods && $this->report_datetime != $periods->start) {
                        $this->addError('content', Yii::t('models_error', 'Вы не можете передавать ' .
                            'данные за не текущий период'));

                        return false;
                    }
                } else {
                    $periods = DataHelper::getTimePeriods($this->report, time());
                    $startTimes = ArrayHelper::map($periods, 'start', 'start');

                    if ( !in_array($this->report_datetime, $startTimes) ) {
                        $this->addError('content', Yii::t('models_error', 'Вы не можете передавать ' .
                            'данные за не текущий период'));

                        return false;
                    }

                    if ( Yii::$app->getSession()->has('controlReport') ) {
                        $sessionData = Json::decode(Yii::$app->getSession()->get('controlReport'));
                        $hasData = [
                            'group_id' => $this->group_id,
                            'report_id' => $this->report_id,
                            'report_datetime' => $this->report_datetime
                        ];

                        if ( array_diff_assoc($sessionData, $hasData) ) {
                            $this->addError('content', Yii::t('models_error', 'Данные контроля не ' .
                                'совпатают с текущими данными'));
                        }
                    }
                }
            }
        }
    }

    public function checkContent()
    {
        if ( count(array_filter($this->content)) == 0 ) {
            $this->addError('content', Yii::t('models_error', 'Нельзя передавать пустой отчет'));
        }
    }

    public function afterValidate()
    {
        if ( $this->content ) {
            $this->content = array_filter($this->content);
        }

        parent::afterValidate();
    }
}