<?php

namespace app\controllers\reports;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use yii\bootstrap5\ActiveForm;

use app\base\BaseController;
use app\actions\{
    IndexAction,
    ViewAction,
    DeleteAction,
    EnableAction,
};
use app\services\report\ControlService;
use app\entities\report\{
    ReportEntity,
    ReportDataEntity
};
use app\models\report\DataModel;
use app\repositories\report\DataRepository;
use app\helpers\report\DataHelper;
use app\forms\report\{
    ControlCheckFullForm,
    ControlCreateForForm
};
use app\search\report\DataSearch;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\reports
 */
final class ControlController extends BaseController
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
                            'data.list',
                        ],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => [
                            'data.view.main',
                            'data.view.group',
                            'data.view.all',
                            'data.view.delete.main',
                            'data.view.delete.group',
                            'data.view.delete.all',
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = DataRepository::get(
                                id: $this->request->get('id'),
                                active: false
                            );

                            return [
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
                            ];
                        },
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            'data.edit.main',
                            'data.edit.group',
                            'data.edit.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = DataRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
                            ];
                        },
                    ],
                    [
                        'actions' => ['createfor'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if ( $this->request->isPost ) {
                                $group_id = $this->request->post('ControlCreateForForm')['group'];

                                if ( $group_id ) {
                                    return (
                                        Yii::$app->getUser()->can('data.createFor')
                                        && in_array($group_id, array_keys(Yii::$app->getUser()->getIdentity()->groups))
                                    );
                                }
                            }

                            return false;
                        }
                    ],
                    [
                        'actions' => ['checkfull'],
                        'allow' => true,
                        'roles' => ['data.checkFull']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            'data.delete.main',
                            'data.delete.group',
                            'data.delete.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = DataRepository::get($this->request->get('id'));

                            return [
                                'name' => 'delete',
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
                            ];
                        },
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            'data.enable.main',
                            'data.enable.group',
                            'data.enable.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = DataRepository::get(
                                id: $this->request->get('id'),
                                active: false
                            );

                            return [
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
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
        private readonly ControlService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => DataSearch::class
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => DataRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => DataModel::class,
                'exceptionMessage' => 'Запрашиваемые сведения отчета не найдены, или недоступны'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => DataRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении переданных сведений возникли ошибки. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Переданные сведения скрыты и показатели более не учитываются',
                'exceptionMessage' => 'Запрашиваемые сведения отчета не найдены, или недоступны'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => DataRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемые сведения отчета не найдены, или недоступны',
                'formControl' => true
            ],
        ];
    }

    public function actionEdit(int $id, $form_control = null): array|string|Response
    {
        $entity = $this->findEntity($id);
        $entity->scenario = $entity::SCENARIO_UPDATE;

        $model = new DataModel($entity, [
            'form_control' => (bool)$form_control
        ]);

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->edit($model);

                $this->setMessage('success', Yii::t('notifications', 'Ранее переданные сведения успешно отредактированы. Показатели будут обновлены'));
                return $this->refresh();
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionCreatefor()
    {
        $form = new ControlCreateForForm();

        if ( $this->request->isAjax && $form->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ( $form->load($this->request->post()) && $form->validate() ) {
            Yii::$app->getSession()->set('controlReport', Json::encode([
                'group_id' => $form->group,
                'report_id' => $form->report,
                'report_datetime' => strtotime($form->period)
            ]));
        }

        return $this->redirect([
            '/reports/send/process',
            'report_id' => $form->report,
            'group_id' => $form->group,
            'report_datetime' => strtotime($form->period),
            'form_control' => true
        ]);
    }

    public function actionCheckfull(): array
    {
        $form = new ControlCheckFullForm();
        $this->response->format = Response::FORMAT_JSON;

        if ( $this->request->isAjax && $form->load($this->request->post()) ) {
            if ( $validate = ActiveForm::validate($form) ) {
                return $validate;
            }
        }

        if ( $form->load($this->request->post()) && $form->validate() ) {
            return $form->getOutList();
        }

        return [];
    }

    public function actionGetperiods($report_id): array
    {
        $this->response->format = Response::FORMAT_JSON;
        $periods = [];
        $report = ReportEntity::find()
            ->where(['id' => $report_id])
            ->limit(1)
            ->one();

        if ( $report->left_period ) {
            $items = DataHelper::getTimePeriods($report, time());
            if ( $items ) {
                //Сортировка не работает так как мне надо, поэтому, обойдем весь массив, начиная с последнего элемента
                $items = (array)$items;
                end($items);

                while( $value = current($items) ) {
                    $periods[] = date(
                        format: Yii::$app->settings->get('system', 'app_language_dateTimeMin'),
                        timestamp: $value['start']
                    );
                    prev($items);
                }
            }
        } else {
            for($i=0; $i <= 10; $i++) {
                $periods[] = Yii::$app->formatter->asDate(strtotime("-$i day"));
            }
        }

        return ['items' => $periods];
    }

    private function findEntity(int $id): ReportDataEntity
    {
        $query = DataRepository::get($id, ['report', 'group', 'structure', 'changes', 'createdUser']);

        if ( $query !== null) {
            return $query;
        }

        throw new NotFoundHttpException(Yii::t('exceptions', 'Переданные сведения не найдены'));
    }
}
