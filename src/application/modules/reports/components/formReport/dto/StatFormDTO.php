<?php

namespace app\modules\reports\components\formReport\dto;

/**
 * @property int $report
 * @property int $template
 * @property string $period
 * @property int|null $dynamic_form_type
 * @property array|null $dynamic_row
 * @property array|null $dynamic_column
 * @property int|null $dynamic_use_appg
 * @property int|null $dynamic_use_jobs
 * @property int|null $dynamic_use_grouptype
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\formReport\dto
 */
final class StatFormDTO
{
    public $report;
    public $template;
    public $period;
    public $dynamic_form_type;
    public $dynamic_form_column;
    public $dynamic_form_row;
    public $dynamic_use_appg;
    public $dynamic_use_jobs;
    public $dynamic_use_grouptype;

    public function __construct(array $attributes) {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}