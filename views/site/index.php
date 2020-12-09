<?php

use yii\bootstrap\Html;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $date string */
/* @var $data float */
/* @var $lastDays array */
$this->title = 'Weather App';
?>
<div class="container">

    <?= Html::beginForm() ?>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-4 col-md-2">
                <?= MaskedInput::widget([
                    'name' => 'date',
                    'mask' => '9999-99-99',
                    'value' => $date
                ]); ?>
            </div>
            <div class="col-sm-4">
                <?= Html::submitButton('Показать') ?>
            </div>
        </div>
        <?php if(isset($data->result)) : ?>
            <div class="text-success" style="font-size: 22px;">
                Температура <b><?= $data->result[0] ?></b> градусов.
            </div>
        <?php elseif($data->error) : ?>
            <div class="text-warning">
                <?= $data->error->message ?>
            </div>
        <?php else : ?>
            <div class="text-primary">
                Температура на указанную дату отсутствует.
            </div>
        <?php endif; ?>
    <?= Html::endForm() ?>

    <?php if($lastDays) : ?>
        <?php if($lastDays->result) : ?>
            <h5 style="margin-top: 30px;">Температура за последние 30 дней</h5>
            <table class="table table-bordered table-hover">
                <?php foreach($lastDays->result as $lastDay) : ?>
                    <tr>
                        <td><?= $lastDay->date_at ?></td>
                        <td><?= $lastDay->temp ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif($lastDays->error) : ?>
            <div class="text-warning">
                <?= $data->error->message ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
