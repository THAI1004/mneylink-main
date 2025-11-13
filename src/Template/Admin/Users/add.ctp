<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var mixed $plans
 */
$this->assign('title', __('Add User'));
$this->assign('description', '');
$this->assign('content_title', __('Add User'));
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?= $this->Form->create($user); ?>

        <div class="row g-4">
            <!-- Basic Information Section -->
            <div class="col-12">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="bx bx-user-circle text-primary"></i> <?= __('Basic Information') ?>
                </h5>
            </div>

            <!-- Username -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="bx bx-user"></i> <?= __('Username') ?>
                    <span class="text-danger">*</span>
                </label>
                <?= $this->Form->text('username', [
                    'class' => 'form-control',
                    'placeholder' => __('Enter username...'),
                    'required' => true
                ]) ?>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="bx bx-envelope"></i> <?= __('Email') ?>
                    <span class="text-danger">*</span>
                </label>
                <?= $this->Form->email('email', [
                    'class' => 'form-control',
                    'placeholder' => __('Enter email address...'),
                    'required' => true
                ]) ?>
            </div>

            <!-- Password -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="bx bx-lock"></i> <?= __('Password') ?>
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <?= $this->Form->password('password', [
                        'class' => 'form-control',
                        'placeholder' => __('Enter password...'),
                        'required' => true,
                        'id' => 'password-field',
                        'templates' => [
                            'inputContainer' => '{{content}}'
                        ]
                    ]) ?>
                    <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                        <i class="bx bx-show"></i>
                    </button>
                </div>
                <small class="text-muted">
                    <i class="bx bx-info-circle"></i> <?= __('Minimum 6 characters') ?>
                </small>
            </div>

            <!-- Role -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="bx bx-shield"></i> <?= __('Role') ?>
                    <span class="text-danger">*</span>
                </label>
                <?= $this->Form->select('role', [
                    'member' => __('Member'),
                    'admin' => __('Admin'),
                    'admin_report' => __('Admin Report'),
                    'admin_camp' => __('Admin Camp'),
                ], [
                    'class' => 'form-select',
                    'required' => true
                ]); ?>
            </div>

            <!-- Account Settings Section -->
            <div class="col-12 mt-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="bx bx-cog text-info"></i> <?= __('Account Settings') ?>
                </h5>
            </div>

            <!-- Status -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bx bx-info-circle"></i> <?= __('Status') ?>
                    <span class="text-danger">*</span>
                </label>
                <?= $this->Form->select('status', [
                    1 => __('Active'),
                    2 => __('Pending'),
                    3 => __('Inactive'),
                ], [
                    'class' => 'form-select',
                    'required' => true
                ]); ?>
            </div>

            <!-- Plan -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bx bx-package"></i> <?= __('Plan') ?>
                    <span class="text-danger">*</span>
                </label>
                <?= $this->Form->select('plan_id', $plans, [
                    'empty' => __('Choose Plan'),
                    'class' => 'form-select',
                    'default' => 1,
                    'required' => true,
                ]); ?>
            </div>

            <!-- Plan Expiration -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bx bx-calendar"></i> <?= __('Plan Expiration Date') ?>
                </label>
                <?= $this->Form->control('expiration', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'placeholder' => 'YYYY-MM-DD',
                    'label' => false,
                    'autocomplete' => 'off'
                ]); ?>
                <small class="text-muted">
                    <i class="bx bx-info-circle"></i> <?= __('Leave empty for infinity') ?>
                </small>
            </div>

            <!-- Info Alert -->
            <div class="col-12 mt-3">
                <div class="alert alert-info border-0 d-flex align-items-start">
                    <i class="bx bx-info-circle fs-4 me-3 mt-1"></i>
                    <div>
                        <strong><?= __('User Creation Information') ?></strong>
                        <ul class="mb-0 mt-2 small">
                            <li><?= __('Username must be unique and cannot be changed later') ?></li>
                            <li><?= __('A welcome email will be sent to the user after creation') ?></li>
                            <li><?= __('Choose appropriate role based on user permissions needed') ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="col-12 mt-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bx bx-user-plus me-2"></i><?= __('Create User') ?>
                    </button>
                    <button type="reset" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="bx bx-reset me-2"></i><?= __('Reset') ?>
                    </button>
                    <?= $this->Html->link(
                        '<i class="bx bx-arrow-back me-2"></i>' . __('Back to Users'),
                        ['action' => 'index'],
                        ['class' => 'btn btn-outline-primary btn-lg px-4', 'escape' => false]
                    ); ?>
                </div>
            </div>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>


<?php $this->start('scriptTop'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php $this->end(); ?>

<?php $this->start('scriptBottom'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // Initialize Flatpickr for date picker
        flatpickr('input[name="expiration"]', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            minDate: 'today'
        });

        // Form validation
        $('form').on('submit', function(e) {
            let valid = true;
            let errors = [];

            // Validate username
            const username = $('input[name="username"]').val();
            if (username.length < 3) {
                valid = false;
                errors.push('<?= __('Username must be at least 3 characters') ?>');
            }

            // Validate email
            const email = $('input[name="email"]').val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                valid = false;
                errors.push('<?= __('Please enter a valid email address') ?>');
            }

            // Validate password
            const password = $('#password-field').val();
            if (password.length < 6) {
                valid = false;
                errors.push('<?= __('Password must be at least 6 characters') ?>');
            }

            if (!valid) {
                e.preventDefault();
                alert(errors.join('\n'));
                return false;
            }
        });

        // Show/Hide password toggle
        $('#toggle-password').on('click', function() {
            const input = $('#password-field');
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bx-show').addClass('bx-hide');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bx-hide').addClass('bx-show');
            }
        });
    });
</script>
<?php $this->end(); ?>