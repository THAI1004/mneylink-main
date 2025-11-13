<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Traffic $traffic
 * @var mixed $plans
 */
$this->assign('title', __('Add Campaign Traffic'));
$this->assign('description', '');
$this->assign('content_title', __('Add Campaign Traffic'));
?>

<?php $this->start('scriptTop'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .url-list {
        margin-bottom: 15px;
    }

    .url-list .url-item {
        display: flex;
        align-items: center;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s ease;
    }

    .url-list .url-item:hover {
        border-color: #adb5bd;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .url-list .url-item .url-action {
        width: 45px;
        text-align: center;
        font-size: 18px;
        height: 45px;
        line-height: 45px;
        color: #dc3545;
        border-right: 1px solid #dee2e6;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .url-list .url-item .url-action:hover {
        background: #dc3545;
        color: white;
    }

    .url-list .url-item .url-input {
        flex: 1;
        padding: 0 12px;
    }

    .url-list .url-item .url-input input {
        border: none;
        height: 45px;
        width: 100%;
        background: transparent;
        outline: none;
    }

    .url-list .url-item .url-input input:focus {
        outline: none;
    }

    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 20px;
        display: inline-block;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        user-select: none;
        margin-left: 8px;
    }

    .select2-container--classic .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0.375rem;
    }

    .select2-container--classic .select2-selection--multiple .select2-selection__choice {
        background: #0d6efd;
        border: none;
        border-radius: 15px;
        padding: 5px 10px;
        color: white;
    }
