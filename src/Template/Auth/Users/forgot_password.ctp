<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Forgot Password'));
$this->assign('description', __('Enter your e-mail address below to reset your password.'));
?>

<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
    <div class="container">
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col mx-auto">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="p-4">
                            <div class="mb-3 text-center">
                                <?php if (file_exists(WWW_ROOT . 'assets/images/logo-icon.png')): ?>
                                    <img src="<?= $this->Url->webroot('assets/images/logo-icon.png') ?>" width="60" alt="" />
                                <?php else: ?>
                                    <h3><?= get_logo_alt()['content'] ?></h3>
                                <?php endif; ?>
                            </div>
                            <div class="text-center mb-4">
                                <h5 class=""><?= __('Forgot Password?') ?></h5>
                                <p class="mb-0">
                                    <?php if (!isset($user->id)): ?>
                                        <?= __('Enter your registered email to reset password') ?>
                                    <?php else: ?>
                                        <?= __('Enter your new password') ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="form-body">
                                <?php if (!isset($user->id)): ?>
                                    <?= $this->Form->create($user, ['id' => 'forgotpassword-form', 'class' => 'row g-3']); ?>
                                    <div class="col-12">
                                        <label for="inputEmail" class="form-label"><?= __('Email') ?></label>
                                        <?= $this->Form->control('email', [
                                            'label' => false,
                                            'placeholder' => __('example@user.com'),
                                            'class' => 'form-control',
                                            'type' => 'email',
                                            'id' => 'inputEmail',
                                        ]) ?>
                                    </div>

                                    <?php if ((get_option('enable_captcha_forgot_password') == 'yes') && isset_captcha()) : ?>
                                        <div class="col-12">
                                            <div class="form-group captcha">
                                                <div id="captchaForgotpassword" style="display: inline-block;"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <?= $this->Form->button(__('Send'), [
                                                'class' => 'btn btn-light',
                                                'id' => 'invisibleCaptchaForgotpassword'
                                            ]); ?>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="text-center">
                                            <p class="mb-0">
                                                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signin', 'prefix' => 'auth']); ?>">
                                                    <i class='bx bx-arrow-back me-1'></i><?= __('Back to Sign In') ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <?= $this->Form->end() ?>

                                <?php else: ?>
                                    <?= $this->Form->create($user, ['class' => 'row g-3']); ?>
                                    <?= $this->Form->hidden('id', ['value' => $user->id]); ?>

                                    <div class="col-12">
                                        <label for="inputChoosePassword" class="form-label"><?= __('New Password') ?></label>
                                        <div class="input-group" id="show_hide_password">
                                            <?= $this->Form->password('password', [
                                                'label' => false,
                                                'placeholder' => __('Enter New Password'),
                                                'class' => 'form-control border-end-0',
                                                'id' => 'inputChoosePassword',
                                            ]) ?>
                                            <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="inputConfirmPassword" class="form-label"><?= __('Confirm Password') ?></label>
                                        <div class="input-group" id="show_hide_password_confirm">
                                            <?= $this->Form->password('confirm_password', [
                                                'label' => false,
                                                'placeholder' => __('Confirm New Password'),
                                                'class' => 'form-control border-end-0',
                                                'id' => 'inputConfirmPassword',
                                            ]) ?>
                                            <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <?= $this->Form->button(__('Reset Password'), [
                                                'class' => 'btn btn-light'
                                            ]); ?>
                                        </div>
                                    </div>
                                    <?= $this->Form->end() ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script>
    // Password show & hide js (từ template gốc)
    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });

        $("#show_hide_password_confirm a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password_confirm input').attr("type") == "text") {
                $('#show_hide_password_confirm input').attr('type', 'password');
                $('#show_hide_password_confirm i').addClass("bx-hide");
                $('#show_hide_password_confirm i').removeClass("bx-show");
            } else if ($('#show_hide_password_confirm input').attr("type") == "password") {
                $('#show_hide_password_confirm input').attr('type', 'text');
                $('#show_hide_password_confirm i').removeClass("bx-hide");
                $('#show_hide_password_confirm i').addClass("bx-show");
            }
        });
    });
</script>
<?php $this->end(); ?>