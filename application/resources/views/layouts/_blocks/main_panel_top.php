<?php

use yii\bootstrap5\Html;

?>

<nav class="navbar navbar-expand navbar-light navbar-bg p-2 ps-3 pe-3">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                <span class="nav-icon dropdown-toggle" role="button" data-bs-toggle="dropdown" id="personalMenu">
                    <div class="position-relative bi bi-person-circle"></div>
                </span>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="personalMenu">
                    <?php
                        echo Html::a(Html::tag('i', '', ['class' => 'align-middle me-1 bi bi-person']) . Yii::t('views', 'Профиль'), ['/profile'], ['class' => 'dropdown-item']);
                        echo Html::a(Html::tag('i', '', ['class' => 'me-1 bi bi-box-arrow-right']) . Yii::t('views', 'Выйти'), ['/logout'], ['class' => 'dropdown-item']);
                    ?>
                </div>
            </li>
        </ul>
    </div>
</nav>
