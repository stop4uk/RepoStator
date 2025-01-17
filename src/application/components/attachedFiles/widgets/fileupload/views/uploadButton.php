<?php

/**
 * @var \dosamigos\fileupload\FileUpload $this
 * @var string $input the code for the input
 * @var string $buttonName
 * @var string $buttonOptions
 */

?>

<span class="dropdown-item fileinput-button">
   <span class="<?= $buttonOptions ?>"><?= $buttonName ?: Yii::t('fileupload', 'Select file...') ?></span>
   <?= $input ?>
</span>
