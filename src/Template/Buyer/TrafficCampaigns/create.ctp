<?php

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Tạo Quảng Cáo'));
$this->assign('description', '');
$this->assign('content_title', __('Tạo Quảng Cáo'));

$package_list = [];
if (!empty(packages())) {
    foreach (packages() as $k => $item) {
        $package_list[$k] = $item->name;
    }
}
?>

<?php $this->start('scriptTop'); ?>
<style>
    .traffic2 .traffic2-wrap .url-item {
        display: flex;
        align-items: center;
    }

    .traffic2 .traffic2-wrap .url-item .traffic2-action {
        width: 34px;
        height: 34px;
        text-align: center;
        line-height: 34px;
        background: green;
        color: white;
        cursor: pointer;
    }

    .traffic2 .traffic2-wrap .url-item .traffic2-action.remove {
        background: red;
    }

    .traffic2 .traffic2-wrap .url-item .traffic2-action:hover {
        opacity: 0.7;
    }

    .traffic2 .traffic2-wrap .url-item .traffic2-input {
        flex: 1;
    }

    /* Fix CKEditor display in new template */
    .cke {
        visibility: visible !important;
        display: block !important;
        z-index: 1 !important;
    }

    .cke_chrome {
        border: 1px solid #d2d6de !important;
        visibility: visible !important;
    }

    .cke_inner {
        display: block !important;
        visibility: visible !important;
    }

    .cke_contents {
        min-height: 200px !important;
        visibility: visible !important;
    }

    .cke_top {
        background: #f4f4f4 !important;
        border-bottom: 1px solid #d2d6de !important;
        visibility: visible !important;
    }

    /* Ensure textarea is replaced */
    .text-editor {
        min-height: 200px;
    }

    /* Fix CKEditor toolbar */
    .cke_toolbar {
        visibility: visible !important;
    }

    .cke_button {
        visibility: visible !important;
    }
</style>
<?php $this->end(); ?>

<div class="alert alert-danger" role="alert" style="display: none" id="campaign-alert-error">
    <i class="fa fa-exclamation-triangle"></i>
    Total views need to be &gt; 1000 views
</div>

