<?php

/**
 * @var \App\View\AppView $this
 */
?>
<!doctype html>
<html lang="<?= locale_get_primary_language(null) ?: 'en' ?>">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($this->fetch('description')); ?>">
    <meta name="keywords" content="<?= h(get_option('seo_keywords')); ?>">

    <title><?= h($this->fetch('title')); ?></title>

    <!--favicon-->
    <?= $this->Assets->favicon() ?>

    <!--plugins từ assets-->
    <link href="<?= $this->Url->build('/asset/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/asset/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/asset/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />

    <!-- loader-->
    <link href="<?= $this->Url->build('/asset/css/pace.min.css') ?>" rel="stylesheet" />
    <script src="<?= $this->Url->build('/asset/js/pace.min.js') ?>"></script>

    <!-- Bootstrap CSS từ asset -->
    <link href="<?= $this->Url->build('/asset/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= $this->Url->build('/asset/css/bootstrap-extended.css') ?>" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- App CSS từ asset -->
    <link href="<?= $this->Url->build('/asset/css/app.css') ?>" rel="stylesheet">
    <link href="<?= $this->Url->build('/asset/css/icons.css') ?>" rel="stylesheet">
    <?= $this->fetch('meta'); ?>
    <?= $this->fetch('css'); ?>
    <?= $this->fetch('script'); ?>

    <?= get_option('auth_head_code'); ?>
    <?= $this->fetch('scriptTop') ?>

    <style>
        /* Dashtrans Theme Styles */
        :root {
            --bs-primary: #0d6efd;
            --bs-font-sans-serif: 'Roboto', sans-serif;
        }

        body {
            font-family: 'Roboto', sans-serif;
            font-size: 0.875rem;
        }

        /* Background Themes */
        .bg-theme {
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .bg-theme2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Card Styles */
        .card {
            border: 0;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Styles */
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-select {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
        }

        .input-group-text {
            border-color: #ced4da;
            background-color: transparent;
            cursor: pointer;
        }

        .border-end-0 {
            border-right: 0 !important;
        }

        /* Button Styles */
        .btn {
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            font-weight: 500;
        }

        .btn-light {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }

        .btn-light:hover {
            background: linear-gradient(135deg, #5568d3 0%, #63408a 100%);
            color: white;
        }

        /* Social Login */
        .login-separater {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }

        .login-separater span {
            background: #fff;
            padding: 0 1rem;
            position: relative;
            z-index: 1;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .login-separater hr {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            margin: 0;
            border: 0;
            border-top: 1px solid #dee2e6;
            z-index: 0;
        }

        .contacts-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            margin: 0 5px;
            border-radius: 50%;
            transition: all 0.3s;
            font-size: 1.25rem;
        }

        .contacts-social a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .bg-light {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Wrapper */
        .wrapper {
            min-height: 100vh;
        }

        .section-authentication-signin {
            min-height: 100vh;
        }

        /* Checkbox & Switch */
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* Links */
        a {
            color: #667eea;
            text-decoration: none;
        }

        a:hover {
            color: #5568d3;
        }

        /* Alert/Flash Messages */
        .alert {
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        /* Captcha */
        .captcha {
            margin: 1rem 0;
        }
    </style>
</head>

<body class="bg-theme bg-theme2">
    <!--wrapper-->
    <div class="wrapper">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>
    <!--end wrapper-->

    <?= $this->element('js_vars'); ?>

    <!-- Bootstrap JS từ assets -->
    <script src="<?= $this->Url->build('/asset/js/bootstrap.bundle.min.js') ?>"></script>

    <!--plugins từ assets-->
    <script src="<?= $this->Url->build('/asset/js/jquery.min.js') ?>"></script>

    <!-- Custom JS -->
    <script data-cfasync="false" src="<?= $this->Assets->url('/js/ads.js?ver=' . APP_VERSION) ?>"></script>

    <?php if (!((bool)get_option('combine_minify_css_js', false))) : ?>
        <?= $this->Assets->script('/vendor/clipboard.min.js?ver=' . APP_VERSION); ?>
        <?= $this->Assets->script('/js/app.js?ver=' . APP_VERSION); ?>
    <?php endif; ?>

    <?= $this->fetch('scriptBottom') ?>
</body>

</html>