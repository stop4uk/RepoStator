<?php

namespace app\controllers\reports;

use Yii;
use yii\base\Exception;
use yii\web\{
    BadRequestHttpException,
    Response
};
use yii\bootstrap5\ActiveForm;

use app\base\{
    BaseAR,
    BaseController,
};
use app\actions\IndexAction;
use app\services\report\SendService;
use app\entities\report\ReportDataEntity;
use app\models\report\DataModel;
use app\search\report\SendSearch;
use yii\filters\AccessControl;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\reports
 */
final class SendController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [
                            'data.send',
                        ],
                    ],
                    [
                        'actions' => ['process'],
                        'allow' => true,
                        'roles' => ['data.send', 'data.send.all'],
                        'roleParams' => function($rule) {
                            return [
                                'group' => $this->request->get('group_id'),
                                'form_control' => $this->request->get('form_control')
                            ];
                        },
                    ],
                ],
            ],
        ];
    }

    public function __construct(
        $id,
        $module,
        private readonly SendService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => SendSearch::class
            ]
        ];
    }

    public function actionProcess(
        int $report_id,
        int $group_id,
        string $report_datetime,
        bool $form_control = false
    ) {
        if ( !is_numeric($report_datetime) ) {
            throw new BadRequestHttpException(Yii::t('exceptions', 'Неверный формат начала отчетного периода'));
        }

        $entity = new ReportDataEntity(['scenario' => BaseAR::SCENARIO_INSERT]);
        $model = new DataModel($entity, [
            'group_id' => $group_id,
            'report_id' => $report_id,
            'report_datetime' => $report_datetime,
            'form_control' => $form_control
        ]);

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->save(
                    model: $model,
                    categoryForLog: 'Reports.Send',
                    errorMessage: Yii::t('exceptions', 'При передаче сведений по отчету возникла ошибка. Пожалуйста, обратитесь к администратору')
                );

                $this->setMessage('success', Yii::t('notifications', 'Сведения успешно переданы'));
                return $this->redirect(['/reports/send']);
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('process', compact('model'));
    }
}
