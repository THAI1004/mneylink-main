<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Testimonial $testimonial
 */
$this->assign('title', __('Add Testimonial'));
$this->assign('description', '');
$this->assign('content_title', __('Add Testimonial'));
?>


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header testimonial-header-gradient text-white text-center py-4">
                    <div class="mb-2">
                        <i class="fa fa-quote-left fa-3x opacity-75"></i>
                    </div>
                    <h3 class="mb-0 fw-bold"><?= __('Add New Testimonial') ?></h3>
                    <p class="mb-0 mt-2 opacity-75"><?= __('Showcase customer feedback and reviews') ?></p>
                </div>

                <div class="card-body p-4">
                    <?= $this->Form->create($testimonial); ?>

                    <!-- Customer Information -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-user me-2"></i><?= __('Customer Information') ?>
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $this->Form->control('name', [
                                    'label' => __('Full Name'),
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => __('Enter customer name...'),
                                    'id' => 'name_field'
                                ]); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <?= $this->Form->control('position', [
                                    'label' => __('Position / Title'),
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => __('e.g., CEO at Company Inc.')
                                ]); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('published', [
                                'label' => __('Publication Status'),
                                'options' => [
                                    '1' => __('Published - Show on website'),
                                    '0' => __('Draft - Hide from website')
                                ],
                                'class' => 'form-select'
                            ]); ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Image Section -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-image me-2"></i><?= __('Profile Picture') ?>
                        </h5>

                        <div class="row">
                            <div class="col-md-8">
                                <?= $this->Form->control('image', [
                                    'label' => __('Image URL'),
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => __('https://example.com/image.jpg'),
                                    'id' => 'image_url'
                                ]); ?>
                                <div class="form-text text-white">
                                    <i class="fa fa-info-circle me-1"></i>
                                    <?= __('Enter the URL of the customer\'s profile picture') ?>
                                </div>
                            </div>


                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Testimonial Content -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-comment me-2"></i><?= __('Testimonial Content') ?>
                        </h5>

                        <div class="mb-3">
                            <?= $this->Form->control('content', [
                                'label' => __('Customer Review'),
                                'class' => 'form-control text-editor',
                                'type' => 'textarea',
                                'rows' => 6,
                                'placeholder' => __('Write the customer testimonial here...')
                            ]); ?>
                            <div class="form-text text-white">
                                <i class="fa fa-lightbulb-o me-1"></i>
                                <?= __('Share what the customer said about your product or service') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Card -->


                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                        <?= $this->Html->link(
                            '<i class="fa fa-times me-2"></i>' . __('Cancel'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-lg px-4', 'escape' => false]
                        ); ?>
                        <?= $this->Form->button(
                            '<i class="fa fa-check me-2"></i>' . __('Save Testimonial'),
                            ['class' => 'btn btn-primary text-white btn-lg fw-bold p-2', 'escape' => false]
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

        // Image preview
        $('#image_url').on('input', function() {
            const imageUrl = $(this).val().trim();
            const previewContainer = $('#image_preview');
            const previewImage = $('#preview_image');

            if (imageUrl) {
                previewContainer.html('<img src="' + imageUrl + '" alt="Preview" onerror="this.style.display=\'none\'; $(this).parent().html(\'<div class=\\\'placeholder\\\'><i class=\\\'fa fa-user\\\'></i></div><p class=\\\'text-muted small mt-2 mb-0\\\'>' + '<?= __('Invalid image URL') ?>' + '</p>\');">');
                previewImage.html('<img src="' + imageUrl + '" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" onerror="this.style.display=\'none\';">');
            } else {
                previewContainer.html('<div class="placeholder"><i class="fa fa-user"></i></div><p class="text-muted small mt-2 mb-0"><?= __('No image') ?></p>');
                previewImage.html('<div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; color: white;"><i class="fa fa-user fa-2x"></i></div>');
            }
        });

        // Live preview updates
        $('#name_field').on('input', function() {
            const name = $(this).val() || '<?= __('Customer Name') ?>';
            $('#preview_name').text(name);
        });

        $('input[name="position"]').on('input', function() {
            const position = $(this).val() || '<?= __('Position') ?>';
            $('#preview_position').text(position);
        });

        // Update preview when CKEditor content changes
        if (CKEDITOR.instances.content) {
            CKEDITOR.instances.content.on('change', function() {
                updateContentPreview();
            });
        }

        // Fallback for textarea if CKEditor is not loaded
        $('textarea[name="content"]').on('input', function() {
            updateContentPreview();
        });

        function updateContentPreview() {
            let content = '';
            if (CKEDITOR.instances.content) {
                content = CKEDITOR.instances.content.getData();
            } else {
                content = $('textarea[name="content"]').val();
            }

            if (content) {
                // Strip HTML tags for preview
                const tempDiv = $('<div>').html(content);
                const textContent = tempDiv.text().trim();
                $('#preview_content').html('<i class="fa fa-quote-left me-2 opacity-50"></i>' + (textContent || '<?= __('Testimonial content will appear here...') ?>') + '<i class="fa fa-quote-right ms-2 opacity-50"></i>');
            } else {
                $('#preview_content').html('<i class="fa fa-quote-left me-2 opacity-50"></i><?= __('Testimonial content will appear here...') ?><i class="fa fa-quote-right ms-2 opacity-50"></i>');
            }
        }
    });
</script>
<?php $this->end(); ?>