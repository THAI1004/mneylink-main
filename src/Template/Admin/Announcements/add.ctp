<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Announcement $announcement
 */
$this->assign('title', __('Add Announcement'));
$this->assign('description', '');
$this->assign('content_title', __('Add Announcement'));
?>


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header announcement-header-gradient text-white text-center py-4">
                    <div class="announcement-icon-box">
                        <i class="fa fa-bullhorn fa-3x"></i>
                    </div>
                    <h3 class="mb-0 fw-bold"><?= __('Create New Announcement') ?></h3>
                    <p class="mb-0 mt-2 opacity-75"><?= __('Notify your users about important updates') ?></p>
                </div>

                <div class="card-body p-4">
                    <?= $this->Form->create($announcement); ?>

                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-edit me-2"></i><?= __('Announcement Details') ?>
                        </h5>

                        <div class="mb-3">
                            <?= $this->Form->control('title', [
                                'label' => __('Announcement Title'),
                                'class' => 'form-control form-control-lg',
                                'type' => 'text',
                                'placeholder' => __('Enter a clear and concise title...'),
                                'id' => 'title_field'
                            ]); ?>
                            <div class="form-text text-white">
                                <i class="fa fa-info-circle me-1"></i>
                                <?= __('Make it attention-grabbing and informative') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $this->Form->control('published', [
                                        'label' => __('Publication Status'),
                                        'options' => [
                                            '1' => __('📢 Published - Show to all users'),
                                            '0' => __('📝 Draft - Save for later')
                                        ],
                                        'class' => 'form-select form-select-lg'
                                    ]); ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Content Section -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-edit me-2"></i><?= __('Announcement Content') ?>
                        </h5>

                        <div class="mb-3">
                            <?= $this->Form->control('content', [
                                'label' => __('Message'),
                                'class' => 'form-control text-editor',
                                'type' => 'textarea',
                                'rows' => 8,
                                'id' => 'content_field'
                            ]); ?>
                            <div class="form-text text-white">
                                <i class="fa fa-info-circle me-1"></i>
                                <?= __('Write your announcement message. You can use rich text formatting.') ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                        <?= $this->Html->link(
                            '<i class="fa fa-times me-2"></i>' . __('Cancel'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-lg px-4', 'escape' => false]
                        ); ?>
                        <?= $this->Form->button(
                            '<i class="fa fa-bullhorn me-2"></i>' . __('Publish Announcement'),
                            ['class' => 'btn btn-primary text-white btn-lg fw-bold p-2', 'escape' => false]
                        ); ?>
                    </div>

                    <?= $this->Form->end(); ?>
                </div>
            </div>

            <!-- Tips Card -->

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

        // Update title preview
        $('#title_field').on('input', function() {
            const title = $(this).val() || '<?= __('Announcement Title') ?>';
            $('#preview_title').text(title);
        });

        // Update status preview
        $('select[name="published"]').on('change', function() {
            const published = $(this).val();
            const statusBadge = $('#preview_status');

            if (published === '1') {
                statusBadge.removeClass('bg-warning text-dark').addClass('bg-success text-white');
                statusBadge.html('<i class="fa fa-check-circle me-1"></i><?= __('Published') ?>');
            } else {
                statusBadge.removeClass('bg-success text-white').addClass('bg-warning text-dark');
                statusBadge.html('<i class="fa fa-clock-o me-1"></i><?= __('Draft') ?>');
            }
        });

        // Update content preview
        function updateContentPreview() {
            let content = '';

            if (CKEDITOR.instances.content) {
                content = CKEDITOR.instances.content.getData();
            } else {
                content = $('textarea[name="content"]').val();
            }

            if (content && content.trim() !== '') {
                $('#preview_content').html(content);
            } else {
                $('#preview_content').html('<p class="text-muted mb-0 fst-italic"><?= __('Your announcement content will appear here...') ?></p>');
            }
        }

        // Listen for CKEditor changes
        let editorCheckInterval = setInterval(function() {
            if (CKEDITOR.instances.content) {
                CKEDITOR.instances.content.on('change', function() {
                    updateContentPreview();
                });

                CKEDITOR.instances.content.on('key', function() {
                    updateContentPreview();
                });

                clearInterval(editorCheckInterval);
            }
        }, 500);

        // Fallback for textarea
        $('textarea[name="content"]').on('input', function() {
            updateContentPreview();
        });

        // Initialize status badge
        $('select[name="published"]').trigger('change');
    });
</script>
<?php $this->end(); ?>