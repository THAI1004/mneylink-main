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
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- App CSS -->
    <link href="<?= $this->Url->build('/asset/css/app.css') ?>" rel="stylesheet">
    <link href="<?= $this->Url->build('/asset/css/icons.css') ?>" rel="stylesheet">

    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <?= get_option('admin_head_code'); ?>

    <style>
        .notification {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ffc107;
            display: inline-block;
            text-align: center;
            font-size: 12px;
            line-height: 20px;
            color: initial;
            margin-left: 4px;
        }

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

    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="bg-theme bg-theme2">
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <h4 class="logo-text"><?= h(get_option('site_name')) ?></h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
            </div>

            <!--navigation-->
            <ul class="metismenu" id="menu">
                <?php if (in_array($user['role'], is_admin())): ?>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>">
                            <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                            <div class="menu-title"><?= __('Statistics') ?></div>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-link'></i></div>
                            <div class="menu-title"><?= __('Manage Links') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Links', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('All Links') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Links', 'action' => 'hidden']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Hidden Links') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Links', 'action' => 'inactive']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Inactive Links') ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-globe'></i></div>
                        <div class="menu-title"><?= __('Traffics') ?></div>
                    </a>
                    <ul>
                        <?php if (in_array($user['role'], is_admin_camp())): ?>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Traffics', 'action' => 'add']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Thêm mới') ?></a>
                            </li>
                        <?php endif; ?>
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Traffics', 'action' => 'index']); ?>">
                                <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                        </li>
                    </ul>
                </li>

                <?php if (in_array($user['role'], is_admin())): ?>
                    <?php if (get_option('earning_mode', 'campaign') === 'campaign') : ?>
                        <li>
                            <a href="javascript:;" class="has-arrow">
                                <div class="parent-icon"><i class='bx bx-data'></i></div>
                                <div class="menu-title"><?= __('Campaigns') ?></div>
                            </a>
                            <ul>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Campaigns', 'action' => 'index']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                                </li>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Campaigns', 'action' => 'createInterstitial']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Create Interstitial Campaign') ?></a>
                                </li>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Campaigns', 'action' => 'createBanner']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Create Banner Campaign') ?></a>
                                </li>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Campaigns', 'action' => 'createPopup']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Create Popup Campaign') ?></a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="has-arrow">
                                        <i class='bx bx-radio-circle'></i><?= __('Prices') ?>
                                    </a>
                                    <ul>
                                        <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'interstitial', 'prefix' => 'admin']); ?>">
                                                <?= __('Interstitial') ?></a>
                                        </li>
                                        <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'banner', 'prefix' => 'admin']); ?>">
                                                <?= __('Banner') ?></a>
                                        </li>
                                        <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'popup', 'prefix' => 'admin']); ?>">
                                                <?= __('Popup') ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (get_option('earning_mode', 'campaign') === 'simple') : ?>
                        <li>
                            <a href="javascript:;" class="has-arrow">
                                <div class="parent-icon"><i class='bx bx-dollar'></i></div>
                                <div class="menu-title"><?= __('Payout Rates') ?></div>
                            </a>
                            <ul>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'payoutInterstitial', 'prefix' => 'admin']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Interstitial') ?></a>
                                </li>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'payoutBanner', 'prefix' => 'admin']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Banner') ?></a>
                                </li>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Traffics2', 'action' => 'payoutBanner', 'prefix' => 'admin']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Banner Traffics2') ?></a>
                                </li>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'payoutPopup', 'prefix' => 'admin']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Popup') ?></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (in_array($user['role'], is_admin_report())): ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-wallet'></i></div>
                            <div class="menu-title"><?= __('Withdraws') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Withdraws', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                            </li>
                            <?php if (in_array($user['role'], is_admin())): ?>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Withdraws', 'action' => 'export']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Export') ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($user['role'], is_admin())): ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-user'></i></div>
                            <div class="menu-title"><?= __('Users') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'add']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Add') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'referrals']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Referrals') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'export']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Export') ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($user['role'], is_admin_camp())): ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-bar-chart-alt-2'></i></div>
                            <div class="menu-title"><?= __('Reports') ?> <span class="notification"><?= request_count('total') ?></span></div>
                        </a>
                        <ul>
                            <?php if (in_array($user['role'], is_admin())): ?>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'Reports', 'action' => 'campaigns']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Reports') ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if (in_array($user['role'], is_admin_camp())): ?>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'BuyerReports', 'action' => 'index']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Buyer Report') ?>
                                        <span class="notification"><?= request_count('buyer') ?></span></a>
                                </li>
                            <?php endif; ?>
                            <?php if (in_array($user['role'], is_admin())): ?>
                                <li><a href="<?php echo $this->Url->build(['controller' => 'MemberReports', 'action' => 'index']); ?>">
                                        <i class='bx bx-radio-circle'></i><?= __('Member Report') ?>
                                        <span class="notification"><?= request_count('member') ?></span></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($user['role'], is_admin())): ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-package'></i></div>
                            <div class="menu-title"><?= __('Plans') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Plans', 'action' => 'add']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Add') ?></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo $this->Url->build(['controller' => 'Invoices', 'action' => 'index']); ?>">
                            <div class="parent-icon"><i class='bx bx-credit-card'></i></div>
                            <div class="menu-title"><?= __('Invoices') ?></div>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-file'></i></div>
                            <div class="menu-title"><?= __('Blog') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Posts', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Posts List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Posts', 'action' => 'add']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Add Post') ?></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-file-blank'></i></div>
                            <div class="menu-title"><?= __('Pages') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'add']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Add') ?></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-comment-dots'></i></div>
                            <div class="menu-title"><?= __('Testimonials') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Testimonials', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Testimonials', 'action' => 'add']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Add') ?></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-notification'></i></div>
                            <div class="menu-title"><?= __('Announcements') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Announcements', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Announcements', 'action' => 'add']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Add') ?></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'menu']); ?>">
                            <div class="parent-icon"><i class='bx bx-menu'></i></div>
                            <div class="menu-title"><?= __('Menu Manger') ?></div>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-error-circle'></i></div>
                            <div class="menu-title"><?= __('Advanced') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Advanced', 'action' => 'statistics']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Statistics Table') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'system']); ?>" target="_blank">
                                    <i class='bx bx-radio-circle'></i><?= __('System Info') ?></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cog'></i></div>
                            <div class="menu-title"><?= __('Settings') ?></div>
                        </a>
                        <ul>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Settings') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Packages', 'action' => 'index']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Packages') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'ads']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Ads') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'withdraw']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Withdraw') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'email']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Email') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'socialLogin']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Social Login') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Options', 'action' => 'payment']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Payment Methods') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Traffics2', 'action' => 'Settings']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Traffics2 Settings') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Ads', 'action' => 'list']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Ads List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'TopMember', 'action' => 'list']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Top Member List') ?></a>
                            </li>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'TopMember', 'action' => 'addFooter']); ?>">
                                    <i class='bx bx-radio-circle'></i><?= __('Add Footer') ?></a>
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

                    <?php if (in_array($user['role'], is_admin()) && require_database_upgrade()) : ?>
                        <div class="alert alert-danger mb-0 ms-3">
                            <button class="btn btn-sm btn-danger" onclick="location.href='<?= $this->Url->build([
                                                                                                'controller' => 'Upgrade',
                                                                                                'action' => 'index',
                                                                                                'prefix' => 'admin',
                                                                                            ]); ?>'">
                                <i class="bx bx-refresh"></i> <?= __('Complete Upgrade Process') ?>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="top-menu ms-auto">
                        <!-- <ul class="navbar-nav align-items-center gap-1">
                            <?php if (in_array($user['role'], ['admin'])) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= $this->Url->build([
                                                                    'controller' => 'Users',
                                                                    'action' => 'dashboard',
                                                                    'prefix' => 'member',
                                                                ]); ?>">
                                        <i class='bx bx-home'></i> <?= __('Member Area') ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul> -->
                    </div>

                    <div class="user-box dropdown px-3">
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z" />
                            </svg>
                            <div class="user-info">
                                <p class="user-name mb-0"><?= h($user['first_name']); ?></p>
                                <p class="designattion mb-0">Admin</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= $this->Url->build([
                                                                                                'controller' => 'Users',
                                                                                                'action' => 'profile',
                                                                                                'prefix' => 'member',
                                                                                            ]); ?>">
                                    <i class="bx bx-user fs-5 me-2"></i><span><?= __('Profile') ?></span>
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
                                    <i class="bx bx-log-out-circle fs-5 me-2"></i><span><?= __('Log out') ?></span>
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
                <section class="mb-3 border-bottom content-header d-flex justify-content-between align-items-center">
                    <h1 style="font-size: 24px;"><?= h($this->fetch('content_title')); ?></h1>
                    <ol class="breadcrumb mb-0">
                        <li><a href="#"><i class="bx bx-home-alt"></i> <?= __('Dashboard') ?></a></li>
                        <li><i style="font-size: 7px; margin: 0 5px;" class="bx bx-chevron-right"></i></li>
                        <li class="active"><?= h($this->fetch('content_title')); ?></li>
                    </ol>
                </section>

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
            <p class="mb-0"><?= __('Copyright ©') ?> <?= h(get_option('site_name')) ?> <?= date("Y") ?> - <?= __('Version') ?> <?= APP_VERSION ?></p>
        </footer>
    </div>
    <!--end wrapper-->

    <?= $this->element('js_vars'); ?>

    <script data-cfasync="false" src="<?= $this->Assets->url('/js/ads.js?ver=' . APP_VERSION) ?>"></script>

    <!-- Bootstrap JS -->
    <script src="<?= $this->Url->build('/asset/js/bootstrap.bundle.min.js') ?>"></script>
    <!--plugins-->
    <script src="<?= $this->Url->build('/asset/js/jquery.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/simplebar/js/simplebar.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
    <script src="<?= $this->Url->build('/asset/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
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

            // DataTables initialization (if exists)
            if ($.fn.DataTable) {
                $('.table').DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/vi.json'
                    }
                });
            }
        });
    </script>

    <?= $this->fetch('scriptBottom') ?>
</body>

</html>