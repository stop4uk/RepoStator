<?php

namespace app\controllers;

use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;

use app\base\BaseController;
use app\repositories\report\TemplateRepository;
use app\search\report\JobSearch;
use app\factories\FormTemplateFactory;
use app\forms\StatisticForm;
use app\helpers\RbacHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers
 */
final class StatisticController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['statistic'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $form = new StatisticForm();
        $searchModel = new JobSearch([
            'onlyMain' => true
        ]);

        $dataProvider = $searchModel->search($this->request->post());

        return $this->render('index', compact('form', 'searchModel', 'dataProvider'));
    }

    public function actionForm()
    {
        $form = new StatisticForm();
        if ( $this->request->isAjax && $form->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ( $form->load($this->request->post()) && $form->validate() ) {
            $factory = FormTemplateFactory::process($form);
            $factory->run();
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
        if ( $template ) {
            return $template->toArray(['form_datetime']);
        }

        return [];
    }
}
