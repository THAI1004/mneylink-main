<?php

/**
 * @var \App\View\AppView $this
 * @var object $menu
 */
?>
<?php
$this->assign('title', __('Menu Manager'));
$this->assign('description', '');
$this->assign('content_title', __('Menu Manager'));
?>

<?php if (!$menu) : ?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header menu-header-gradient text-white text-center py-4">
                        <div class="mb-2">
                            <i class="fa fa-bars fa-3x opacity-75"></i>
                        </div>
                        <h3 class="mb-0 fw-bold"><?= __('Menu Manager') ?></h3>
                        <p class="mb-0 mt-2 opacity-75"><?= __('Manage your website navigation menus') ?></p>
                    </div>

                    <div class="card-body p-4">
                        <?php
                        $availableMenus = [
                            'menu_main' => ['title' => __('Main Menu'), 'icon' => 'fa-bars', 'color' => 'primary'],
                            'menu_short' => ['title' => __('Short Link Page Menu'), 'icon' => 'fa-link', 'color' => 'success'],
                            'menu_footer' => ['title' => __('Footer Menu'), 'icon' => 'fa-list', 'color' => 'info'],
                        ]
                        ?>



                        <div class="row g-4">
                            <?php foreach ($availableMenus as $key => $menuData) : ?>
                                <div class="col-lg-12">
                                    <div class="card menu-location-card border-0 shadow-sm h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    <div class="d-flex align-items-center  justify-content-center bg-<?= $menuData['color'] ?> bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;">
                                                        <i class="fa <?= $menuData['icon'] ?> fa-2x text-<?= $menuData['color'] ?>"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-2 fw-bold">
                                                        <?= $this->Html->link(
                                                            $menuData['title'],
                                                            [
                                                                'controller' => 'Options',
                                                                'action' => 'menu',
                                                                'menu' => $key,
                                                                'lang' => get_option('language', 'en_US'),
                                                            ],
                                                            ['class' => 'text-decoration-none text-white']
                                                        ); ?>
                                                    </h5>

                                                    <div class="mb-3">
                                                        <span class="badge bg-<?= $menuData['color'] ?> me-2">
                                                            <i class="fa fa-language me-1"></i><?= __('Current Language:') ?>
                                                        </span>
                                                        <?= $this->Html->link(
                                                            get_option('language', 'en_US'),
                                                            [
                                                                'controller' => 'Options',
                                                                'action' => 'menu',
                                                                'menu' => $key,
                                                                'lang' => get_option('language', 'en_US'),
                                                            ],
                                                            ['class' => ' language-badge bg-' . $menuData['color'] . ' text-white text-decoration-none']
                                                        ); ?>
                                                    </div>

                                                    <div>
                                                        <small class=" text-white fw-semibold"><?= __('Available Languages:') ?></small>
                                                        <div class="mt-2">
                                                            <?php foreach (get_site_languages() as $lang) : ?>
                                                                <?= $this->Html->link(
                                                                    '<i class="fa fa-globe me-1"></i>' . $lang,
                                                                    ['controller' => 'Options', 'action' => 'menu', 'menu' => $key, 'lang' => $lang],
                                                                    ['class' => 'badge bg-secondary text-white text-decoration-none me-2 language-badge', 'escape' => false]
                                                                ); ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($menu) : ?>
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Add Menu Item Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-lg h-100">
                    <div class="card-header menu-card-gradient text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa fa-plus-circle me-2"></i><?= __('Add Menu Item') ?>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <?= $this->Form->create(null, [
                            'url' => [
                                'controller' => 'Options',
                                'action' => 'menuItem',
                                'lang' => $this->getRequest()->getQuery('lang'),
                            ],
                        ]); ?>

                        <?= $this->Form->hidden('name', ['value' => $menu->name]) ?>

                        <div class="mb-3">
                            <?= $this->Form->control('title', [
                                'label' => __('Menu Title'),
                                'class' => 'form-control',
                                'placeholder' => __('Enter menu title...')
                            ]) ?>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('link', [
                                'label' => __('Link URL'),
                                'class' => 'form-control',
                                'placeholder' => __('/page-url or https://...')
                            ]) ?>
                            <div class="form-text">
                                <i class="fa fa-info-circle me-1"></i>
                                <?= __('Enter relative or absolute URL') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('visibility', [
                                'label' => __('Visibility'),
                                'options' => [
                                    'all' => __('👁️ All Visitors'),
                                    'guest' => __('🔓 Only Guests'),
                                    'logged' => __('🔐 Only Logged In'),
                                ],
                                'class' => 'form-select',
                            ]); ?>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('class', [
                                'label' => __('CSS Class (Optional)'),
                                'class' => 'form-control',
                                'placeholder' => __('custom-class')
                            ]) ?>
                        </div>

                        <?= $this->Form->button(
                            '<i class="fa fa-plus me-2"></i>' . __('Add to Menu'),
                            ['class' => 'btn btn-success w-100 fw-semibold', 'escape' => false]
                        ); ?>
                        <?= $this->Form->end(); ?>
                    </div>
                </div>
            </div>

            <!-- Menu Items List -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fa fa-list me-2"></i><?= __('Menu Structure') ?>
                            </h5>
                            <span class="badge bg-primary">
                                <?= count(json_decode($menu->value)) ?> <?= __('items') ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
                            <i class="fa fa-hand-grab-o me-2 mt-1"></i>
                            <div class="small">
                                <strong><?= __('Tip:') ?></strong>
                                <?= __('Drag and drop items to reorder. Click "Edit" to modify item details.') ?>
                            </div>
                        </div>

                        <?= $this->Form->create(null, [
                            'id' => 'form-settings',
                            'url' => [
                                'controller' => 'Options',
                                'action' => 'menu',
                                'menu' => $menu->name,
                                'lang' => $this->getRequest()->getQuery('lang'),
                            ],
                            'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='<i class=\"fa fa-spinner fa-spin me-2\"></i>" . __('Saving ...') . "'; return true;",
                        ]); ?>

                        <ul id="sortable" class="list-group mb-4">
                            <?php foreach (json_decode($menu->value) as $item) : ?>
                                <li class="menu-item list-group-item" data-id="<?= $item->id ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <i class="fa fa-arrows drag-handle"></i>
                                            <span class="fw-semibold"><?= $item->title ?></span>
                                            <span class="badge bg-light text-dark ms-2 visibility-badge">
                                                <?php
                                                $visIcons = ['all' => '👁️', 'guest' => '🔓', 'logged' => '🔐'];
                                                echo $visIcons[$item->visibility] ?? '👁️';
                                                ?>
                                            </span>
                                        </div>
                                        <div>
                                            <a data-bs-toggle="collapse" href="#menu-item-<?= $item->id ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-edit me-1"></i><?= __('Edit') ?>
                                            </a>
                                        </div>
                                    </div>

                                    <div id="menu-item-<?= $item->id ?>" class="collapse mt-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <?= $this->Form->hidden($item->id . '[id]', ['value' => $item->id]) ?>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <?= $this->Form->control($item->id . '[title]', [
                                                            'label' => __('Title'),
                                                            'class' => 'form-control form-control-sm',
                                                            'value' => $item->title,
                                                        ]) ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $this->Form->control($item->id . '[link]', [
                                                            'label' => __('Link'),
                                                            'class' => 'form-control form-control-sm',
                                                            'value' => $item->link,
                                                        ]) ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $this->Form->control($item->id . '[visibility]', [
                                                            'label' => __('Visibility'),
                                                            'options' => [
                                                                'all' => __('All Visitors'),
                                                                'guest' => __('Only Guests'),
                                                                'logged' => __('Only Logged In'),
                                                            ],
                                                            'value' => $item->visibility,
                                                            'class' => 'form-select form-select-sm',
                                                        ]); ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $this->Form->control($item->id . '[class]', [
                                                            'label' => __('CSS Class'),
                                                            'class' => 'form-control form-control-sm',
                                                            'value' => $item->class,
                                                        ]) ?>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end mt-3">
                                                    <a href="#" class="item-delete btn btn-sm btn-danger">
                                                        <i class="fa fa-trash me-1"></i><?= __('Delete') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <?= $this->Html->link(
                                '<i class="fa fa-arrow-left me-2"></i>' . __('Back to Menu Locations'),
                                ['action' => 'menu'],
                                ['class' => 'btn btn-outline-secondary btn-lg px-4', 'escape' => false]
                            ); ?>
                            <?= $this->Form->button(
                                '<i class="fa fa-save me-2"></i>' . __('Save Menu'),
                                [
                                    'name' => 'save_settings',
                                    'class' => 'btn btn-save-gradient text-white btn-lg fw-bold px-5',
                                    'escape' => false
                                ]
                            ); ?>
                        </div>

                        <?= $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->start('scriptBottom'); ?>
<script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        $('#sortable').sortable({
            items: '> li',
            cursor: 'move',
            opacity: 0.8,
            handle: '.drag-handle',
            tolerance: 'pointer',
            placeholder: 'list-group-item bg-light border-primary border-2',
        }).disableSelection();

        $('.item-delete').on('click', function(e) {
            e.preventDefault();
            if (confirm('<?= __('Are you sure you want to delete this menu item?') ?>')) {
                $(this).closest('li[data-id]').fadeOut(300, function() {
                    $(this).remove();
                });
            }
            return false;
        });
    });
</script>
<?php $this->end(); ?>