<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 * @var \App\Model\Entity\Plan $logged_user_plan
 *
 */
?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language(null) ?>">

<head>
    <?= $this->Html->charset(); ?>
    <title><?= h($this->fetch('title')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($this->fetch('description')); ?>">

    <?= $this->Assets->favicon() ?>

    <!--plugins-->
    <link href="<?= $this->Url->build('/asset/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/asset/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/asset/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />
    <link href="<?= $this->Url->build('/asset/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>" rel="stylesheet" />

    <!-- loader-->
    <link href="<?= $this->Url->build('/asset/css/pace.min.css') ?>" rel="stylesheet" />
    <script src="<?= $this->Url->build('/asset/js/pace.min.js') ?>"></script>

    <!-- Bootstrap CSS -->
    <link href="<?= $this->Url->build('/asset/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= $this->Url->build('/asset/css/bootstrap-extended.css') ?>" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- App CSS -->
    <link href="<?= $this->Url->build('/asset/css/app.css') ?>" rel="stylesheet">
    <link href="<?= $this->Url->build('/asset/css/icons.css') ?>" rel="stylesheet">

    <?= $this->fetch('meta'); ?>
    <?= $this->fetch('css'); ?>
    <?= $this->fetch('script'); ?>

    <?= get_option('member_head_code'); ?>

    <?= $this->fetch('scriptTop') ?>

    <style>
        /* Fix CKEditor display in Dashtrans template */
        .cke {
            visibility: visible !important;
            display: block !important;
            z-index: 1 !important;
        }

        .cke_chrome {
            border: 1px solid #d2d6de !important;
            visibility: visible !important;
        }

        .cke_inner {
            display: block !important;
            visibility: visible !important;
        }

        .cke_contents {
            min-height: 200px !important;
            visibility: visible !important;
        }

        .cke_top {
            background: #f4f4f4 !important;
            border-bottom: 1px solid #d2d6de !important;
            visibility: visible !important;
        }

        .cke_toolbar {
            visibility: visible !important;
        }

        .cke_button {
            visibility: visible !important;
        }

        /* Ensure textarea is visible before replacement */
        .text-editor {
            min-height: 200px;
            display: block !important;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {
            font-family: 'Roboto', 'Be Vietnam Pro', sans-serif !important;
        }

        /* Fixed footer at bottom */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 250px;
            right: 0;
            backdrop-filter: blur(10px);
            padding: 10px 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            z-index: 100;
            transition: all 0.3s ease;
        }

        /* Adjust footer when sidebar is toggled */
        .wrapper.toggled .page-footer {
            left: 70px;
        }

        /* Add padding to page content to prevent overlap with fixed footer */
        .page-wrapper {
            padding-bottom: 60px;
        }

        /* For mobile devices */
        @media (max-width: 1024px) {
            .page-footer {
                left: 0;
            }
        }
    </style>
</head>

<body class="bg-theme bg-theme2">
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">

                <div>
                    <h4 class="logo-text"><?= get_option('site_name') ?></h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>">
                        <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                        <div class="menu-title">Hướng dẫn cơ bản</div>
                    </a>
                </li>

                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-data'></i></div>
                        <div class="menu-title">Quảng Cáo</div>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo $this->Url->build(['controller' => 'TrafficCampaigns', 'action' => 'index']); ?>">
                                <i class='bx bx-radio-circle'></i>Danh Sách
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Url->build(['controller' => 'TrafficCampaigns', 'action' => 'create']); ?>">
                                <i class='bx bx-radio-circle'></i>Create
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="<?= $this->Url->build(['_name' => 'buyer_invoices']) ?>">
                        <div class="parent-icon"><i class='bx bx-credit-card'></i></div>
                        <div class="menu-title">Hóa Đơn</div>
                    </a>
                </li>

                <li class="menu-label">Cài Đặt</li>

                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-cog'></i></div>
                        <div class="menu-title">Cài Đặt</div>
                    </a>
                    <ul>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'buyer_profile']) ?>">
                                <i class='bx bx-radio-circle'></i>Hồ sơ
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'buyer_change-password']) ?>">
                                <i class='bx bx-radio-circle'></i>Đổi Mật Khẩu
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'buyer_change-email']) ?>">
                                <i class='bx bx-radio-circle'></i>Đổi Email
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="<?= $this->Url->build(['_name' => 'buyer_form_support']) ?>">
                        <div class="parent-icon"><i class='bx bx-support'></i></div>
                        <div class="menu-title">Hỗ Trợ</div>
                    </a>
                </li>

                <?php if ($logged_user->role == 'admin') : ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cog'></i></div>
                            <div class="menu-title">Cài Đặt Chung</div>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= $this->Url->build(['_name' => 'buyer_option_packages']) ?>">
                                    <i class='bx bx-radio-circle'></i>Packages
                                </a>
                            </li>
                            <li>
                                <a href="<?= $this->Url->build(['_name' => 'buyer_option_notification']) ?>">
                                    <i class='bx bx-radio-circle'></i>Trang thông báo
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->

        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand gap-3">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <?php if (in_array($logged_user->role, ['admin'])) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= $this->Url->build([
                                                                    'controller' => 'Users',
                                                                    'action' => 'dashboard',
                                                                    'prefix' => 'admin',
                                                                ]); ?>">
                                        <i class='bx bx-home'></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (count(get_site_languages(true)) > 1) : ?>
                                <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
                                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                                        <i class='bx bx-globe'></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php foreach (get_site_languages(true) as $lang) : ?>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center py-2" href="<?= $this->request->getPath() . '?lang=' . $lang ?>">
                                                    <span class="ms-2"><?= locale_get_display_name($lang, $lang) ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="user-box dropdown px-3">
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z" />
                            </svg>
                            <div class="user-info">
                                <p class="user-name mb-0"><?= h($logged_user->first_name); ?></p>
                                <p class="designattion mb-0">Buyer</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= $this->Url->build(['_name' => 'buyer_profile']) ?>">
                                    <i class="bx bx-user fs-5"></i><span><?= __('Profile') ?></span>
                                </a>
                            </li>

                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= $this->Url->build([
                                                                                                'controller' => 'Users',
                                                                                                'action' => 'logout',
                                                                                                'prefix' => 'auth',
                                                                                            ]); ?>">
                                    <i class="bx bx-log-out-circle"></i><span><?= __('Log out') ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <section class=" mb-3 border-bottom content-header d-flex justify-content-between align-items-center">
                    <h1 style="font-size: 24px;"><?= h($this->fetch('content_title')); ?></h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> <?= __('Dashboard') ?></a></li>
                        <li><i style="font-size: 7px; margin: 0 5px;" class="fa-solid fa-chevron-right"></i></li>
                        <li class="active"><?= h($this->fetch('content_title')); ?></li>
                    </ol>
                </section>

                <?php if (!$logged_user_plan->disable_ads && !empty(get_option('ad_member'))) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card radius-10">
                                <div class="card-body text-center">
                                    <?= get_option('ad_member'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>

            </div>
        </div>
        <!--end page wrapper -->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javascript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <footer class="page-footer">
            <p class="mb-0"><?= __('Copyright ©') ?> <?= h(get_option('site_name')) ?> <?= date("Y") ?></p>
        </footer>
    </div>
    <!--end wrapper-->

    <!--start switcher-->
    <!-- <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i></div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase"><?= __('Theme Customizer') ?></h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <p class="mb-0"><?= __('Gaussian Texture') ?></p>
            <hr>
            <ul class="switcher">
                <li id="theme1"></li>
                <li id="theme2"></li>
                <li id="theme3"></li>
                <li id="theme4"></li>
                <li id="theme5"></li>
                <li id="theme6"></li>
            </ul>
            <hr>
            <p class="mb-0"><?= __('Gradient Background') ?></p>
            <hr>
            <ul class="switcher">
                <li id="theme7"></li>
                <li id="theme8"></li>
                <li id="theme9"></li>
                <li id="theme10"></li>
                <li id="theme11"></li>
                <li id="theme12"></li>
                <li id="theme13"></li>
                <li id="theme14"></li>
                <li id="theme15"></li>
            </ul>
        </div>
    </div> -->
    <!--end switcher-->

    <?= $this->element('js_vars'); ?>

    <script data-cfasync="false" src="<?= $this->Assets->url('/js/ads.js?ver=' . APP_VERSION) ?>"></script>

    <!-- Bootstrap JS -->
    <script src="<?= $this->Url->build('/asset/js/bootstrap.bundle.min.js') ?>"></script>
    <!--plugins-->
    <script src="<?= $this->Url->build('/asset/js/jquery.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/simplebar/js/simplebar.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/apexcharts-bundle/js/apexcharts.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>

    <!--app JS-->
    <script src="<?= $this->Url->build('/asset/js/app.js') ?>"></script>

    <script>
        $(document).ready(function() {
            // Initialize metismenu
            $('#menu').metisMenu();

            // Mobile toggle menu
            $('.mobile-toggle-menu').on('click', function() {
                $('.wrapper').toggleClass('toggled');
            });

            // Sidebar toggle
            $('.toggle-icon').on('click', function() {
                $('.wrapper').toggleClass('toggled');
            });

            // Search functionality
            $('.search-show').on('click', function() {
                $('.search-bar-box').addClass('show');
                $('.search-control').focus();
            });

            $('.search-close').on('click', function() {
                $('.search-bar-box').removeClass('show');
                $('.search-control').val('');
            });

            // Overlay click to close sidebar on mobile
            $('.overlay').on('click', function() {
                $('.wrapper').removeClass('toggled');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() < 1025) {
                    if (!$(e.target).closest('.sidebar-wrapper, .mobile-toggle-menu').length) {
                        $('.wrapper').removeClass('toggled');
                    }
                }
            });

            // Back to top button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('.back-to-top').fadeIn();
                } else {
                    $('.back-to-top').fadeOut();
                }
            });

            $('.back-to-top').on('click', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
                return false;
            });

            // Theme switcher
            $('.switcher-btn').on('click', function() {
                $('.switcher-wrapper').toggleClass('switcher-toggled');
            });

            $('.close-switcher').on('click', function() {
                $('.switcher-wrapper').removeClass('switcher-toggled');
            });

            // Theme colors
            $('#theme1').click(function() {
                $('html').attr('class', 'bg-theme bg-theme1');
            });
            $('#theme2').click(function() {
                $('html').attr('class', 'bg-theme bg-theme2');
            });
            $('#theme3').click(function() {
                $('html').attr('class', 'bg-theme bg-theme3');
            });
            $('#theme4').click(function() {
                $('html').attr('class', 'bg-theme bg-theme4');
            });
            $('#theme5').click(function() {
                $('html').attr('class', 'bg-theme bg-theme5');
            });
            $('#theme6').click(function() {
                $('html').attr('class', 'bg-theme bg-theme6');
            });
            $('#theme7').click(function() {
                $('html').attr('class', 'bg-theme bg-theme7');
            });
            $('#theme8').click(function() {
                $('html').attr('class', 'bg-theme bg-theme8');
            });
            $('#theme9').click(function() {
                $('html').attr('class', 'bg-theme bg-theme9');
            });
            $('#theme10').click(function() {
                $('html').attr('class', 'bg-theme bg-theme10');
            });
            $('#theme11').click(function() {
                $('html').attr('class', 'bg-theme bg-theme11');
            });
            $('#theme12').click(function() {
                $('html').attr('class', 'bg-theme bg-theme12');
            });
            $('#theme13').click(function() {
                $('html').attr('class', 'bg-theme bg-theme13');
            });
            $('#theme14').click(function() {
                $('html').attr('class', 'bg-theme bg-theme14');
            });
            $('#theme15').click(function() {
                $('html').attr('class', 'bg-theme bg-theme15');
            });
        });
    </script>

    <?= $this->fetch('scriptBottom') ?>
</body>

</html>