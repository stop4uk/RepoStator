<?php

namespace app\modules\reports\controllers;

use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

use app\components\{
    base\BaseController,
    attachedFiles\AttachFileActionsTrait
};
use app\modules\reports\{
    components\formReport\FormFactory,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository,
    repositories\ReportRepository,
    repositories\TemplateRepository,
    search\JobSearch,
    forms\StatisticForm
};
use app\modules\users\{
    components\rbac\items\Permissions,
    repositories\GroupRepository
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
 */
final class StatisticController extends BaseController
{
    use AttachFileActionsTrait;

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [
                            Permissions::STATISTIC
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $session = Yii::$app->getSession();
        $sessionKey = 'Job_search';
        $searchModel = new JobSearch(['onlyMine' => true]);
        $searchFilters = $this->request->post();
        $form = new StatisticForm();

        if ($this->request->isGet) {
            $session->remove($sessionKey);
        }

        if ($this->request->isPost) {
            if (
                $this->request->getQueryParam('page') !== null
                && $session->has($sessionKey)
            ) {
                $searchFilters = $session->get($sessionKey);
            } else {
                $session->set($sessionKey, $this->request->post());
            }
        }

        $dataProvider = $searchModel->search($searchFilters);

        return $this->render('index', compact(
            'form',
            'searchModel',
            'dataProvider'
        ));
    }

    public function actionForm()
    {
        $form = new StatisticForm();
        if ($this->request->isAjax && $form->load($this->request->post())) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ($form->load($this->request->post()) && $form->validate()) {
            $factory = new FormFactory($form);
            $factory->process();
        }
    }

    public function actionGetformsettings(int $report_id): array
    {
        $this->response->format = Response::FORMAT_JSON;
        return [
            'allowDynamic' => ReportRepository::get($report_id)->allow_dynamicForm,
            'elements' => TemplateRepository::getAllow(
                reports: [$report_id => $report_id],
                groups: Yii::$app->getUser()->getIdentity()->groups
            )
        ];
    }

    public function actionGetdynamicformsettings(int $report_id): array
    {
        $groupsAllow = Yii::$app->getUser()->getIdentity()->groups;
        $groupsCanSent = GroupRepository::getAllBy(['id' => array_keys($groupsAllow), 'accept_send' => 1])->all();
        $groups = ArrayHelper::map($groupsCanSent, 'id', 'name');

        $mergeConstantAndRules = ArrayHelper::merge(
            ConstantRepository::getAllow(reports: [$report_id => $report_id], groups: $groupsAllow),
            ConstantruleRepository::getAllow(reports: [$report_id => $report_id], groups: $groupsAllow)
        );

        $this->response->format = Response::FORMAT_JSON;
        return compact('mergeConstantAndRules', 'groups');
    }

    public function actionGetperiod(int $template_id): array
    {
        $template = TemplateRepository::get($template_id);

        $this->response->format = Response::FORMAT_JSON;
        if ($template) {
            return $template->toArray(['form_datetime']);
        }

        return [];
    }
}
