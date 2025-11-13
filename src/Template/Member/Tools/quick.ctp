<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 */
$this->assign('title', __('Quick Link'));
$this->assign('description', '');
$this->assign('content_title', __('Quick Link'));

?>

<div class="card shadow mb-4">
    <div class="card-header text-white">
        <h3 class="card-title mb-0"><?= h($this->fetch('content_title')) ?></h3>
    </div>
    <div class="card-body">

        <div class="alert alert-success text-white" role="alert">
            <h4 class="alert-heading"><?= __('Your API token:') ?></h4>
            <p class="mb-0">
            <pre class="p-2 bg-light rounded text-break"><?= $logged_user->api_token ?></pre>
            </p>
        </div>

        <p class="lead">
            <?= __('Everyone can use the shortest way to shorten links with {0}.', get_option('site_name')) ?>
        </p>

        <p>
            <?= __(
                'Just copy the link below to address bar into your web browser, change last part to ' .
                    'destination link and press ENTER. {0} will redirect you to your shortened link. Copy it wherever ' .
                    'you want and get paid.',
                get_option('site_name')
            ) ?>
        </p>

        <pre class="p-3 bg-light rounded text-break"><?= $this->Url->build('/', true); ?>st?api=<b><?= $logged_user->api_token ?></b>&url=<b>yourdestinationlink.com</b></pre>

    </div>
</div>