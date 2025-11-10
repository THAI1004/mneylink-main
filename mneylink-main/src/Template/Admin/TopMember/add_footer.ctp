<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
?>
<?php
$this->assign('title', __('Add Footer'));
$this->assign('description', '');
$this->assign('content_title', __('Add Footer'));
?>

<?php $this->start('scriptTop'); ?>
<style>
    .panel-heading{
        display: flex;
        justify-content: space-between;
    }
    .panel-close{
        cursor: pointer;
    }
    .panel-close:hover{
        color: red;
    }
</style>
<?php $this->end(); ?>

<div class="box box-primary">
    <div class="box-body">
        <?=$this->Form->create('add_footer',[
            'method' => 'post',

        ])?>
        <?=$this->Form->control('add_footer',[
            'label' => 'Add Footer',
            'type' => 'textarea',
            'class' => 'form-control text-editor',
            'value' => get_option('addFooter')
        ])?>
        <?=$this->Form->submit('submit')?>
        <?=$this->Form->end()?>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replaceClass = 'text-editor';
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
        CKEDITOR.dtd.$removeEmpty['i'] = false;
    });
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
