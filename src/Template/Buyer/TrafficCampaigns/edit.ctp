<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Sửa Quảng Cáo'));
$this->assign('description', '');
$this->assign('content_title', __('Sửa Quảng Cáo'));
$status = [
    'false' => 'Lựa chọn',
    '0' => 'Tạm dừng',
    '1' => 'Hoạt động'
];
?>
<?php $this->start('scriptTop'); ?>
    <style>
        .traffic2 .traffic2-wrap .url-item{
            display: flex;
            align-items: center;
        }
        .traffic2 .traffic2-wrap .url-item .traffic2-action{
            width: 34px;
            height: 34px;
            text-align: center;
            line-height: 34px;
            background: green;
            color: white;
            cursor: pointer;
        }
        .traffic2 .traffic2-wrap .url-item .traffic2-action.remove{
            background: red;
        }
        .traffic2 .traffic2-wrap .url-item .traffic2-action:hover{
            opacity: 0.7;
        }
        .traffic2 .traffic2-wrap .url-item .traffic2-input{
            flex: 1;
        }
    </style>
<?php $this->end(); ?>

    <div class="alert alert-danger" role="alert" style="display: none" id="campaign-alert-error">
        <i class="fa fa-exclamation-triangle"></i>
        Total views need to be &gt; 1000 views
    </div>

    <div class="box box-primary">
        <div class="box-body">

            <?php $this->Form->unlockField('edit') ?>
            <?php echo $this->Form->create('campaign-edit', [
                'id' => 'campaign-edit'
            ]) ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <tbody>
                    <tr>
                        <th style="min-width: 120px">Từ khóa</th>
                        <th style="min-width: 120px">Địa chỉ Trang web</th>
                        <th style="min-width: 120px">Xem mỗi ngày (view/day)</th>
                        <th style="min-width: 120px">Image URL</th>
<!--                        <th style="min-width: 120px">Traffic2 URL</th>-->
                        <?php if($traffic->buyer_invoice->status) : ?>
                        <th style="min-width: 120px">Trạng thái</th>
                        <?php endif; ?>
                    </tr>
                    </tbody>
                    <tbody id="list-campaign-keyword-table">
                    <tr>
                        <td>
                            <?= $this->Form->control("edit.keywords",[
                                'label' => false,
                                'type' => 'text',
                                'class' => 'form-control',
                                'placeholder' => 'Keywords',
                                'required' => true,
                                'value' => (!empty($traffic)) ? $traffic->keywords : ''
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("edit.url",[
                                'label' => false,
                                'type' => 'url',
                                'class' => 'form-control',
                                'placeholder' => 'Website URL',
                                'required' => true,
                                'value' => (!empty($traffic)) ? $traffic->url : ''
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("edit.count_day",[
                                'label' => false,
                                'type' => 'number',
                                'class' => 'form-control',
                                'placeholder' => 'Views',
                                'required' => true,
                                'value' => (!empty($traffic)) ? $traffic->count_day : ''
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("edit.image_url",[
                                'label' => false,
                                'type' => 'url',
                                'class' => 'form-control',
                                'placeholder' => 'Image url',
                                'required' => true,
                                'value' => (!empty($traffic)) ? $traffic->img_desktop : ''
                            ]); ?>
                        </td>
                        <!--<td class="traffic2">
                            <?php /*$urlList = (!empty($traffic->traffic_ver2_url)) ? json_decode($traffic->traffic_ver2_url) : []; */?>
                            <div class="traffic2-wrap">
                                <?php /*if(!empty($urlList)) : */?>
                                    <?php /*foreach ($urlList as $k => $list) : */?>
                                        <?php /*if(!$k) : */?>
                                            <div class="traffic2-mainlist url-item">
                                                <div class="traffic2-input">
                                                    <input type="url" class="form-control" name="edit[traffic2_url][]" value="<?/*=$list*/?>" placeholder="Url">
                                                </div>
                                                <div class="traffic2-action add" data-index="0"><i class="fa fa-plus"></i></div>
                                            </div>
                                        <?php /*endif; */?>
                                    <?php /*endforeach; */?>

                                    <div class="traffic2-sublist">
                                        <?php /*foreach ($urlList as $k => $list) : if($k) : */?>
                                            <div class="url-item">
                                                <div class="traffic2-input">
                                                    <input type="url" class="form-control" name="edit[traffic2_url][]" value="<?/*=$list*/?>" placeholder="Url">
                                                </div>
                                                <div class="traffic2-action remove" data-index="<?/*=$k*/?>"><i class="fa fa-trash"></i></div>
                                            </div>
                                        <?php /*endif; endforeach; */?>
                                    </div>
                                <?php /*endif; */?>

                            </div>
                        </td>-->
                        <?php if($traffic->buyer_invoice->status) : ?>
                            <td>
                                <?= $this->Form->control("edit.status",[
                                    'label' => false,
                                    'type' => 'select',
                                    'class' => 'form-control',
                                    'options' => $status
                                ]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                    </tbody>
                </table>
            </div>

            <?=
            $this->Form->control('edit.description', [
                'label' => __('Mô Tả'),
                'class' => 'form-control text-editor',
                'type' => 'textarea',
                'value' => (!empty($traffic)) ? $traffic->content : ''
            ]);
            ?>

            <?= $this->Form->button(__('Sửa Quảng Cáo'), [
                'class' => 'btn btn-success btn-lg'
            ]); ?>

            <?php echo $this->Form->end(); ?>
            </div><!-- /.box-body -->
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

        $(document).on('click','.traffic2-wrap .traffic2-action',function (){
            let $this = $(this);
            if ($this.hasClass('add')){
                let index = $this.attr('data-index');
                let html = `<div class='url-item'>
                            <div class="traffic2-input">
                                <input type="url" class="form-control" name="campaign_keywords[${index}][traffic2_url][]" value="" placeholder="Url">
                            </div>
                            <div class="traffic2-action remove"><i class="fa fa-trash"></i></div>
                        </div>`;
                $this.closest('.traffic2-wrap').find('.traffic2-sublist').append(html);
            } else {
                $this.closest('.url-item').remove();
            }
        })
    </script>
<?php $this->end();
