<?php

namespace app\modules\reports\controllers;

use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;

use app\components\{
    base\BaseController,
    attachedFiles\AttachFileActionsTrait
};
use app\modules\reports\{
    components\formReport\FormFactory,
    repositories\TemplateRepository,
    search\JobSearch,
    forms\StatisticForm,
};
use app\modules\users\{
    components\rbac\items\Permissions,
    components\rbac\RbacHelper,
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

    public function actionGettemplates(int $report_id): array
    {
        $this->response->format = Response::FORMAT_JSON;
        $groups = RbacHelper::getAllowGroupsArray('constantRule.list.all');

        return ['elements' => TemplateRepository::getAllow(
            reports: [$report_id => $report_id],
            groups: $groups
        )];
    }

    public function actionGetperiod(int $template_id): array
    {
        $this->response->format = Response::FORMAT_JSON;

        $template = TemplateRepository::get($template_id);
        if ($template) {
            return $template->toArray(['form_datetime']);
        }

        return [];
    }
}
