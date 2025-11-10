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

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <?php
    if ((bool)get_option('combine_minify_css_js', false)) {
        echo $this->Assets->css('/build/css/dashboard.min.css?ver=' . APP_VERSION);
    } else {
        echo $this->Assets->css('/vendor/bootstrap/css/bootstrap.min.css?ver=' . APP_VERSION);
        echo $this->Assets->css('/vendor/font-awesome/css/font-awesome.min.css?ver=' . APP_VERSION);
        echo $this->Assets->css('/vendor/dashboard/css/AdminLTE.min.css?ver=' . APP_VERSION);
        echo $this->Assets->css('/vendor/dashboard/css/skins/_all-skins.min.css?ver=' . APP_VERSION);
        echo $this->Assets->css('/css/app.css?ver=' . APP_VERSION);
    }

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <?= get_option('member_head_code'); ?>

    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">body {font-family: 'Be Vietnam Pro', sans-serif!important;}</style>
</head>


<body class="member-dashboard hold-transition <?= get_option('member_adminlte_theme_skin', 'skin-blue') ?> sidebar-mini">
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?= $this->Url->build('/'); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">
                <?= preg_replace('/(\B.|\s+)/', '', get_option('site_name')) ?>
            </span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><?= get_option('site_name') ?></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?= __('Toggle navigation') ?></span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <?php if (in_array($logged_user->role, ['admin'])) : ?>
                        <li class="dropdown messages-menu">
                            <!-- Menu toggle button -->
                            <a href="<?= $this->Url->build([
                                'controller' => 'Users',
                                'action' => 'dashboard',
                                'prefix' => 'admin',
                            ]); ?>">
                                <i class="fa fa-dashboard"></i> <span
                                        class="hidden-xs"><?= __('Administration Area') ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (count(get_site_languages(true)) > 1) : ?>
                        <li class="dropdown language-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-language"></i> <?= __('Language') ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach (get_site_languages(true) as $lang) : ?>
                                    <li>
                                        <?= $this->Html->link(
                                            locale_get_display_name($lang, $lang),
                                            $this->request->getPath() . '?lang=' . $lang
                                        ); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img
                                    src="<?= "https://www.gravatar.com/avatar/" . md5(strtolower(trim($logged_user->email))) .
                                    "?s=160" ?>" class="user-image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?= h($logged_user->first_name); ?></span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?= "https://www.gravatar.com/avatar/" .
                                md5(strtolower(trim($logged_user->email))) . "?s=160" ?>" class="img-circle">
                                <p>
                                    <small><?= __('Member since') ?> <?= $logged_user->created ?></small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?= $this->Url->build([
                                        'controller' => 'Users',
                                        'action' => 'profile',
                                        'prefix' => 'member',
                                    ]); ?>" class="btn btn-default btn-flat"><?= __('Profile') ?></a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?= $this->Url->build([
                                        'controller' => 'Users',
                                        'action' => 'logout',
                                        'prefix' => 'auth',
                                    ]); ?>" class="btn btn-default btn-flat"><?= __('Log out') ?></a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">

                <li>
                    <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>">
                        <i class="fa fa-dashboard"></i> <span>Hướng dẫn cơ bản</span>
                    </a>
                </li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-database"></i> <span>Quảng Cáo</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="active"><a href="<?php echo $this->Url->build(['controller' => 'TrafficCampaigns', 'action' => 'index']); ?>">Danh Sách</a></li>
                        <li><a href="<?php echo $this->Url->build(['controller' => 'TrafficCampaigns', 'action' => 'create']); ?>">Create</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?=$this->Url->build(['_name' => 'buyer_invoices'])?>">
                        <i class="fa fa-credit-card"></i> <span>Hóa Đơn</span>
                    </a>
                </li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-gears"></i> <span>Cài Đặt</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="<?=$this->Url->build(['_name' => 'buyer_profile'])?>">Hồ sơ</a></li>
                        <li><a href="<?=$this->Url->build(['_name' => 'buyer_change-password'])?>">Đổi Mật Khẩu</a></li>
                        <li><a href="<?=$this->Url->build(['_name' => 'buyer_change-email'])?>">Đổi Email</a></li>
                    </ul>
                </li>

                <li><a href="<?=$this->Url->build(['_name' => 'buyer_form_support'])?>"><i class="fa fa-life-ring"></i> <span>Hỗ Trợ</span></a></li>
                <?php if($logged_user->role == 'admin') :?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-gears"></i> <span>Cài Đặt Chung</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="<?=$this->Url->build(['_name' => 'buyer_option_packages'])?>">Packages</a></li>
                        <li><a href="<?=$this->Url->build(['_name' => 'buyer_option_notification'])?>">Trang thông báo</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <!-- /.sidebar-menu -->

        </section>
        <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?= h($this->fetch('content_title')); ?></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> <?= __('Dashboard') ?></a></li>
                <li class="active"><?= h($this->fetch('content_title')); ?></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if (!$logged_user_plan->disable_ads && !empty(get_option('ad_member'))) : ?>
                <div class="banner banner-member">
                    <div class="banner-inner">
                        <?= get_option('ad_member'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </section>
        <!-- /.content -->
    </div>

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">

        </div>
        <!-- Default to the left -->
        <?= __('Copyright &copy;') ?> <?= h(get_option('site_name')) ?> <?= date("Y") ?>
    </footer>

    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>

<?= $this->element('js_vars'); ?>

<script data-cfasync="false" src="<?= $this->Assets->url('/js/ads.js?ver=' . APP_VERSION) ?>"></script>

<?php
if ((bool)get_option('combine_minify_css_js', false)) {
    echo $this->Assets->script('/build/js/dashboard.min.js?ver=' . APP_VERSION);
} else {
    echo $this->Assets->script('/vendor/jquery.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/bootstrap/js/bootstrap.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/clipboard.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/conditionize.jquery.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/js/app.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/dashboard/js/app.min.js?ver=' . APP_VERSION);
}
?>

<?= $this->fetch('scriptBottom') ?>
</body>
</html>