<?php

namespace app\modules\reports\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\bootstrap5\ActiveForm;

use app\actions\{
    DeleteAction,
    EnableAction,
    IndexAction,
    ViewAction
};
use app\components\base\BaseController;
use app\modules\reports\{
    entities\ReportDataEntity,
    entities\ReportEntity,
    models\DataModel,
    forms\ControlCheckFullForm,
    forms\ControlCreateForForm,
    repositories\DataRepository,
    services\ControlService,
    helpers\DataHelper,
    search\DataSearch,
};
use app\modules\users\components\rbac\items\Permissions;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
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
                        'actions' => ['index', 'getperiods'],
                        'allow' => true,
                        'roles' => [
                            Permissions::DATA_LIST,
                        ],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => [
                            Permissions::DATA_VIEW_MAIN,
                            Permissions::DATA_VIEW_GROUP,
                            Permissions::DATA_VIEW_ALL,
                            Permissions::DATA_VIEW_DELETE_MAIN,
                            Permissions::DATA_VIEW_DELETE_GROUP,
                            Permissions::DATA_VIEW_DELETE_ALL,
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
                            Permissions::DATA_EDIT_MAIN,
                            Permissions::DATA_EDIT_GROUP,
                            Permissions::DATA_EDIT_ALL
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
                        'roles' => [Permissions::DATA_CREATEFOR],
                    ],
                    [
                        'actions' => ['checkfull'],
                        'allow' => true,
                        'roles' => [Permissions::DATA_CHECKFULL]
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            Permissions::DATA_DELETE_MAIN,
                            Permissions::DATA_DELETE_GROUP,
                            Permissions::DATA_DELETE_ALL
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
                            Permissions::DATA_ENABLE_MAIN,
                            Permissions::DATA_ENABLE_GROUP,
                            Permissions::DATA_ENABLE_ALL
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

        if ($this->request->isAjax && $model->load($this->request->post())) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($this->request->post()) && $model->validate()) {
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

        if ($this->request->isAjax && $form->load($this->request->post())) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ($form->load($this->request->post()) && $form->validate()) {
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

        if ($this->request->isAjax && $form->load($this->request->post())) {
            if ($validate = ActiveForm::validate($form)) {
                return $validate;
            }
        }

        if ($form->load($this->request->post()) && $form->validate()) {
            return $form->getOutList();
        }

        return [];
    }

    public function actionGetperiods($report_id): array
    {
        $this->response->format = Response::FORMAT_JSON;

        $dateFormat = str_replace('php:', '', Yii::$app->settings->get('system', 'app_language_dateTimeMin'));
        $periods = [];
        $report = ReportEntity::find()
            ->where(['id' => $report_id])
            ->limit(1)
            ->one();

        if ($report->left_period) {
            $items = DataHelper::getTimePeriods($report, time());
            if ($items) {
                //Сортировка не работает так как мне надо, поэтому, обойдем весь массив, начиная с последнего элемента
                $items = (array)$items;
                end($items);

                while($value = current($items)) {
                    $periods[] = date(
                        format: $dateFormat,
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

        if ($query !== null) {
            return $query;
        }

        throw new NotFoundHttpException(Yii::t('exceptions', 'Переданные сведения не найдены'));
    }
}
