<?php

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Contact Us'));
$this->assign('description', '');
$this->assign('content_title', __('Contact Us'));

?>

<!-- Header -->
<div class="mt-5">
    <div class="section-inner">
        <div class="container">
            <div class="intro-text">
                <h1 class="text-primary fw-bold h1 intro-lead-in text-center mb-3"><?= __('Contact Us') ?>
            </div>
        </div>
    </div>
</div>


<div id="contact" class="mb-5">
    <div class="container">
        <?= $this->element('contact'); ?>
    </div>
</div>