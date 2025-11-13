<?php

/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
?>
<?php
$this->assign('title', __('Top Member List'));
$this->assign('description', '');
$this->assign('content_title', __('Top Member List'));
?>

<?php $this->start('scriptTop'); ?>
<style>
    .panel-heading {
        display: flex;
        justify-content: space-between;
    }

    .panel-close {
        cursor: pointer;
    }

    .panel-close:hover {
        color: red;
    }
</style>
<?php $this->end(); ?>

<div class="box box-primary">
    <div class="box-body">
        <div class="package-wrap">

            <?php $this->Form->unlockField('topMemberList') ?>
            <?= $this->Form->create('topMemberList') ?>
            <div class="package-items">

                <?php if (!empty($topMemberList)) : $topMemberList = json_decode($topMemberList);
                    foreach ($topMemberList as $k => $package) :
                ?>
                        <div class="panel panel-default item" data-number="<?= $k ?>">
                            <div class="panel-heading">
                                <div class="panel-title">View: <?= $package->view ?></div>
                                <div class="panel-close text-red btn btn-danger"><i class="fa fa-trash"></i> Xóa</div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12" style="margin-bottom: 15px">
                                        <label for="">View</label>
                                        <input type="number" name="topMemberList[<?= $k ?>][view]" class="form-control package-name" required value="<?= $package->view ?>">
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="">Thưởng</label>
                                        <input type="number" name="topMemberList[<?= $k ?>][bonus]" class="form-control" value="<?= $package->bonus ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php endforeach;
                endif; ?>
            </div>

            <?= $this->Form->button('<i class="fa fa-plus"></i> Add Item', [
                'type' => 'button',
                'class' => 'btn btn-primary add-item'
            ]) ?>

            <?= $this->Form->button('Submit', [
                'class' => 'btn btn-success'
            ]) ?>

            <?= $this->Form->end() ?>
            <div class="package-more hide">
                <div class="panel panel-default item" data-number="__number__">
                    <div class="panel-heading">
                        <div></div>
                        <div class="panel-close text-red btn btn-danger"><i class="fa fa-trash"></i> Xóa</div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12" style="margin-bottom: 15px">
                                <label for="">View</label>
                                <input type="number" __name__="topMemberList[__number__][view]" class="form-control package-name" required>
                            </div>
                            <div class="col-lg-12">
                                <label for="">Thưởng</label>
                                <input type="number" __name__="topMemberList[__number__][bonus]" class="form-control">
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
            $(this).closest('.item').find('.panel-title').text(`View: ${$(this).val()}`);
        })
    })
</script>
<?php $this->end(); ?>