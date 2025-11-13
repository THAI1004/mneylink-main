<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 */
$this->assign('title', __('Developers API'));
$this->assign('description', '');
$this->assign('content_title', __('Developers API'));
?>

<div class="card shadow mb-5">
    <div class="card-body p-4">

        <div class="alert alert-info border-info" role="alert">
            <h4 class="alert-heading text-white fw-bold mb-3"><?= __('Your API token:') ?></h4>
            <p class="mb-0">
            <pre class="p-3 bg-white border border-dark rounded text-dark fw-bold text-break fs-6"><?= $logged_user->api_token ?></pre>
            </p>
        </div>

        <hr class="my-4">

        <p class="lead">
            <?= __(
                'For developers {0} prepared <b>API</b> which returns responses in <b>JSON</b> or ' .
                    '<b>TEXT</b> formats. ',
                get_option('site_name')
            ) ?>
        </p>

        <p><?= __('Currently there is one method which can be used to shorten links on behalf of your account.') ?></p>
        <p><?= __('All you have to do is to send a <b>GET</b> request with your API token and URL Like the following:') ?></p>

        <div class="p-3 bg-light rounded shadow-sm mb-3">
            <code class="text-break text-white d-block">
                <?= $this->Url->build('/', true); ?>api?api=
                <b class="text-danger"><?= $logged_user->api_token ?></b>
                &url=
                <b class="text-warning"><?= urlencode('yourdestinationlink.com') ?></b>
                &alias=
                <b class="text-primary">CustomAlias</b>
            </code>
        </div>

        <p><?= __('You will get a JSON response like the following') ?></p>

        <div class="p-3 bg-dark text-white rounded shadow-sm mb-4">
            <pre class="mb-0 text-break">{"status":"success","shortenedUrl":"<?= json_encode($this->Url->build('/', true) . 'xxxxx') ?>"}</pre>
        </div>

        <p><?= __('If you want a TEXT response just add <b>&format=text</b> at the end of your request as ' .
                'the below example. This will return just the short link. Note that if an error occurs, it will not ' .
                'output anything.') ?></p>

        <div class="p-3 bg-light rounded shadow-sm mb-4">
            <code class="text-break text-white d-block">
                <?= $this->Url->build('/', true); ?>api?api=
                <b class="text-danger"><?= $logged_user->api_token ?></b>
                &url=
                <b class="text-warning"><?= urlencode('yourdestinationlink.com') ?></b>
                &alias=
                <b class="text-primary">CustomAlias</b>
                &format=
                <b class="text-info">text</b>
            </code>
        </div>


        <?php
        $allowed_ads = get_allowed_ads();
        unset($allowed_ads[get_option('member_default_advert', 1)]);
        ?>

        <?php if (array_key_exists(1, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API with the interstitial advertising add the below code to the end of the URL") ?></p>
            <pre class="p-2 bg-warning bg-opacity-10 border border-warning rounded">&type=1</pre>
        <?php endif; ?>

        <?php if (array_key_exists(2, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API with the banner advertising add the below code to the end of the URL") ?></p>
            <pre class="p-2 bg-warning bg-opacity-10 border border-warning rounded">&type=2</pre>
        <?php endif; ?>

        <?php if (array_key_exists(0, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API without advertising add the below code to the end of the URL") ?></p>
            <pre class="p-2 bg-warning bg-opacity-10 border border-warning rounded">&type=0</pre>
        <?php endif; ?>

        <div class="alert alert-warning mt-4 border-warning" role="alert">
            <h4 class="alert-heading text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= __("Note") ?></h4>
            <p class="mb-0 text-white"><?= __("api & url are required fields and the other fields like alias, format & type are optional.") ?></p>
        </div>

        <p class="mt-4 fs-5  text-white"><?= __("That's it :)") ?></p>

        <hr class="my-4">

        <h3 class="mt-4 text-primary"><i class="bi bi-filetype-php me-2"></i><?= __("Using the API in PHP") ?></h3>

        <p><?= __("To use the API in your PHP application, you need to send a GET request via " .
                "file_get_contents or cURL. Please check the below sample examples using file_get_contents") ?></p>

        <h5 class="mt-4 mb-3 text-secondary"><?= __("Using JSON Response") ?></h5>

        <pre class="p-3 bg-dark text-white rounded shadow-sm mb-4">
$long_url = urlencode('<b class="text-success">yourdestinationlink.com</b>');
$api_token = '<b class="text-danger"><?= $logged_user->api_token ?></b>';
$api_url = "<?= $this->Url->build('/', true); ?>api?api={<b class="text-danger">$api_token</b>}&url={<b class="text-success">$long_url</b>}&alias={<b class="text-primary">CustomAlias</b>}";
$result = @json_decode(file_get_contents($api_url),TRUE);
if($result["status"] === 'error') {
&emsp;echo $result["message"];
} else {
&emsp;echo $result["shortenedUrl"];
}</pre>

        <h5 class="mt-4 mb-3 text-secondary"><?= __("Using Plain Text Response") ?></h5>

        <pre class="p-3 bg-dark text-white rounded shadow-sm mb-4">
$long_url = urlencode('<b class="text-success">yourdestinationlink.com</b>');
$api_token = '<b class="text-danger"><?= $logged_user->api_token ?></b>';
$api_url = "<?= $this->Url->build('/', true); ?>api?api={<b class="text-danger">$api_token</b>}&url={<b class="text-success">$long_url</b>}&alias={<b class="text-primary">CustomAlias</b>}&format=<b class="text-info">text</b>";
$result = @file_get_contents($api_url);
if( $result ){
&emsp;echo $result;
}</pre>

    </div>
</div>