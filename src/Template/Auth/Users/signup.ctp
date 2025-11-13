<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Create an Account'));
$this->assign('description', __('Register a new membership'));
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
                                <p class="mb-0"><?= __('Create your account') ?></p>
                            </div>
                            <div class="form-body">
                                <?= $this->Form->create($user, ['id' => 'signup-form', 'class' => 'row g-3']); ?>
                                <div class="col-12">
                                    <label for="inputRole" class="form-label"><?= __('Account Type') ?></label>
                                    <?= $this->Form->control('role', [
                                        'type' => 'select',
                                        'label' => false,
                                        'class' => 'form-select',
                                        'id' => 'inputRole',
                                        'options' => [
                                            'member' => 'Nhà xuất bản (Người kiếm tiền)',
                                            'buyer' => 'Nhà quảng cáo (Người mua traffic)'
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-12">
                                    <label for="inputUsername" class="form-label"><?= __('Username') ?></label>
                                    <?= $this->Form->control('username', [
                                        'label' => false,
                                        'placeholder' => __('Username'),
                                        'class' => 'form-control',
                                        'id' => 'inputUsername',
                                    ]) ?>
                                </div>
                                <div class="col-12">
                                    <label for="inputEmail" class="form-label"><?= __('Email') ?></label>
                                    <?= $this->Form->control('email', [
                                        'label' => false,
                                        'placeholder' => __('Email'),
                                        'class' => 'form-control',
                                        'id' => 'inputEmail',
                                    ]) ?>
                                </div>
                                <div class="col-12">
                                    <label for="inputChoosePassword" class="form-label"><?= __('Password') ?></label>
                                    <div class="input-group" id="show_hide_password">
                                        <?= $this->Form->password('password', [
                                            'label' => false,
                                            'placeholder' => __('Enter Password'),
                                            'class' => 'form-control border-end-0',
                                            'id' => 'inputChoosePassword',
                                        ]) ?>
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="inputConfirmPassword" class="form-label"><?= __('Confirm Password') ?></label>
                                    <div class="input-group" id="show_hide_password_compare">
                                        <?= $this->Form->password('password_compare', [
                                            'label' => false,
                                            'placeholder' => __('Re-enter Password'),
                                            'class' => 'form-control border-end-0',
                                            'id' => 'inputConfirmPassword',
                                        ]) ?>
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                    </div>
                                </div>

                                <?php if ((get_option('enable_captcha_signup') == 'yes') && isset_captcha()) : ?>
                                    <div class="col-12">
                                        <div class="form-group captcha">
                                            <div id="captchaSignup" style="display: inline-block;"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12">
                                    <div class="form-check">
                                        <?= $this->Form->control('accept', [
                                            'type' => 'checkbox',
                                            'label' => __(
                                                "I agree to the {0} and {1}",
                                                "<a href='" . $this->Url->build('/') . 'pages/terms' . "' target='_blank'>" . __('Terms of Use') . "</a>",
                                                "<a href='" . $this->Url->build('/') . 'pages/privacy' . "' target='_blank'>" . __('Privacy Policy') . "</a>"
                                            ),
                                            'class' => 'form-check-input',
                                            'escape' => false
                                        ]) ?>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <?= $this->Form->button(__('Sign up'), [
                                            'class' => 'btn btn-light',
                                            'id' => 'invisibleCaptchaSignup',
                                        ]); ?>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0"><?= __('Already have an account?') ?> <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signin', 'prefix' => 'auth']); ?>"><?= __('Sign in here') ?></a></p>
                                    </div>
                                </div>
                                <?= $this->Form->end() ?>
                            </div>

                            <?php if (
                                (bool)get_option('social_login_facebook', false) ||
                                (bool)get_option('social_login_twitter', false) ||
                                (bool)get_option('social_login_google', false)
                            ) : ?>
                                <div class="login-separater text-center mb-5">
                                    <span><?= __('OR SIGN UP WITH') ?></span>
                                    <hr />
                                </div>
                                <div class="list-inline contacts-social text-center">
                                    <?php if ((bool)get_option('social_login_facebook', false)) : ?>
                                        <?= $this->Form->postLink(
                                            '<i class="bx bxl-facebook"></i>',
                                            [
                                                'prefix' => false,
                                                'plugin' => 'ADmad/SocialAuth',
                                                'controller' => 'Auth',
                                                'action' => 'login',
                                                'provider' => 'facebook',
                                                '?' => ['redirect' => $this->request->getQuery('redirect')]
                                            ],
                                            [
                                                'class' => 'list-inline-item bg-light text-white border-0 rounded-3',
                                                'escape' => false,
                                            ]
                                        ); ?>
                                    <?php endif; ?>

                                    <?php if ((bool)get_option('social_login_twitter', false)) : ?>
                                        <?= $this->Form->postLink(
                                            '<i class="bx bxl-twitter"></i>',
                                            [
                                                'prefix' => false,
                                                'plugin' => 'ADmad/SocialAuth',
                                                'controller' => 'Auth',
                                                'action' => 'login',
                                                'provider' => 'twitter',
                                                '?' => ['redirect' => $this->request->getQuery('redirect')]
                                            ],
                                            [
                                                'class' => 'list-inline-item bg-light text-white border-0 rounded-3',
                                                'escape' => false,
                                            ]
                                        ); ?>
                                    <?php endif; ?>

                                    <?php if ((bool)get_option('social_login_google', false)) : ?>
                                        <?= $this->Form->postLink(
                                            '<i class="bx bxl-google"></i>',
                                            [
                                                'prefix' => false,
                                                'plugin' => 'ADmad/SocialAuth',
                                                'controller' => 'Auth',
                                                'action' => 'login',
                                                'provider' => 'google',
                                                '?' => ['redirect' => $this->request->getQuery('redirect')]
                                            ],
                                            [
                                                'class' => 'list-inline-item bg-light text-white border-0 rounded-3',
                                                'escape' => false,
                                            ]
                                        ); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

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

        $("#show_hide_password_compare a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password_compare input').attr("type") == "text") {
                $('#show_hide_password_compare input').attr('type', 'password');
                $('#show_hide_password_compare i').addClass("bx-hide");
                $('#show_hide_password_compare i').removeClass("bx-show");
            } else if ($('#show_hide_password_compare input').attr("type") == "password") {
                $('#show_hide_password_compare input').attr('type', 'text');
                $('#show_hide_password_compare i').removeClass("bx-hide");
                $('#show_hide_password_compare i').addClass("bx-show");
            }
        });
    });
</script>
<?php $this->end(); ?>