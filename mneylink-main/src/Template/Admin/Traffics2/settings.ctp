<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Option $option
 */
$this->assign('title', __('Cài đặt traffics'));
$this->assign('description', '');
$this->assign('content_title', __('Cài đặt traffics'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create(); ?>

        <?=
        $this->Form->control('traffic_default_time', [
            'label' => __('Thời gian mặc định của traffic'),
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => 'number',
            'value' => $traffics_settings ?? 60
        ]);
        ?>

        <?=
        $this->Form->control('traffic2_default_time', [
            'label' => __('Thời gian mặc định của traffic2'),
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => 'number',
            'value' => $traffics2_settings ?? 90
        ]);
        ?>

        <?=
        $this->Form->control('traffic2_url_default_time', [
            'label' => __('Thời gian mặc định của traffic2 Url 2'),
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => 'number',
            'value' => $traffics2_url_settings ?? 10
        ]);
        ?>

        <?= $this->Form->button(__('Save'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>

    </div><!-- /.box-body -->
</div>

<?php $this->start('scriptBottom'); ?>

<?php $this->end(); ?>
