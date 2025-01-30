<?php if (Yii::$app->settings->get('template', 'footer_enable') ): ?>
    <footer id="footer" class="mt-auto py-3 bg-white">
        <div class="container">
            <div class="row text-muted">
                <div class="col-12 text-center">
                    <?php
                        if (Yii::$app->settings->get('template', 'footer_name')) {
                            echo '&copy; ' . Yii::$app->settings->get('template', 'footer_name');
                        }

                        if (Yii::$app->settings->get('template', 'footer_year')) {
                            echo '&nbsp; ' . date('Y');
                        }
                    ?>
                </div>
            </div>
        </div>
    </footer>
<?php endif;
