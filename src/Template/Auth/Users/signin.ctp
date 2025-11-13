<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Sign In'));
$this->assign('description', __('Sign in to start your session'));
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
                                <p class="mb-0"><?= __('Please log in to your account') ?></p>
                            </div>
                            <div class="form-body">
                                <?= $this->Form->create($user, ['id' => 'signin-form', 'class' => 'row g-3']); ?>
                                <div class="col-12">
                                    <label for="inputEmailAddress" class="form-label"><?= __('Username or Email') ?></label>
                                    <?= $this->Form->control('username', [
                                        'label' => false,
                                        'placeholder' => __('Username or email address'),
                                        'class' => 'form-control',
                                        'id' => 'inputEmailAddress',
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
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <?= $this->Form->control('remember_me', [
                                            'type' => 'checkbox',
                                            'label' => __('Remember Me'),
                                            'class' => 'form-check-input',
                                            'id' => 'flexSwitchCheckChecked',
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="<?= $this->Url->build([
                                                    'controller' => 'Users',
                                                    'action' => 'forgotPassword',
                                                    'prefix' => 'auth',
                                                ]); ?>"><?= __('Forgot Password') ?> ?</a>
                                </div>

                                <?php if ((get_option('enable_captcha_signin', 'no') == 'yes') && isset_captcha()) : ?>
                                    <div class="col-12">
                                        <div class="form-group captcha">
                                            <div id="captchaSignin" style="display: inline-block;"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <?= $this->Form->button(__('Sign in'), [
                                            'class' => 'btn btn-light',
                                            'id' => 'invisibleCaptchaSignin',
                                        ]); ?>
                                    </div>
                                </div>

                                <?php if ((bool)get_option('close_registration', false) === false) : ?>
                                    <div class="col-12">
                                        <div class="text-center">
                                            <p class="mb-0"><?= __("Don't have an account yet?") ?> <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signup', 'prefix' => 'auth']); ?>"><?= __('Sign up here') ?></a></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?= $this->Form->end() ?>
                            </div>

                            <?php if (
                                (bool)get_option('social_login_facebook', false) ||
                                (bool)get_option('social_login_twitter', false) ||
                                (bool)get_option('social_login_google', false)
                            ) : ?>
                                <div class="login-separater text-center mb-5">
                                    <span><?= __('OR SIGN IN WITH') ?></span>
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
                                                '?' => ['redirect' => $this->request->getQuery('redirect')],
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
                                                '?' => ['redirect' => $this->request->getQuery('redirect')],
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
                                                '?' => ['redirect' => $this->request->getQuery('redirect')],
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
    // Clean up URL hash
    var url_href = window.location.href;
    if (url_href.substr(-1) === '#') {
        history.pushState('', document.title, url_href.substr(0, url_href.length - 1));
    }

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
    });
</script>
<?php $this->end(); ?>