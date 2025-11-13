<?php

/**
 * @var \App\View\AppView $this
 * @var mixed $totalClicks
 * @var mixed $totalLinks
 * @var mixed $totalUsers
 */
$this->assign('title', (get_option('site_meta_title')) ?: get_option('site_name'));
$this->assign('description', get_option('description'));
$this->assign('content_title', get_option('site_name'));
?>

<main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1><?= __('Shorten URLs and') ?> <span><?= __('earn money') ?></span></h1>
                        <p><?= __('We have defined earning extra money from the comfort of your home.') ?></p>

                        <?php if (get_option('home_shortening') == 'yes') : ?>
                            <div class="hero-actions justify-content-center justify-content-lg-start">
                                <?= $this->element('shorten'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="<?= $this->Url->build('/img/hero-illustration.webp') ?>" class="img-fluid floating" alt="<?= h(get_option('site_name')) ?>" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Hero Section -->

    <!-- Services Section -->
    <section id="services" class="services section">
        <!-- Section Title -->
        <div class="container section-title">
            <h2><?= __('WHY JOIN US?') ?></h2>
            <p><?= __('We have defined earning extra money from the comfort of your home.') ?></p>
        </div>
        <!-- End Section Title -->

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-question-circle"></i>
                        </div>
                        <h3><?= __('What is {0}?', h(get_option('site_name'))) ?></h3>
                        <p><?= __(
                                '{0} is a completely free tool where you can create short links, which apart from being free, you get paid! So, now you can make money from home, when managing and protecting your links.',
                                h(get_option('site_name'))
                            ) ?></p>
                    </div>
                </div>
                <!-- End Service Card -->

                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h3><?= __('How and how much do I earn?') ?></h3>
                        <p><?= __(
                                "How can you start making money in {0}? It's just 3 steps: create an account, create a link and post it - for every visit, you earn money. It's just that easy!",
                                h(get_option('site_name'))
                            ) ?></p>
                    </div>
                </div>
                <!-- End Service Card -->

                <?php if ((bool)get_option('enable_referrals', 1)) : ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3><?= __(
                                    '{0}% Referral Bonus',
                                    h(get_option('referral_percentage'))
                                ) ?></h3>
                            <p><?= __(
                                    'The {0} referral program is a great way to spread the word of this great service and to earn even more money with your short links! Refer friends and receive {1}% of their earnings for life!',
                                    [h(get_option('site_name')), h(get_option('referral_percentage'))]
                                ) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- End Service Card -->

                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h3><?= __('Featured Administration Panel') ?></h3>
                        <p><?= __('Control all of the features from the administration panel with a click of a button.') ?></p>
                    </div>
                </div>
                <!-- End Service Card -->

                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <h3><?= __('Detailed Stats') ?></h3>
                        <p><?= __('Know your audience. Analyse in detail what brings you the most income and what strategies you should adapt.') ?></p>
                    </div>
                </div>
                <!-- End Service Card -->

                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h3><?= __('Low Minimum Payout') ?></h3>
                        <p><?= __(
                                'You are required to earn only {0} before you will be paid. We can pay all users via their PayPal.',
                                display_price_currency(get_option('minimum_withdrawal_amount'))
                            ) ?></p>
                    </div>
                </div>
                <!-- End Service Card -->

                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h3><?= __('Highest Rates') ?></h3>
                        <p><?= __('Make the most out of your traffic with our always increasing rates.') ?></p>
                    </div>
                </div>
                <!-- End Service Card -->

                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <h3><?= __('API') ?></h3>
                        <p><?= __('Shorten links more quickly with easy to use API and bring your creative and advanced ideas to life.') ?></p>
                    </div>
                </div>
                <!-- End Service Card -->

                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h3><?= __('Support') ?></h3>
                        <p><?= __('A dedicated support team is ready to help with any questions you may have.') ?></p>
                    </div>
                </div>
                <!-- End Service Card -->
            </div>
        </div>
    </section>
    <!-- /Services Section -->

    <?php if ((bool)get_option('display_home_stats', 1)) : ?>
        <!-- Stats Section -->
        <section id="stats" class="about section">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Content Column -->
                    <div class="col-lg-6">
                        <div class="content">
                            <h2><?= __("Fast Growing") ?></h2>
                            <p class="lead">
                                <?= __('Numbers speak for themselves') ?>
                            </p>

                            <p>
                                <?= __('Join thousands of satisfied users who are already earning money by shortening and sharing links. Our platform continues to grow rapidly with new features and higher rates.') ?>
                            </p>

                            <!-- Stats Row -->
                            <div class="stats-row">
                                <div class="stat-item">
                                    <h3>
                                        <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?= $totalClicks ?>" data-purecounter-duration="2"><?= $totalClicks ?></span>
                                    </h3>
                                    <p><?= __("Total Clicks") ?></p>
                                </div>
                                <div class="stat-item">
                                    <h3>
                                        <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?= $totalLinks ?>" data-purecounter-duration="2"><?= $totalLinks ?></span>
                                    </h3>
                                    <p><?= __("Total URLs") ?></p>
                                </div>
                                <div class="stat-item">
                                    <h3>
                                        <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?= $totalUsers ?>" data-purecounter-duration="2"><?= $totalUsers ?></span>
                                    </h3>
                                    <p><?= __("Registered users") ?></p>
                                </div>
                            </div>
                            <!-- End Stats Row -->

                            <!-- CTA Button -->
                            <div class="cta-wrapper">
                                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signup']) ?>" class="btn-cta">
                                    <span><?= __('Get Started Now') ?></span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="col-lg-6">
                        <div class="about-image">
                            <img src="<?= $this->Url->build('/img/stats-illustration.webp') ?>" alt="<?= __('Statistics') ?>" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Stats Section -->
    <?php endif; ?>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
        <!-- Section Title -->
        <div class="container section-title">
            <h2><?= __('Contact Us') ?></h2>
            <p><?= __('Get in touch') ?></p>
        </div>
        <!-- End Section Title -->

        <div class="container">
            <div class="row align-items-stretch">
                <div class="col-lg-7 order-lg-1 order-2">
                    <div class="contact-form-container">
                        <div class="form-intro">
                            <h2><?= __("Let's Start a Conversation") ?></h2>
                            <p>
                                <?= __('Have questions? We are here to help. Send us a message and we will respond as soon as possible.') ?>
                            </p>
                        </div>

                        <?= $this->element('contact'); ?>
                    </div>
                </div>

                <div class="col-lg-5 order-lg-2 order-1">
                    <div class="contact-sidebar">
                        <div class="contact-header">
                            <h3><?= __('Get in Touch') ?></h3>
                            <p><?= __('We are available to answer your questions and help you get started.') ?></p>
                        </div>

                        <div class="contact-methods">
                            <?php if (get_option('contact_address')): ?>
                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="contact-details">
                                        <span class="method-label"><?= __('Address') ?></span>
                                        <p><?= h(get_option('contact_address')) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (get_option('contact_email')): ?>
                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="contact-details">
                                        <span class="method-label"><?= __('Email') ?></span>
                                        <p><?= h(get_option('contact_email')) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (get_option('contact_phone')): ?>
                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <div class="contact-details">
                                        <span class="method-label"><?= __('Phone') ?></span>
                                        <p><?= h(get_option('contact_phone')) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="contact-details">
                                    <span class="method-label"><?= __('Hours') ?></span>
                                    <p><?= __('Monday - Friday: 9AM - 6PM') ?><br /><?= __('Saturday: 10AM - 4PM') ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="connect-section">
                            <span class="connect-label"><?= __('Connect with us') ?></span>
                            <div class="social-links">
                                <?php if (get_option('linkedin_url')): ?>
                                    <a href="<?= h(get_option('linkedin_url')) ?>" class="social-link">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (get_option('twitter_url')): ?>
                                    <a href="<?= h(get_option('twitter_url')) ?>" class="social-link">
                                        <i class="bi bi-twitter-x"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (get_option('instagram_url')): ?>
                                    <a href="<?= h(get_option('instagram_url')) ?>" class="social-link">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (get_option('facebook_url')): ?>
                                    <a href="<?= h(get_option('facebook_url')) ?>" class="social-link">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Contact Section -->
</main>