<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
?>
<?php
$this->assign('title', __('Cài đặt Thông báo'));
$this->assign('description', '');
$this->assign('content_title', __('Cài đặt Thông báo'));
$page_list = [];
if (!empty($pages)){
    foreach ($pages as $page){
        $page_list[$page->id] = $page->title;
    }
}
?>

<?php $this->start('scriptTop'); ?>

<?php $this->end(); ?>

<div class="box box-primary">
    <div class="box-body">
        <div class="package-wrap">
            <?=$this->Form->create('notification')?>

            <?=$this->Form->control('page',[
                'label' => 'Trang thông báo',
                'class' => 'form-control',
                'type' => 'select',
                'options' => $page_list,
                'value' => get_option('buyer_page')
            ])?>

            <?=$this->Form->control('ember_code',[
                'label' => 'Ember Code',
                'class' => 'form-control',
                'type' => 'textarea',
                'value' => get_option('ember_code')
            ])?>

            <?=$this->Form->control('nf_1',[
                'label' => 'Thông báo 1',
                'class' => 'form-control text-editor',
                'type' => 'textarea',
                'value' => get_option('nf_1')
            ])?>

            <?=$this->Form->control('nf_2',[
                'label' => 'Thông báo 2',
                'class' => 'form-control text-editor',
                'type' => 'textarea',
                'value' => get_option('nf_2')
            ])?>

            <?=$this->Form->control('info_banking',[
                'label' => 'Thông tin chuyển khoản',
                'class' => 'form-control text-editor',
                'type' => 'textarea',
                'value' => get_option('info_banking')
            ])?>

            <?=$this->Form->button('Cập nhật',[
                'class' => 'btn btn-primary btn-lg'
            ])?>

            <?=$this->Form->end()?>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
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
<script type="text/javascript">
    $(document).ready(function(){
        let stt = $('.package-items .item').length;
        $('.add-item').click(function (){
            let template = $('.package-more').html();
            template = template.replace(/__name__/g,'name');
            template = template.replace(/__number__/g,stt);
            $('.package-items').append(template);
            stt++;
        })
        $(document).on('click','.panel-close',function (){
            $(this).closest('.item').remove();
            $('.package-items .item').each(function (i){
                $(this).find('')
            })
        })
        $(document).on('keyup','.package-name',function (){
            $(this).closest('.item').find('.panel-title').text($(this).val());
        })
    })
</script>
<?php $this->end(); ?>
