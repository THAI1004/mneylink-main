<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
$this->assign('title', __('Add Post'));
$this->assign('description', '');
$this->assign('content_title', __('Add Post'));
?>



<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header post-header-gradient text-white text-center py-4">
                    <div class="mb-2">
                        <i class="fa fa-pencil-square-o fa-3x opacity-75"></i>
                    </div>
                    <h3 class="mb-0 fw-bold"><?= __('Create New Post') ?></h3>
                    <p class="mb-0 mt-2 opacity-75"><?= __('Write and publish your blog content') ?></p>
                </div>

                <div class="card-body p-4">
                    <?= $this->Form->create($post); ?>

                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-info-circle me-2"></i><?= __('Basic Information') ?>
                        </h5>

                        <div class="mb-3">
                            <?= $this->Form->control('title', [
                                'label' => __('Post Title'),
                                'class' => 'form-control form-control-lg',
                                'type' => 'text',
                                'placeholder' => __('Enter an engaging post title...')
                            ]); ?>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('slug', [
                                'label' => __('URL Slug'),
                                'class' => 'form-control',
                                'type' => 'text',
                                'placeholder' => __('post-url-slug')
                            ]); ?>
                            <div class="form-text text-white">
                                <i class="fa fa-info-circle me-1"></i>
                                <?= __('URL-friendly version of the title (lowercase, no spaces)') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('published', [
                                'label' => __('Publication Status'),
                                'options' => [
                                    '1' => __('Published'),
                                    '0' => __('Draft')
                                ],
                                'class' => 'form-select'
                            ]); ?>
                            <div class="form-text text-white">
                                <i class="fa fa-info-circle me-1"></i>
                                <?= __('Choose whether to publish immediately or save as draft') ?>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Content -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-file-text-o me-2"></i><?= __('Post Content') ?>
                        </h5>

                        <div class="mb-3">
                            <?= $this->Form->control('short_description', [
                                'label' => __('Short Description (Excerpt)'),
                                'class' => 'form-control text-editor',
                                'type' => 'textarea',
                                'rows' => 3
                            ]); ?>
                            <div class="form-text text-white">
                                <i class="fa fa-info-circle me-1"></i>
                                <?= __('Brief summary that appears in post listings') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('description', [
                                'label' => __('Full Content'),
                                'class' => 'form-control text-editor',
                                'type' => 'textarea',
                                'rows' => 10
                            ]); ?>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- SEO Settings -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-search me-2"></i><?= __('SEO Settings') ?>
                        </h5>

                        <div class="alert text-white alert-info d-flex align-items-start mb-3" role="alert">
                            <i class="fa fa-lightbulb-o me-2 mt-1 fs-5"></i>
                            <div class="small">
                                <strong><?= __('SEO Tip:') ?></strong>
                                <?= __('Optimized meta tags help search engines understand and rank your content better.') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('meta_title', [
                                'label' => __('Meta Title'),
                                'class' => 'form-control',
                                'type' => 'text',
                                'placeholder' => __('Enter SEO title...'),
                                'id' => 'meta_title_field'
                            ]); ?>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="form-text text-white mb-0">
                                    <i class="fa fa-info-circle me-1"></i>
                                    <?= __('Recommended: 50-60 characters') ?>
                                </div>
                                <span class="char-counter" id="meta_title_counter">0 / 60</span>
                            </div>
                            <div class="progress mt-2" style="height: 3px;">
                                <div class="progress-bar" id="meta_title_progress" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('meta_description', [
                                'label' => __('Meta Description'),
                                'class' => 'form-control text-white',
                                'type' => 'textarea',
                                'rows' => 3,
                                'placeholder' => __('Enter SEO description...'),
                                'id' => 'meta_description_field'
                            ]); ?>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="form-text text-white mb-0">
                                    <i class="fa fa-info-circle me-1"></i>
                                    <?= __('Recommended: 150-160 characters') ?>
                                </div>
                                <span class="char-counter" id="meta_description_counter">0 / 160</span>
                            </div>
                            <div class="progress mt-2" style="height: 3px;">
                                <div class="progress-bar" id="meta_description_progress" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                        <?= $this->Html->link(
                            '<i class="fa fa-times me-2"></i>' . __('Cancel'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-lg px-4', 'escape' => false]
                        ); ?>
                        <?= $this->Form->button(
                            '<i class="fa fa-check me-2"></i>' . __('Publish Post'),
                            ['class' => 'btn btn-primary text-white btn-lg fw-bold px-5', 'escape' => false]
                        ); ?>
                    </div>

                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        // Initialize CKEditor
        CKEDITOR.replaceClass = 'text-editor';
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
        CKEDITOR.dtd.$removeEmpty['i'] = false;

        // Character counter function
        function updateCharCounter(fieldId, counterId, progressId, maxLength) {
            const field = $('#' + fieldId);
            const counter = $('#' + counterId);
            const progress = $('#' + progressId);

            function update() {
                const length = field.val().length;
                const percentage = (length / maxLength) * 100;

                counter.text(length + ' / ' + maxLength);
                progress.css('width', Math.min(percentage, 100) + '%');

                // Update counter color
                counter.removeClass('warning success');
                progress.removeClass('bg-success bg-warning bg-danger');

                if (length === 0) {
                    progress.addClass('bg-secondary');
                } else if (length > maxLength) {
                    counter.addClass('warning');
                    progress.addClass('bg-danger');
                } else if (length >= maxLength - 10) {
                    counter.addClass('success');
                    progress.addClass('bg-success');
                } else {
                    progress.addClass('bg-primary');
                }
            }

            field.on('input', update);
            update();
        }

        // Initialize character counters
        updateCharCounter('meta_title_field', 'meta_title_counter', 'meta_title_progress', 60);
        updateCharCounter('meta_description_field', 'meta_description_counter', 'meta_description_progress', 160);

        // Auto-generate slug from title
        $('#title').on('input', function() {
            const title = $(this).val();
            const slug = title
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            $('#slug').val(slug);
        });
    });
</script>
<?php $this->end(); ?>