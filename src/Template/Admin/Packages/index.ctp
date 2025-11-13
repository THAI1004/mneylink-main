<?php

/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
?>
<?php
$this->assign('title', __('Packages'));
$this->assign('description', '');
$this->assign('content_title', __('Packages'));
?>

<?php $this->start('scriptTop'); ?>
<style>
    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-close {
        cursor: pointer;
    }

    .panel-close:hover {
        color: #d9534f;
    }
</style>
<?php $this->end(); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="package-wrap">

                        <?php $this->Form->unlockField('packages') ?>
                        <?= $this->Form->create('packages') ?>

                        <div class="package-items">
                            <?php if (!empty($packages)) : $packages = json_decode($packages);
                                foreach ($packages as $k => $package) : ?>
                                    <div class="panel panel-default item" data-number="<?= $k ?>">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?= $package->name ?></h4>
                                            <span class="panel-close">
                                                <i class="fa fa-trash"></i>
                                            </span>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 form-group">
                                                    <label class="control-label">Tiêu đề</label>
                                                    <input type="text"
                                                        name="packages[<?= $k ?>][name]"
                                                        class="form-control package-name"
                                                        required
                                                        value="<?= $package->name ?>">
                                                </div>
                                                <div class="col-lg-6 form-group">
                                                    <label class="control-label">Time</label>
                                                    <input type="number"
                                                        name="packages[<?= $k ?>][time]"
                                                        class="form-control"
                                                        required
                                                        value="<?= $package->time ?>">
                                                </div>
                                                <div class="col-lg-6 form-group">
                                                    <label class="control-label">Số tiền/view</label>
                                                    <input type="number"
                                                        name="packages[<?= $k ?>][price]"
                                                        class="form-control"
                                                        required
                                                        value="<?= $package->price ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php endforeach;
                            endif; ?>
                        </div>

                        <div class="btn-group" role="group">
                            <?= $this->Form->button('<i class="fa fa-plus"></i> Add Item', [
                                'type' => 'button',
                                'class' => 'btn btn-primary add-item'
                            ]) ?>

                            <?= $this->Form->button('<i class="fa fa-save"></i> Submit', [
                                'class' => 'btn btn-success'
                            ]) ?>
                        </div>

                        <?= $this->Form->end() ?>

                        <div class="package-more hide">
                            <div class="panel panel-default item" data-number="__number__">
                                <div class="panel-heading">
                                    <div></div>
                                    <span class="panel-close text-red btn btn-danger">
                                        <i class="fa fa-trash"></i> Xóa
                                    </span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label class="control-label">Tiêu đề</label>
                                            <input type="text"
                                                __name__="packages[__number__][name]"
                                                class="form-control package-name"
                                                required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label class="control-label">Time</label>
                                            <input type="number"
                                                __name__="packages[__number__][time]"
                                                class="form-control"
                                                required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label class="control-label">Số tiền/view</label>
                                            <input type="number"
                                                __name__="packages[__number__][price]"
                                                class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        let stt = $('.package-items .item').length;

        $('.add-item').click(function() {
            let template = $('.package-more').html();
            template = template.replace(/__name__/g, 'name');
            template = template.replace(/__number__/g, stt);
            $('.package-items').append(template);
            stt++;
        })

        $(document).on('click', '.panel-close', function() {
            $(this).closest('.item').remove();
            $('.package-items .item').each(function(i) {
                $(this).find('')
            })
        })

        $(document).on('keyup', '.package-name', function() {
            $(this).closest('.item').find('.panel-title').text($(this).val());
        })
    })
</script>
<?php $this->end(); ?>