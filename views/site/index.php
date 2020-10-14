<?php

use yii\helpers\Html;

$this->title = 'My Yii Application';
?>

<div class="site-index">

    <?= Html::a('Contact Sales',['/call/connect'], [
        'onclick'=>"
            $.ajax({
                type     :'POST',
                cache    : false,
                url  : '/call/connect',
                success  : function(response) {
                    console.log(response);
                }
            });
            return false;
        ",
    ]); ?>

    <br>

    <?= Html::a('Make call to +79142736836',['/call/make', 'to' => '+79142736836'], [
        'onclick'=>"
            $.ajax({
                type     :'POST',
                cache    : false,
                url  : '/call/make?to=89142736836',
                success  : function(response) {
                    console.log(response);
                }
            });
            return false;
        ",
    ]); ?>
</div>

<?php

$script = <<< JS

    $('#contactForm').on('submit', function(e) {
        // Prevent submit event from bubbling and automatically submitting the
        // form
        e.preventDefault();

        // Call our ajax endpoint on the server to initialize the phone call
        $.ajax({
            url: '/call/connect',
            method: 'POST',
        }).done(function(data) {
            // The JSON sent back from the server will contain a success message
            alert(data.message);
        }).fail(function(error) {
            alert(JSON.stringify(error));
        });
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>