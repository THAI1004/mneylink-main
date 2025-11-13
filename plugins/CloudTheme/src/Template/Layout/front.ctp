<?php

/**
 * @var \App\View\AppView $this
 */
?>
<?php $user = $this->request->getSession()->read('Auth.User'); ?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language(null) ?>">

<head>
    <?= $this->Html->charset(); ?>
    <title><?= h($this->fetch('title')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= h($this->fetch('description')); ?>">
    <meta name="keywords" content="<?= h(get_option('seo_keywords')); ?>">

    <!-- Favicons -->
    <link href="<?= $this->Url->build('/img/favicon.png') ?>" rel="icon" />
    <link href="<?= $this->Url->build('/img/apple-touch-icon.png') ?>" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/" rel="preconnect" />
    <link href="https://fonts.gstatic.com/" rel="preconnect" crossorigin="" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Questrial:wght@400&display=swap" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="<?= $this->Url->build('/vendors/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/vendors/bootstrap-icons.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/vendors/glightbox.min.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/vendors/swiper-bundle.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Main CSS File -->
    <link href="<?= $this->Url->build('/assets/css/main.css') ?>" rel="stylesheet" />

    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <?= get_option('head_code'); ?>
    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="index-page <?= ($this->request->getParam('_name') === 'home') ? 'home' : 'inner-page' ?>">
    <?= get_option('after_body_tag_code'); ?>

    <!-- Header -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
            <?php
            $logo = get_logo();
            $class = '';
            if ($logo['type'] == 'image') {
                $class = 'logo-image';
            }
            ?>
            <a href="<?= build_main_domain_url('/'); ?>" class="logo d-flex align-items-center <?= $class ?>">
                <?php if ($logo['type'] == 'image'): ?>
                    <?= $logo['content'] ?>
                <?php else: ?>
                    <h1 class="sitename"><?= $logo['content'] ?></h1>
                <?php endif; ?>
            </a>

            <nav id="navmenu" class="navmenu">
                <?=
                menu_display('menu_main', [
                    'ul_class' => '',
                    'li_class' => '',
                    'a_class' => '',
                ], true);
                ?>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>

    <!-- Footer -->

    <?= $this->element('front_footer'); ?>



    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Scripts -->
    <script data-cfasync="false" src="<?= $this->Assets->url('/js/ads.js?ver=' . APP_VERSION) ?>"></script>
    <script src="<?= $this->Url->build('/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/js/jquery.min.js') ?>"></script>
    <!-- Vendor JS Files -->
    <script src="<?= $this->Url->build('/vendors/glightbox.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/swiper-bundle.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/purecounter_vanilla.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/imagesloaded.pkgd.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/isotope.pkgd.min.js') ?>"></script>

    <?= $this->element('js_vars'); ?>

    <!-- Main JS File -->
    <script src="<?= $this->Url->build('/assets/js/main.js') ?>"></script>
    <?= $this->Assets->script('/vendor/clipboard.min.js?ver=' . APP_VERSION); ?>
    <?= $this->Assets->script('app.js?ver=' . APP_VERSION); ?>

    <?= $this->fetch('scriptBottom') ?>
    <?= get_option('footer_code'); ?>
</body>

</html>