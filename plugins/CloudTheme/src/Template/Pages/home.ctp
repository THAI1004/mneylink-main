<?php
$this->assign('title', (get_option('site_meta_title')) ?: get_option('site_name'));
$this->assign('description', get_option('description'));
$this->assign('content_title', get_option('site_name'));
?>
<style>
    /* 1. Thiết lập Transition (Hiệu ứng chuyển đổi mượt mà) */
    /* Áp dụng Transition cho cả khung (.step) và icon (.icon-container) */
    .step,
    .icon-container {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    /* 2. Hiệu ứng Hover áp dụng lên Khung (.step) */
    /* Khi hover vào ô chứa (.col-sm-4), tác động lên khung bên trong (.step) */
    .col-sm-4:hover .step {
        /* Làm nổi khung (tăng đổ bóng) */
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.25) !important;

        /* Tùy chọn: Nhấc khung lên một chút (dịch chuyển theo trục Y) */
        transform: translateY(-5px);
    }

    /* 3. Hiệu ứng Hover áp dụng lên Icon (Phóng to Icon) */
    /* Khi hover vào ô chứa (.col-sm-4), tác động lên icon (.icon-container) */
    .col-sm-4:hover .icon-container {
        /* Phóng to icon lên 1.1 lần (110%) */
        transform: scale(1.1);
    }

    .feature,
    .feature-img {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    /* 2. Hiệu ứng Hover áp dụng lên Khung (.feature) */
    /* Khi hover vào ô chứa (.col-sm-4), tác động lên khung bên trong (.feature) */
    .col-sm-4:hover .feature {
        /* Làm nổi khung (tăng đổ bóng mạnh hơn) */
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.25) !important;

        /* Nhấc khung lên một chút */
        transform: translateY(-5px);
    }

    /* 3. Hiệu ứng Hover áp dụng lên Icon (.feature-img) */
    /* Khi hover vào ô chứa (.col-sm-4), tác động lên icon container bên trong */
