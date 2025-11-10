<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language(null) ?>">
<head>
    <?= $this->element('front_head'); ?>
    <style>
        #bs-example-navbar-collapse-1{
            display: flex!important;
            align-items: center;
            justify-content: flex-end;
        }
        #bs-example-navbar-collapse-1 .dashboard{
            margin-left: 19px;
            font-size: 13px;
            font-weight: bold;
            margin-top: -2px;
        }
        #bs-example-navbar-collapse-1 .dashboard a{
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body class="<?= ($this->request->getParam('_name') === 'home') ? 'home' : 'inner-page' ?>">
<?= get_option('after_body_tag_code'); ?>

<!-- Navigation -->
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only"><?= __('Toggle navigation') ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <?php
            $logo = get_logo();
            $class = '';
            if ($logo['type'] == 'image') {
                $class = 'logo-image';
            }
            ?>
            <a class="navbar-brand <?= $class ?>" href="<?= build_main_domain_url('/'); ?>"><?= $logo['content'] ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?=
            menu_display('menu_main', [
                'ul_class' => 'nav navbar-nav navbar-right',
                'li_class' => '',
                'a_class' => '',
            ], true);
            ?>
            <?php if ($user = $this->getRequest()->getSession()->read('Auth.User.id')) : ?>
            <div class="dashboard">
                <?php $role = $this->getRequest()->getSession()->read('Auth.User.role'); ?>
                <?php if ($role == 'buyer') : ?>
                    <a  href="<?=$this->Url->build(['_name' => 'buyer_dashboard'])?>">Dashboard</a>
                <?php else : ?>
                    <a  href="<?=$this->Url->build(['_name' => 'member_dashboard'])?>">Dashboard</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>

<?= $this->element('front_footer'); ?>

</body>

</html>
