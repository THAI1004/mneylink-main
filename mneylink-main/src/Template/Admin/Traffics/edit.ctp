<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Traffic $traffic
 * @var mixed $plans
 */
$this->assign('title', __('Edit Campaign Traffic'));
$this->assign('description', '');
$this->assign('content_title', __('Edit Campaign Traffic'));
?>

<?php $this->start('scriptTop'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        <?php $this->Form->unlockField('traffic_ver2_url') ?>

        <?= $this->Form->create($traffic); ?>

        <?=
        $this->Form->control('title', [
            'label' => __('Tên chiến dịch'),
            'class' => 'form-control',
            'placeholder' => 'Tên chiến dịch'
        ])
        ?>
        <?=
        $this->Form->control('url', [
            'label' => __('URL chiến dịch'),
            'class' => 'form-control',
            'placeholder' => 'URL chiến dịch'
        ])
        ?>
        
        <div class="row">
            <div class="col-sm-6">
                <?=
                    $this->Form->control('count_day', [
                        'label' => __('Số lượng mỗi ngày'),
                        'class' => 'form-control',
                        'type' => 'number',
                        'placeholder' => 'Số lượng traffic chạy mỗi ngày'
                    ])
                ?>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                                    <?=
                    $this->Form->control('count', [
                        'label' => __('Tổng'),
                        'class' => 'form-control',
                        'type' => 'number',
                        'placeholder' => 'Số lượng tổng chạy'
                    ])
                ?>
                </div>
            </div>
        </div>
        <?=
            $this->Form->control('keywords', [
                'label' => __('Từ khóa'),
                'class' => 'form-control',
                'type' => 'textarea',
                'placeholder' => 'Mỗi từ khóa trên một dòng!'
            ]);
        ?>
        <?=
        $this->Form->control('img_mobile', [
            'label' => __('Hình ảnh trên Mobile'),
            'class' => 'form-control',
            'type' => 'url',
            'placeholder' => 'URL hình ảnh hiển thị thiết bị Mobile/Tablet!'
        ])
        ?>
        <?=
        $this->Form->control('img_desktop', [
            'label' => __('Hình ảnh Desktop'),
            'class' => 'form-control',
            'type' => 'url',
            'placeholder' => 'URL hình ảnh hiển thị thiết bị máy tính!'
        ])
        ?>
        <?=
            $this->Form->control('content', [
                'label' => __('Content'),
                'class' => 'form-control text-editor',
                'type' => 'textarea',
            ]);
        ?>

        <?=
            $this->Form->control('status', [
                'label' => __('Trạng thái'),
                'options' => [
                    0 => __('Tạm dừng'),
                    1 => __('Đang chạy'),
                    2 => __('Hoàn thành'),
                    3 => __('Kết thúc'),
                ],
                'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('copy', [
            'label' => __('Cho phép copy'),
            'type' => 'checkbox',
        ]);
        ?>
        <hr/>
        <label style="margin-bottom: 0">Tùy chọn cho camp ngoại</label>
        <?=
        $this->Form->control('foreign_camp', [
            'label' => __('Camp ngoại'),
            'type' => 'checkbox',
        ]);
        ?>

        <?=
        $this->Form->control('except_region', [
            'label' => __('Ngoại trừ vùng'),
            'type' => 'select',
            'class' => 'except_region form-control',
            'options' => get_countries(),
            'multiple' => 'multiple',
            'value' => json_decode($traffic->except_region)
        ]);
        ?>
        <?=
        $this->Form->control('only_region', [
            'label' => __('Chỉ định vùng'),
            'type' => 'select',
            'class' => 'only_region form-control',
            'options' => get_countries(),
            'multiple' => 'multiple',
            'value' => json_decode($traffic->only_region)
        ]);
        ?>
        <hr/>
        <label style="margin-bottom: 0">Traffic Version 2</label>
        <?=
        $this->Form->control('traffic_ver2', [
            'label' => __('Enable Traffic version 2'),
            'type' => 'checkbox',
        ]);
        ?>

        <div class="url-list">
            <?php if(!empty($traffic->traffic_ver2_url) && is_array(json_decode($traffic->traffic_ver2_url))) : ?>
                <?php foreach (json_decode($traffic->traffic_ver2_url) as $k => $list) : ?>
                    <div class="url-item" data-number="<?=$k?>">
                        <div class="url-action"><i class="fa fa-trash"></i></div>
                        <div class="url-input"><input type="url" name="traffic_ver2_url[]" class="form-control" value="<?=$list?>" placeholder="Url"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= $this->Form->button(__('Add URL'), [
            'class' => 'btn btn-primary add-url',
            'type' => 'button'
        ]); ?>
        <hr/>
        <?=
        $this->Form->control('device', [
            'label' => __('Thiết bị'),
            'options' => [
                0 => __('Tất cả'),
                1 => __('Chỉ PC'),
                2 => __('Chỉ Mobile')
            ],
            'class' => 'form-control',
        ]);
        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
<?php $this->start('scriptBottom'); ?>

<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    CKEDITOR.replaceClass = 'text-editor';
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.dtd.$removeEmpty['span'] = false;
    CKEDITOR.dtd.$removeEmpty['i'] = false;
  });
</script>
<script>
    $(document).ready(function() {
        $('.except_region, .only_region').select2({
            theme: 'classic'
        });
    });
</script>
<script type="text/javascript">
    $('.add-url').click(function (){
        let list = $('.url-list');
        let item = list.find('.url-item');
        let html = ` <div class="url-item" data-number="${item.length}">
                        <div class="url-action"><i class="fa fa-trash"></i></div>
                        <div class="url-input"><input type="url" name="traffic_ver2_url[]" class="form-control" value="" placeholder="Url"></div>
                     </div>`;
        list.append(html);
    })
    $(document).on('click','.url-action',function (){
        let $this = $(this);
        $this.closest('.url-item').remove();
        $('.url-list .url-item').each(function (index,item){
            $(this).attr('data-number',index);
        })
    });
</script>

<?php $this->end(); ?>