</style>
<section id="hero" class="hero section bg-light py-5 py-md-6">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-3 fw-bold mb-4">
                        <?= __('Shorten URLs and') ?>
                        <span class="text-primary"><?= __('earn money') ?></span>
                    </h1>

                    <div class="shorten-form-wrapper  justify-content-center mt-4 p-4 shadow-lg rounded-3">
                        <?php if (get_option('home_shortening') == 'yes') : ?>
                            <?= $this->element('shorten'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-image text-center">
                </div>
            </div>
        </div>
    </div>
</section>
<section class="steps p-5 bg-white">
    <div class="container text-center">

        <div class="row justify-content-center my-4">

            <div class="col-sm-4 p-3">
                <div class="step p-4 h-100 shadow-sm rounded-3">
                    <div class="icon-container d-flex justify-content-center align-items-center mx-auto mb-3 text-dark p-2">
                        <i class="bi bi-person-plus-fill fs-3"></i>
                    </div>
                    <h4 class="step-heading h4 fw-bold mt-2 text-dark"><?= __('Create an account') ?></h4>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="step p-4 h-100 shadow-sm rounded-3">
                    <div class="icon-container d-flex justify-content-center align-items-center mx-auto mb-3 text-dark p-2">
                        <i class="bi bi-link-45deg fs-3"></i>
                    </div>
                    <h4 class="step-heading h4 fw-bold mt-2 text-dark"><?= __('Shorten your link') ?></h4>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="step p-4 h-100 shadow-sm rounded-3">
                    <div class="icon-container d-flex justify-content-center align-items-center mx-auto mb-3 text-dark p-2">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <h4 class="step-heading h4 fw-bold  text-dark"><?= __('Earn Money') ?></h4>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="features py-2  border-top ">
    <div class="container text-center">
        <div class="section-title mb-1">
            <h3 class="section-subheading text-primary fw-semibold text-uppercase mb-2"><?= __('Earn extra money') ?></h3>
            <h2 class="section-heading h2 fw-bolder text-dark"><?= __('Why join us?') ?></h2>
        </div>

        <div class="row justify-content-center">

            <div class="col-sm-4 p-3">
                <div class="feature  p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-circle-question fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('What is {0}?', h(get_option('site_name'))) ?></h4>
                    <div class="feature-content text-secondary">
                        <?= __(
                            '{0} is a completely free tool where you can create short links, which apart from being free, you get paid! So, now you can make money from home, when managing and protecting your links.',
                            h(get_option('site_name'))
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-hand-holding-dollar fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('How and how much do I earn?') ?></h4>
                    <div class="feature-content text-secondary">
                        <?= __(
                            "How can you start making money with {0}? It's just 3 steps: create an account, create a link and post it - for every visit, you earn money. It's just that easy!",
                            h(get_option('site_name'))
                        ) ?>
                    </div>
                </div>
            </div>

            <?php if ((bool)get_option('enable_referrals', 1)) : ?>
                <div class="col-sm-4 p-3">
                    <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                        <div class="feature-icon mb-3 text-primary">
                            <i class="fa-solid fa-user-group fa-2x"></i>
                        </div>
                        <h4 class="feature-heading h4 fw-bold">
                            <?= __(
                                '{0}% Referral Bonus',
                                h(get_option('referral_percentage'))
                            ) ?>
                        </h4>
                        <div class="feature-content text-secondary">
                            <?= __(
                                'The {0} referral program is a great way to spread the word of this great service and to earn even more money with your short links! Refer friends and receive {1}% of their earnings for life!',
                                [h(get_option('site_name')), h(get_option('referral_percentage'))]
                            ) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-sm-4 p-3">
                <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-gauge-high fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('Featured Administration Panel') ?></h4>
                    <div class="feature-content text-secondary"><?= __('Control all of the features from the administration panel with a click of a button.') ?></div>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-chart-line fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('Detailed Stats') ?></h4>
                    <div class="feature-content text-secondary"><?= __('Know your audience. Analyse in detail what brings you the most income and what strategies you should adapt.') ?></div>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-hand-holding-dollar fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('Low Minimum Payout') ?></h4>
                    <div class="feature-content text-secondary">
                        <?= __(
                            'You are required to earn only {0} before you will be paid. We can pay all users via their PayPal.',
                            display_price_currency(get_option('minimum_withdrawal_amount'))
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-hand-holding-dollar fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('Highest Rates') ?></h4>
                    <div class="feature-content text-secondary"><?= __('Make the most out of your traffic with our always increasing rates.') ?></div>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-code fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('API') ?></h4>
                    <div class="feature-content text-secondary"><?= __('Shorten links more quickly with easy to use API and bring your creative and advanced ideas to life.') ?></div>
                </div>
            </div>

            <div class="col-sm-4 p-3">
                <div class="feature p-4 h-100 bg-white border rounded-3 shadow-sm">
                    <div class="feature-icon mb-3 text-primary">
                        <i class="fa-solid fa-headset fa-2x"></i>
                    </div>
                    <h4 class="feature-heading h4 fw-bold"><?= __('Support') ?></h4>
                    <div class="feature-content text-secondary"><?= __('A dedicated support team is ready to help with any questions you may have.') ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?=
$this->cell('Testimonial', [], [
    'cache' => [
        'config' => '1day',
        'key' => 'home_testimonials_' . locale_get_default(),
    ],
])
?>

<?php if ((bool)get_option('display_home_stats', 1)) : ?>
    <section class="stats py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="section-subheading text-primary fw-semibold text-uppercase mb-2"><?= __("Numbers speak for themselves") ?></h3>
                <h2 class="section-heading h1 fw-bolder text-dark"><?= __('Fast Growing') ?></h2>
            </div>
            <div class="row">

                <div class="col-sm-4 text-center p-3">
                    <div class="stat p-4 h-100 bg-white rounded shadow-sm">
                        <div class="stat-img mb-3">
                            <i class="fa-solid fa-chart-line fa-3x text-primary"></i>
                        </div>
                        <div class="stat-num h2 fw-bold text-dark mb-0">
                            <?= $totalClicks ?>
                        </div>
                        <div class="stat-text text-uppercase text-secondary small">
                            <?= __("Total Clicks") ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 text-center p-3">
                    <div class="stat p-4 h-100 bg-white rounded shadow-sm">
                        <div class="stat-img mb-3">
                            <i class="fa-solid fa-link fa-3x text-success"></i>
                        </div>
                        <div class="stat-num h2 fw-bold text-dark mb-0">
                            <?= $totalLinks ?>
                        </div>
                        <div class="stat-text text-uppercase text-secondary small">
                            <?= __("Total Links") ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 text-center p-3">
                    <div class="stat p-4 h-100 bg-white rounded shadow-sm">
                        <div class="stat-img mb-3">
                            <i class="fa-solid fa-users fa-3x text-info"></i>
                        </div>
                        <div class="stat-num h2 fw-bold text-dark mb-0">
                            <?= $totalUsers ?>
                        </div>
                        <div class="stat-text text-uppercase text-secondary small">
                            <?= __("Registered users") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<div class="separator">
    <div class="container"></div>
</div>

<!-- Contact Section -->
<section id="contact" class="py-5  border-top">
    <div class="container">
        <div class="mb-3 text-center">
            <h3 class="section-subheading text-primary fw-semibold text-uppercase mb-2">
                <?= __("Contact Us") ?>
            </h3>

            <h2 class="section-heading h1 fw-bolder text-dark">
                <?= __("Get in touch!") ?>
            </h2>
        </div>

        <div class="row justify-content-center">
            <div class="">
                <div class="p-4 bg-white"> <?= $this->element('contact'); ?>
                </div>

            </div>
        </div>

    </div>
</section>