</style>
<?php $this->end(); ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php $this->Form->unlockField('traffic_ver2_url') ?>
        <?= $this->Form->create($traffic); ?>

        <div class="row g-3">
            <!-- Tên chiến dịch -->
            <div class="col-12">
                <label class="form-label fw-semibold"><?= __('Tên chiến dịch') ?></label>
                <?= $this->Form->text('title', [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Nhập tên chiến dịch...',
                    'value' => $traffic->title ?? ''
                ]) ?>
            </div>

            <!-- URL chiến dịch -->
            <div class="col-12">
                <label class="form-label fw-semibold"><?= __('URL chiến dịch') ?></label>
                <?= $this->Form->text('url', [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'https://example.com',
                    'value' => $traffic->url ?? ''
                ]) ?>
            </div>

            <!-- Số lượng mỗi ngày và Tổng -->
            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Số lượng mỗi ngày') ?></label>
                <?= $this->Form->number('count_day', [
                    'class' => 'form-control',
                    'placeholder' => 'Số lượng traffic chạy mỗi ngày',
                    'value' => $traffic->count_day ?? ''
                ]) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Tổng') ?></label>
                <?= $this->Form->number('count', [
                    'class' => 'form-control',
                    'placeholder' => 'Số lượng tổng chạy',
                    'value' => $traffic->count ?? ''
                ]) ?>
            </div>

            <!-- Từ khóa -->
            <div class="col-12">
                <label class="form-label fw-semibold"><?= __('Từ khóa') ?></label>
                <?= $this->Form->textarea('keywords', [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Mỗi từ khóa trên một dòng!',
                    'value' => $traffic->keywords ?? ''
                ]) ?>
            </div>

            <!-- Hình ảnh Mobile -->
            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Hình ảnh trên Mobile') ?></label>
                <?= $this->Form->text('img_mobile', [
                    'type' => 'url',
                    'class' => 'form-control',
                    'placeholder' => 'URL hình ảnh hiển thị thiết bị Mobile/Tablet!',
                    'value' => $traffic->img_mobile ?? ''
                ]) ?>
            </div>

            <!-- Hình ảnh Desktop -->
            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Hình ảnh Desktop') ?></label>
                <?= $this->Form->text('img_desktop', [
                    'type' => 'url',
                    'class' => 'form-control',
                    'placeholder' => 'URL hình ảnh hiển thị thiết bị máy tính!',
                    'value' => $traffic->img_desktop ?? ''
                ]) ?>
            </div>

            <!-- Content -->
            <div class="col-12">
                <label class="form-label fw-semibold"><?= __('Content') ?></label>
                <?= $this->Form->textarea('content', [
                    'class' => 'form-control text-editor',
                    'rows' => 6,
                    'value' => $traffic->content ?? ''
                ]) ?>
            </div>

            <!-- Trạng thái -->
            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Trạng thái') ?></label>
                <?= $this->Form->select('status', [
                    0 => __('Tạm dừng'),
                    1 => __('Đang chạy'),
                    2 => __('Hoàn thành'),
                    3 => __('Kết thúc'),
                ], [
                    'class' => 'form-select',
                    'value' => $traffic->status ?? 0
                ]) ?>
            </div>

            <!-- Thiết bị -->
            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Thiết bị') ?></label>
                <?= $this->Form->select('device', [
                    0 => __('Tất cả'),
                    1 => __('Chỉ PC'),
                    2 => __('Chỉ Mobile')
                ], [
                    'class' => 'form-select',
                    'value' => $traffic->device ?? 0
                ]) ?>
            </div>

            <!-- Cho phép copy -->
            <div class="col-12">
                <div class="form-check">
                    <?= $this->Form->checkbox('copy', [
                        'class' => 'form-check-input',
                        'id' => 'copyCheck',
                        'checked' => $traffic->copy ?? false
                    ]) ?>
                    <label class="form-check-label" for="copyCheck">
                        <?= __('Cho phép copy') ?>
                    </label>
                </div>
            </div>
        </div>

        <!-- Camp ngoại Section -->
        <hr class="my-4">
        <div class="section-header">
            <i class="bx bx-world"></i> Tùy chọn cho camp ngoại
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="form-check">
                    <?= $this->Form->checkbox('foreign_camp', [
                        'class' => 'form-check-input',
                        'id' => 'foreignCampCheck',
                        'checked' => $traffic->foreign_camp ?? false
                    ]) ?>
                    <label class="form-check-label" for="foreignCampCheck">
                        <?= __('Camp ngoại') ?>
                    </label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Ngoại trừ vùng') ?></label>
                <?= $this->Form->select('except_region', get_countries(), [
                    'class' => 'except_region form-control',
                    'multiple' => 'multiple',
                    'value' => $traffic->except_region ?? []
                ]) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('Chỉ định vùng') ?></label>
                <?= $this->Form->select('only_region', get_countries(), [
                    'class' => 'only_region form-control',
                    'multiple' => 'multiple',
                    'value' => $traffic->only_region ?? []
                ]) ?>
            </div>
        </div>

        <!-- Traffic Version 2 Section -->
        <hr class="my-4">
        <div class="section-header">
            <i class="bx bx-trending-up"></i> Traffic Version 2
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="form-check">
                    <?= $this->Form->checkbox('traffic_ver2', [
                        'class' => 'form-check-input',
                        'id' => 'trafficVer2Check',
                        'checked' => $traffic->traffic_ver2 ?? false
                    ]) ?>
                    <label class="form-check-label" for="trafficVer2Check">
                        <?= __('Enable Traffic version 2') ?>
                    </label>
                </div>
            </div>

            <div class="col-12">
                <div class="url-list">
                    <?php if (!empty($traffic->traffic_ver2_url) && is_array(json_decode($traffic->traffic_ver2_url))) : ?>
                        <?php foreach (json_decode($traffic->traffic_ver2_url) as $k => $list) : ?>
                            <div class="url-item" data-number="<?= $k ?>">
                                <div class="url-action"><i class="fa fa-trash"></i></div>
                                <div class="url-input">
                                    <input type="url" name="traffic_ver2_url[]" class="form-control" value="<?= $list ?>" placeholder="https://example.com">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn btn-primary add-url">
                    <i class="fa fa-plus me-2"></i><?= __('Add URL') ?>
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4">
            <div class="col-12">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    <i class="fa fa-check me-2"></i><?= __('Submit') ?>
                </button>
            </div>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize CKEditor
        CKEDITOR.replaceClass = 'text-editor';
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
        CKEDITOR.dtd.$removeEmpty['i'] = false;

        // Initialize Select2
        $('.except_region, .only_region').select2({
            theme: 'classic',
            placeholder: 'Chọn quốc gia...',
            allowClear: true
        });

        // Add URL functionality
        $('.add-url').click(function() {
            let list = $('.url-list');
            let item = list.find('.url-item');
            let html = `<div class="url-item" data-number="${item.length}">
                            <div class="url-action"><i class="fa fa-trash"></i></div>
                            <div class="url-input">
                                <input type="url" name="traffic_ver2_url[]" class="form-control" value="" placeholder="https://example.com">
                            </div>
                        </div>`;
            list.append(html);
        });

        // Remove URL functionality
        $(document).on('click', '.url-action', function() {
            let $this = $(this);
            $this.closest('.url-item').remove();
            $('.url-list .url-item').each(function(index, item) {
                $(this).attr('data-number', index);
            });
        });
    });
</script>
<?php $this->end(); ?>