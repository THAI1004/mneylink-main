<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statistic $first_statistic
 * @var mixed $campaign_countries
 * @var mixed $campaign_earnings
 * @var mixed $campaign_referers
 */
$this->assign('title', __('Yêu cầu cập nhật'));
$this->assign('description', '');
$this->assign('content_title', __('Yêu cầu cập nhật'));

$buyerReport = (!empty($buyer)) ? $buyer : [];
$traffic_id = $buyerReport->traffic_id;
$buyer_edit = json_decode($buyerReport->content);
$traffic = (!empty($traffic)) ? $traffic : [];
$status = [
    'false'  => 'Lựa chọn',
    '0' => 'Tạm dừng',
    '1' => 'Hoạt động'
];
?>

<?php $this->start('scriptTop'); ?>
<style>
    .url-list{
        margin-bottom: 15px;
    }
    .url-list .url-item{
        display: flex;
        align-items: center;
        border: 1px solid #d2d6de;
        margin-bottom: 10px;
    }

    .url-list .url-item .url-action{
        width: 36px;
        text-align: center;
        font-size: 20px;
        height: 36px;
        line-height: 36px;
        color: red;
        border-right: 1px solid #d2d6de;
        cursor: pointer;
    }
    .url-list .url-item .url-action:hover{
        background: #ebe7e7;
    }
    .url-list .url-item .url-input{
        flex: 1;
    }
    .url-list .url-item .url-input input{
        border: none;
        height: 36px;
        width: 100%;
    }
</style>
<?php $this->end(); ?>

<div class="box box-primary">
    <div class="box-body">
        <?php if (!$traffic->buyer_invoice->status) : ?>
        <div class="alert alert-danger" role="alert">
            User Buyer này chưa thanh toán hóa đơn traffic <?=$traffic->id?>, nên status sẽ không hoạt động
        </div>
        <?php endif; ?>

        <?=$this->Form->unlockField('traffic_ver2_url')?>
        <?=$this->Form->create('report')?>

        <?=$this->Form->control('keywords',[
            'label' => 'Keywords',
            'type' => 'text',
            'class' => 'form-control',
            'placeHolder' => 'Keywords',
            'value' => $buyer_edit->keywords
        ])?>

        <?=$this->Form->control('url',[
            'label' => 'Url',
            'type' => 'text',
            'class' => 'form-control',
            'placeHolder' => 'Url',
            'value' => $buyer_edit->url
        ])?>

        <?=$this->Form->control('count_day',[
            'label' => 'Xem mỗi ngày',
            'type' => 'number',
            'class' => 'form-control',
            'placeHolder' => 'Xem mỗi ngày',
            'value' => $buyer_edit->count_day
        ])?>

        <?=$this->Form->control('image_url',[
            'label' => 'Hình ảnh',
            'type' => 'text',
            'class' => 'form-control',
            'placeHolder' => 'Hình ảnh',
            'value' => $buyer_edit->image_url
        ])?>

        <?=
        $this->Form->control('description', [
            'label' => __('Mô Tả'),
            'class' => 'form-control text-editor',
            'type' => 'textarea',
            'value' => $buyer_edit->description
        ]);
        ?>

        <label><?=__('Traffic2 Url')?></label>
        <div class="url-list">
            <?php if(!empty($buyer_edit->traffic2_url)) : ?>
                <?php foreach ($buyer_edit->traffic2_url as $k => $list) : ?>
                    <div class="url-item" data-number="<?=$k?>">
                        <div class="url-action"><i class="fa fa-trash"></i></div>
                        <div class="url-input"><input type="url" name="traffic_ver2_url[]" class="form-control" value="<?=$list?>" placeholder="Url"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($traffic->buyer_invoice->status) : ?>
            <td>
                <?= $this->Form->control("status",[
                    'label' => false,
                    'type' => 'select',
                    'class' => 'form-control',
                    'options' => $status,
                    'value' => $buyer_edit->status
                ]); ?>
            </td>
        <?php endif; ?>

        <?=$this->Form->button('Phê Duyệt',[
            'class' => 'btn btn-primary btn-lg'
        ])?>

        <?=$this->Form->end()?>
    </div><!-- /.box-body -->
</div>

<?php $this->start('scriptBottom') ?>
<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>
<script>
    $(document).ready(function () {
        CKEDITOR.replaceClass = 'text-editor';
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
        CKEDITOR.dtd.$removeEmpty['i'] = false;
    });
</script>
<?php $this->end() ?>
