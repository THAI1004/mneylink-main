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

    <!-- Main CSS File -->
    <link href="<?= $this->Url->build('/assets/css/main.css') ?>" rel="stylesheet" />

    <?= $this->Assets->css('/vendor/font-awesome/css/font-awesome.min.css?ver=' . APP_VERSION); ?>
    <?= $this->Assets->css('/vendor/animate.min.css?ver=' . APP_VERSION); ?>
    <?= $this->Assets->css('app.css?ver=' . APP_VERSION); ?>

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
    <footer id="footer" class="footer position-relative light-background">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <div class="footer-content">
                        <?php
                        $footer_logo = get_logo();
                        ?>
                        <a href="<?= build_main_domain_url('/'); ?>" class="logo d-flex align-items-center mb-4">
                            <?php if ($footer_logo['type'] == 'image'): ?>
                                <?= $footer_logo['content'] ?>
                            <?php else: ?>
                                <span class="sitename"><?= $footer_logo['content'] ?></span>
                            <?php endif; ?>
                        </a>
                        <p class="mb-4">
                            <?= h(get_option('site_description', 'Your website description here')) ?>
                        </p>

                        <div class="newsletter-form">
                            <h5><?= __('Stay Updated') ?></h5>
                            <form action="#" method="post" class="php-email-form">
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" placeholder="<?= __('Enter your email') ?>" required />
                                    <button type="submit" class="btn-subscribe">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                                <div class="loading"><?= __('Loading') ?></div>
                                <div class="error-message"></div>
                                <div class="sent-message"><?= __('Thank you for subscribing!') ?></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="footer-links">
                        <h4><?= __('Company') ?></h4>
                        <?=
                        menu_display('menu_footer', [
                            'ul_class' => '',
                            'li_class' => '',
                            'a_class' => '',
                        ]);
                        ?>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="footer-links">
                        <h4><?= __('Quick Links') ?></h4>
                        <ul>
                            <li><a href="<?= $this->Url->build('/') ?>"><i class="bi bi-chevron-right"></i> <?= __('Home') ?></a></li>
                            <li><a href="<?= $this->Url->build('/about') ?>"><i class="bi bi-chevron-right"></i> <?= __('About') ?></a></li>
                            <li><a href="<?= $this->Url->build('/services') ?>"><i class="bi bi-chevron-right"></i> <?= __('Services') ?></a></li>
                            <li><a href="<?= $this->Url->build('/contact') ?>"><i class="bi bi-chevron-right"></i> <?= __('Contact') ?></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="footer-contact">
                        <h4><?= __('Get in Touch') ?></h4>

                        <?php if (get_option('contact_address')): ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="contact-info">
                                    <p><?= h(get_option('contact_address')) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (get_option('contact_phone')): ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="contact-info">
                                    <p><?= h(get_option('contact_phone')) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (get_option('contact_email')): ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="contact-info">
                                    <p><?= h(get_option('contact_email')) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="social-links">
                            <?php if (get_option('facebook_url')): ?>
                                <a href="<?= h(get_option('facebook_url')) ?>"><i class="bi bi-facebook"></i></a>
                            <?php endif; ?>
                            <?php if (get_option('twitter_url')): ?>
                                <a href="<?= h(get_option('twitter_url')) ?>"><i class="bi bi-twitter-x"></i></a>
                            <?php endif; ?>
                            <?php if (get_option('linkedin_url')): ?>
                                <a href="<?= h(get_option('linkedin_url')) ?>"><i class="bi bi-linkedin"></i></a>
                            <?php endif; ?>
                            <?php if (get_option('youtube_url')): ?>
                                <a href="<?= h(get_option('youtube_url')) ?>"><i class="bi bi-youtube"></i></a>
                            <?php endif; ?>
                            <?php if (get_option('github_url')): ?>
                                <a href="<?= h(get_option('github_url')) ?>"><i class="bi bi-github"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="copyright">
                            <p>
                                © <span><?= __('Copyright') ?></span>
                                <strong class="px-1 sitename"><?= h(get_option('site_name')) ?></strong>
                                <span><?= date("Y") ?> - <?= __('All Rights Reserved') ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="footer-bottom-links">
                            <a href="<?= $this->Url->build('/privacy-policy') ?>"><?= __('Privacy Policy') ?></a>
                            <a href="<?= $this->Url->build('/terms-of-service') ?>"><?= __('Terms of Service') ?></a>
                            <a href="<?= $this->Url->build('/cookie-policy') ?>"><?= __('Cookie Policy') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Scripts -->
    <script data-cfasync="false" src="<?= $this->Assets->url('/js/ads.js?ver=' . APP_VERSION) ?>"></script>
    <?= $this->Assets->script('/vendor/jquery.min.js?ver=' . APP_VERSION); ?>
    <?= $this->Assets->script('/vendor/bootstrap/js/bootstrap.min.js?ver=' . APP_VERSION); ?>
    <?= $this->Assets->script('/vendor/wow.min.js?ver=' . APP_VERSION); ?>
    <?= $this->Assets->script('/vendor/clipboard.min.js?ver=' . APP_VERSION); ?>

    <!-- Vendor JS Files -->
    <script src="<?= $this->Url->build('/vendors/glightbox.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/swiper-bundle.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/purecounter_vanilla.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/imagesloaded.pkgd.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/vendors/isotope.pkgd.min.js') ?>"></script>

    <?= $this->element('js_vars'); ?>

    <!-- Main JS File -->
    <script src="<?= $this->Url->build('/assets/js/main.js') ?>"></script>
    <?= $this->Assets->script('app.js?ver=' . APP_VERSION); ?>

    <?= $this->fetch('scriptBottom') ?>
    <?= get_option('footer_code'); ?>
</body>

</html>