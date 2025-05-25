<?php

namespace app\modules\reports\components\formReport\dto;

/**
 * @property string $name
 * @property int $report_id
 * @property int $form_datetime
 * @property int $form_type
 * @property int $form_usejobs
 * @property int|null $use_appg
 * @property int|null $use_grouptype
 * @property int|null $table_type
 * @property string|null $table_rows
 * @property string|null $table_columns
 * @property int $limit_maxfiles
 * @property int $limit_maxsavetime
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\formReport\dto
 */
final class TemplateDTO
{
    public string $name;
    public int $report_id;
    public int $form_datetime;
    public int $form_type;
    public int|null $form_usejobs;
    public int|null $use_appg;
    public int|null $use_grouptype;
    public int|null $table_type;
    public array|string|null $table_rows = null;
    public array|string|null $table_columns = null;
    public int $limit_maxfiles = 0;
    public int $limit_maxsavetime = 0;

    public function __construct(array $attributes) {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}