<div class="box box-primary">
    <div class="box-body">

        <div class="form-group">
            <label>Embed code</label>
            <pre><?= htmlentities(get_option('ember_code')) ?></pre>
        </div>

        <?php $this->Form->unlockField('campaign_keywords') ?>

        <?php echo $this->Form->create('campaign-create', [
            'id' => 'campaign-create'
        ]) ?>

        <div style="display: flex; align-items: center; margin-bottom: 15px">
            <label class="btn btn-primary">
                Import CSV
                <input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="display: none" id="import-csv">
            </label>

            <a href="/import-keywords.csv" download="import-keywords.csv" style="margin-left: 15px">
                <i>Tải file mẫu</i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tbody>
                    <tr>
                        <th style="width: 150px">Package</th>
                        <th style="width: 200px">Loại</th>
                        <th style="width: 200px">Từ khóa</th>
                        <th style="min-width: 120px">Địa chỉ Trang web</th>
                        <th style="width: 100px">Xem mỗi ngày (view/day)</th>
                        <th style="width: 100px">Tổng xem</th>
                        <th style="min-width: 120px">Image URL</th>
                        <!--                        <th class="traffic2" style="min-width: 120px">Traffic 2 URL</th>-->
                        <th class="text-center">
                            <i class="fa fa-plus-circle text-primary add-campaign-keyword-button"
                                style="cursor: pointer"></i>
                        </th>
                    </tr>
                </tbody>
                <tbody id="list-campaign-keyword-table">
                    <tr>
                        <td>
                            <?= $this->Form->control("campaign_keywords.0.package", [
                                'label' => false,
                                'type' => 'select',
                                'class' => 'form-control package',
                                'required' => true,
                                'options' => $package_list
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("campaign_keywords.0.kind", [
                                'label' => false,
                                'type' => 'select',
                                'class' => 'form-control kind',
                                'required' => true,
                                'options' => ["google" => "google", "direct" => "direct"]
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("campaign_keywords.0.keyword", [
                                'label' => false,
                                'type' => 'text',
                                'class' => 'form-control',
                                'placeholder' => 'Keywords',
                                'required' => true
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("campaign_keywords.0.url", [
                                'label' => false,
                                'type' => 'url',
                                'class' => 'form-control',
                                'placeholder' => 'Website URL',
                                'required' => true
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("campaign_keywords.0.view_per_day", [
                                'label' => false,
                                'type' => 'number',
                                'class' => 'form-control',
                                'placeholder' => 'Views',
                                'required' => true
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("campaign_keywords.0.total_views", [
                                'label' => false,
                                'type' => 'number',
                                'class' => 'form-control keyword-total-views',
                                'placeholder' => 'Views',
                                'required' => true
                            ]); ?>
                        </td>
                        <td>
                            <?= $this->Form->control("campaign_keywords.0.image_url", [
                                'label' => false,
                                'type' => 'url',
                                'class' => 'form-control',
                                'placeholder' => 'Image url',
                                'required' => true
                            ]); ?>
                        </td>
                        <!--<td class="traffic2">
                            <div class="traffic2-wrap">
                                <div class="traffic2-mainlist url-item">
                                    <div class="traffic2-input">
                                        <input type="url" class="form-control" name="campaign_keywords[0][traffic2_url][]" value="" placeholder="Url">
                                    </div>
                                    <div class="traffic2-action add" data-index="0"><i class="fa fa-plus"></i></div>
                                </div>
                                <div class="traffic2-sublist"></div>
                            </div>
                        </td>-->
                        <td class="text-center">
                            <i class="fa fa-minus-circle text-danger remove-campaign-keyword-button"
                                style="cursor: pointer"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tổng xem</label>
                    <input type="text" id="total-views" class="form-control" disabled="" value="0" data-value="0">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tổng tiền</label>
                    <?=
                    $this->Form->control('total_amount', [
                        'label' => false,
                        'class' => 'form-control',
                        'readonly' => true,
                        'type' => 'text',
                        'value' => 0
                    ])
                    ?>
                </div>
            </div>
        </div>

        <?=
        $this->Form->control('description', [
            'label' => __('Mô Tả'),
            'class' => 'form-control text-editor',
            'type' => 'textarea',
        ]);
        ?>

        <?= $this->Form->button(__('Tạo Quảng Cáo'), [
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
    $(document).ready(function() {
        // Wait for CKEditor to fully load
        if (typeof CKEDITOR !== 'undefined') {
            // Replace all textareas with class 'text-editor'
            CKEDITOR.replaceClass = 'text-editor';

            // Configure CKEditor
            CKEDITOR.config.allowedContent = true;
            CKEDITOR.dtd.$removeEmpty['span'] = false;
            CKEDITOR.dtd.$removeEmpty['i'] = false;

            // Additional configuration for better display
            CKEDITOR.config.height = 300;
            CKEDITOR.config.resize_enabled = true;

            // Force replace textareas
            $('.text-editor').each(function() {
                var textarea = $(this);
                if (textarea.length && !textarea.hasClass('cke_editable')) {
                    try {
                        CKEDITOR.replace(textarea.attr('id') || textarea.attr('name'), {
                            height: 300,
                            allowedContent: true
                        });
                    } catch (e) {
                        console.log('CKEditor init error:', e);
                    }
                }
            });

            console.log('CKEditor initialized successfully');
        } else {
            console.error('CKEditor not loaded');
        }
    });
</script>
<script>
    $(document).ready(function() {
        $("#import-csv").on('input', function() {
            var files = $(this).prop('files')
            if (files.length) {
                var file = files[0];
                var reader = new FileReader();
                reader.addEventListener('loadstart', function() {
                    console.log('File reading started');
                });
                reader.addEventListener('load', function(e) {
                    var text = e.target.result;
                    var array = $.csv.toArrays(text);
                    array.map((item, index) => {
                        if (index && item.length >= 5) {
                            const index = $('#list-campaign-keyword-table').find('tr').length
                            $('#list-campaign-keyword-table').append(`<tr>
                                <td><input type="text" class="form-control" name="campaign_keywords[${index}][keyword]" placeholder="Keywords" required value="${item[0]}"></td>
                                <td><input type="url" class="form-control" name="campaign_keywords[${index}][url]" placeholder="Website URL" required value="${item[1]}"></td>
                                <td><input type="number" min="0" class="form-control keyword-view-per-day" name="campaign_keywords[${index}][view_per_day]" placeholder="Views" required value="${item[2]}"></td>
                                <td><input type="number" min="0" class="form-control keyword-total-views" name="campaign_keywords[${index}][total_views]" placeholder="Views" required value="${item[3]}"></td>
                                <td><input type="text" class="form-control" name="campaign_keywords[${index}][image_url]" placeholder="Image url" value="${item[4]}"></td>
                                <td><input type="text" class="form-control" name="campaign_keywords[${index}][traffic2_url]" placeholder="Traffic2 url" value="${item[5]}"></td>
                                <td class="text-center"><i class="fa fa-minus-circle text-danger remove-campaign-keyword-button" style="cursor: pointer"></i></td>
                            </tr>`)
                        }
                    })
                    calculateTotalAmount()
                })
                reader.readAsText(file);
                $(this).val('')
            }
        });
    })
</script>
<script>
    var trafficPackages = <?= json_encode(packages()) ?>;

    function calculateTotalAmount(packageId = 1) {
        let totalViews = 0;
        $('#list-campaign-keyword-table tr').each(function() {
            let package_id = $(this).find('.package').val();
            let view = ($(this).find('.keyword-total-views').val() !== '') ? $(this).find('.keyword-total-views').val() : 0;
            let getPackage = trafficPackages[package_id];
            totalViews += (parseInt(view) * parseInt(getPackage.price));
        })
        $('#total-views').val(totalViews.toLocaleString())
        $('#total-views').attr('data-value', totalViews)
        $('#total-amount').val((totalViews).toLocaleString())
    }

    $(document).ready(function() {
        $('.add-campaign-keyword-button').click(function() {
            let selectList = '';
            for (let item in trafficPackages) {
                selectList += `<option value="${item}">${trafficPackages[item].name}</option>`;
            }

            const index = $('#list-campaign-keyword-table').find('tr').length
            $('#list-campaign-keyword-table').append(`<tr>
                    <td><div class="form-group select  required"><select name="campaign_keywords[${index}][package]" class="form-control package" required="required" id="campaign-keywords-0-package">${selectList}</select><span class="help-block"></span></div></td>
                    <td><input type="text" class="form-control" name="campaign_keywords[${index}][keyword]" placeholder="Keywords" required></td>
                    <td><input type="url" class="form-control" name="campaign_keywords[${index}][url]" placeholder="Website URL" required></td>
                    <td><input type="number" min="0" class="form-control keyword-view-per-day" name="campaign_keywords[${index}][view_per_day]" placeholder="Views" required></td>
                    <td><input type="number" min="0" class="form-control keyword-total-views" name="campaign_keywords[${index}][total_views]" placeholder="Views" required></td>
                    <td><input type="text" class="form-control" name="campaign_keywords[${index}][image_url]" placeholder="Image url"></td>
                    <td class="traffic2">
                        <div class="traffic2-wrap">
                            <div class="traffic2-mainlist url-item">
                                <div class="traffic2-input">
                                    <input type="url" class="form-control" name="campaign_keywords[${index}][traffic2_url][]" value="" placeholder="Url">
                                </div>
                                <div class="traffic2-action add" data-index="${index}"><i class="fa fa-plus"></i></div>
                            </div>
                            <div class="traffic2-sublist"></div>
                        </div>
                    </td>
                    <td class="text-center"><i class="fa fa-minus-circle text-danger remove-campaign-keyword-button" style="cursor: pointer"></i></td>
                </tr>`)
            calculateTotalAmount()
        })

        $(document).on('click', '.remove-campaign-keyword-button', function() {
            $(this).closest('tr').remove()
            calculateTotalAmount()
        })

        $(document).on('change', '.package', function() {
            calculateTotalAmount();
        })

        $(document).on('change', '.keyword-total-views', function() {
            calculateTotalAmount();
        })

        $('#campaign-create').submit(function() {
            var totalViews = parseInt($('#total-views').attr('data-value'))
            if (totalViews < 1000) {
                $('#campaign-alert-error').show()
                window.scrollTo(0, 0)
                return false
            } else {
                $('#campaign-alert-error').hide()
                return true
            }
        })
    })

    $(document).on('click', '.traffic2-wrap .traffic2-action', function() {
        let $this = $(this);
        if ($this.hasClass('add')) {
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
