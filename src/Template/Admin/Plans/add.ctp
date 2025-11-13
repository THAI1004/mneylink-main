<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plan $plan
 */
$this->assign('title', __('Add Plan'));
$this->assign('description', '');
$this->assign('content_title', __('Add Plan'));
?>

<style>
    /* Toggle Switch */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }

    .plan-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .section-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        margin: 0 -1.5rem;
        padding: 1rem 1.5rem;
        color: white;
    }

    .feature-card {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .btn-submit-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-submit-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header  text-white text-center py-4">
                    <h3 class="mb-0 fw-bold">
                        <i class="fa fa-plus-circle me-2"></i><?= __('Add New Plan') ?>
                    </h3>
                    <p class="mb-0 mt-2 opacity-75"><?= __('Configure your membership plan settings') ?></p>
                </div>

                <div class="card-body p-4">
                    <?= $this->Form->create($plan); ?>

                    <!-- Basic Settings -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-cog me-2"></i><?= __('Basic Settings') ?>
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <?= $this->Form->checkbox('enable', [
                                        'class' => 'form-check-input',
                                        'role' => 'switch',
                                        'id' => 'enable'
                                    ]); ?>
                                    <label class="form-check-label m-1 fw-semibold" for="enable">
                                        <?= __('Enable Plan') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <?= $this->Form->checkbox('hidden', [
                                        'class' => 'form-check-input',
                                        'role' => 'switch',
                                        'id' => 'hidden'
                                    ]); ?>
                                    <label class="form-check-label m-1 fw-semibold" for="hidden">
                                        <?= __('Hidden Plan') ?>
                                    </label>
                                    <div class="form-text">
                                        <?= __('Only admins can see hidden plans and assign it to users but users will not see it at the member area.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Plan Details -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-info-circle me-2"></i><?= __('Plan Details') ?>
                        </h5>

                        <?= $this->Form->control('title', [
                            'label' => __('Plan Title'),
                            'class' => 'form-control',
                            'type' => 'text',
                        ]); ?>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <?= $this->Form->control('monthly_price', [
                                    'label' => __('Monthly Price'),
                                    'class' => 'form-control',
                                    'type' => 'text',
                                ]); ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('yearly_price', [
                                    'label' => __('Yearly Price'),
                                    'class' => 'form-control',
                                    'type' => 'text',
                                ]); ?>
                            </div>
                        </div>

                        <div class="mt-3">
                            <?= $this->Form->control('description', [
                                'label' => __('Description'),
                                'class' => 'form-control text-editor',
                                'type' => 'textarea',
                                'rows' => 4
                            ]); ?>
                        </div>
                    </div>

                    <!-- Limits -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-sliders me-2"></i><?= __('Limits & Restrictions') ?>
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <?= $this->Form->control('url_daily_limit', [
                                    'label' => __('Daily URL Limit'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'step' => 1,
                                    'min' => 0,
                                ]); ?>
                                <div class="form-text"><?= __('Maximum URLs per day') ?></div>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('url_monthly_limit', [
                                    'label' => __('Monthly URL Limit'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'step' => 1,
                                    'min' => 0,
                                ]); ?>
                                <div class="form-text"><?= __('Maximum URLs per month') ?></div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <?= $this->Form->control('views_hourly_limit', [
                                    'label' => __('Hourly Views Limit'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'step' => 1,
                                    'min' => 0,
                                ]); ?>
                                <div class="form-text"><?= __('Max paid views/hour') ?></div>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('views_daily_limit', [
                                    'label' => __('Daily Views Limit'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'step' => 1,
                                    'min' => 0,
                                ]); ?>
                                <div class="form-text"><?= __('Max paid views/day') ?></div>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('views_monthly_limit', [
                                    'label' => __('Monthly Views Limit'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'step' => 1,
                                    'min' => 0,
                                ]); ?>
                                <div class="form-text"><?= __('Max paid views/month') ?></div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <?= $this->Form->control('cpm_fixed', [
                                    'label' => __('Fixed CPM Rate'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'min' => 0,
                                    'step' => 'any',
                                ]); ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('timer', [
                                    'label' => __('Countdown Timer (seconds)'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'step' => 1,
                                    'min' => 0,
                                ]); ?>
                                <div class="form-text"><?= __('Timer duration on short link page') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Redirect Types -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-random me-2"></i><?= __('Redirect Types') ?>
                        </h5>

                        <div class="row g-3 ">
                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card  h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <?= $this->Form->checkbox('direct_redirect', [
                                                'class' => 'form-check-input ',
                                                'role' => 'switch',
                                                'id' => 'direct_redirect',
                                                'required' => false
                                            ]); ?>
                                            <label class="form-check-label m-1 fw-semibold" for="direct_redirect">
                                                <?= __('Direct Redirect') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <?= $this->Form->checkbox('banner_redirect', [
                                                'class' => 'form-check-input',
                                                'role' => 'switch',
                                                'id' => 'banner_redirect',
                                                'required' => false
                                            ]); ?>
                                            <label class="form-check-label m-1 fw-semibold" for="banner_redirect">
                                                <?= __('Banner Redirect') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <?= $this->Form->checkbox('interstitial_redirect', [
                                                'class' => 'form-check-input',
                                                'role' => 'switch',
                                                'id' => 'interstitial_redirect',
                                                'required' => false
                                            ]); ?>
                                            <label class="form-check-label m-1 fw-semibold" for="interstitial_redirect">
                                                <?= __('Interstitial') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <?= $this->Form->checkbox('random_redirect', [
                                                'class' => 'form-check-input',
                                                'role' => 'switch',
                                                'id' => 'random_redirect',
                                                'required' => false
                                            ]); ?>
                                            <label class="form-check-label m-1 fw-semibold" for="random_redirect">
                                                <?= __('Random') ?>
                                            </label>
                                        </div>
                                        <div class="form-text small mt-2">
                                            <?= __('Requires interstitial & banner enabled') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-star me-2"></i><?= __('Plan Features') ?>
                        </h5>

                        <div class="row g-3">
                            <?php
                            $features = [
                                ['edit_link', __('Edit Link'), __('Allow editing links without changing URL')],
                                ['edit_long_url', __('Edit Long URL'), __('Allow editing the destination URL')],
                                ['alias', __('Custom Alias'), __('Allow custom short link aliases')],
                                ['link_expiration', __('Link Expiration'), __('Set expiration date for links')],
                                ['multi_domains', __('Multi Domains'), __('Select different domains for links')],
                                ['disable_ads', __('Remove Ads'), __('Remove ads from short link & member area'), true],
                                ['disable_captcha', __('Remove Captcha'), __('Skip captcha for logged users'), true],
                                ['visitors_remove_captcha', __('Visitors Remove Captcha'), __('Skip captcha for all visitors')],
                                ['onetime_captcha', __('Onetime Captcha'), __('Show captcha only once'), true],
                                ['direct', __('Direct Access'), __('Go directly to URL without interstitial'), true],
                                ['referral', __('Referral Earnings'), __('Earn from referrals')],
                                ['stats', __('Link Statistics'), __('View detailed link statistics')],
                            ];

                            foreach ($features as $feature):
                                [$field, $label, $help, $asterisk] = array_pad($feature, 4, false);
                            ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card feature-card h-100">
                                        <div class="card-body">
                                            <div class="form-check form-switch">
                                                <?= $this->Form->checkbox($field, [
                                                    'class' => 'form-check-input mr-2',
                                                    'role' => 'switch',
                                                    'id' => $field,
                                                    'required' => false
                                                ]); ?>
                                                <label class="form-check-label m-1 fw-semibold" for="<?= $field ?>">
                                                    <?= $asterisk ? '* ' : '' ?><?= $label ?>
                                                </label>
                                            </div>
                                            <div class="form-text text-white small mt-1"><?= $help ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Referral Percentage -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <?= $this->Form->control('referral_percentage', [
                                            'label' => __('Referral Percentage'),
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'min' => 0,
                                            'step' => 'any',
                                            'required' => false,
                                        ]); ?>
                                        <div class="form-text text-white"><?= __('Enter percentage (e.g., 20 for 20%)') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tools & API -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa fa-wrench me-2"></i><?= __('Tools & API Access') ?>
                        </h5>

                        <div class="row g-3">
                            <?php
                            $tools = [
                                ['api_quick', __('Quick Link Tool')],
                                ['api_mass', __('Mass Shrinker Tool')],
                                ['api_full', __('Full Page Script Tool')],
                                ['bookmarklet', __('Bookmarklet Tool')],
                                ['api_developer', __('Developers API Tool')],
                            ];

                            foreach ($tools as $tool):
                                [$field, $label] = $tool;
                            ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card feature-card h-100">
                                        <div class="card-body">
                                            <div class="form-check form-switch">
                                                <?= $this->Form->checkbox($field, [
                                                    'class' => 'form-check-input',
                                                    'role' => 'switch',
                                                    'id' => $field,
                                                    'required' => false
                                                ]); ?>
                                                <label class="form-check-label m-1 fw-semibold" for="<?= $field ?>">
                                                    <?= $label ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                        <div class="alert alert-info mb-0 py-2 px-3 small">
                            <i class="fa fa-info-circle me-1"></i>
                            * <?= __("This feature requires the visitor to be logged in to take effect.") ?>
                        </div>
                        <?= $this->Form->button('<i class="fa fa-check me-2"></i>' . __('Create Plan'), [
                            'class' => 'btn btn-lg btn-submit-gradient text-white fw-bold px-5',
                            'escape' => false
                        ]); ?>
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
        CKEDITOR.replaceClass = 'text-editor';
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
        CKEDITOR.dtd.$removeEmpty['i'] = false;
    });
</script>
<?php $this->end(); ?>