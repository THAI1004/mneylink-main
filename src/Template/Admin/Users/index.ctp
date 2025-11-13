<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 * @var mixed $plans
 */
?>
<?php
$this->assign('title', __('Manage Users'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Users'));
?>

<?php
$yes_no = [
    1 => __('Yes'),
    0 => __('No'),
];

$statuses = [
    1 => __('Active'),
    2 => __('Pending'),
    3 => __('Inactive'),
]
?>

<!-- Filter Section -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-dark text-white border-bottom">
        <h5 class="mb-0">
            <i class="bx bx-filter-alt text-primary"></i> <?= __('Search & Filter') ?>
        </h5>
    </div>
    <div class="card-body bg-dark text-white">
        <?php
        $base_url = ['controller' => 'Users', 'action' => 'index'];
        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'row g-3',
        ]);
        ?>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->text('Filter.id', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => __('Id'),
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->select('Filter.status', $statuses, [
                'label' => false,
                'empty' => __('Status'),
                'class' => 'form-select',
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->select('Filter.plan_id', $plans, [
                'label' => false,
                'empty' => __('Plan'),
                'class' => 'form-select',
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->select('Filter.disable_earnings', $yes_no, [
                'label' => false,
                'empty' => __('Disable Earnings'),
                'class' => 'form-select',
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->text('Filter.username', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => __('Username'),
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->text('Filter.email', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => __('Email'),
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->text('Filter.country', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => __('Country'),
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->text('Filter.login_ip', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => __('Login IP'),
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <?= $this->Form->text('Filter.register_ip', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => __('Register IP'),
            ]); ?>
        </div>

        <div class="col-md-4 col-sm-8">
            <?= $this->Form->text('Filter.other_fields', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => __('First name, last name, address'),
            ]); ?>
        </div>

        <div class="col-md-2 col-sm-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bx bx-search-alt"></i> <?= __('Filter') ?>
                </button>
                <?= $this->Html->link('<i class="bx bx-reset"></i>', $base_url, [
                    'class' => 'btn btn-outline-secondary',
                    'escape' => false,
                    'title' => __('Reset')
                ]); ?>
            </div>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>

<!-- Users Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark">
            <i class="bx bx-user text-primary"></i> <?= __('All Users') ?>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <?= $this->Form->create(null, [
                'url' => ['controller' => 'Users', 'action' => 'mass'],
            ]); ?>

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th>
                        <th><?= $this->Paginator->sort('id', __('Id')); ?></th>
                        <th><?= $this->Paginator->sort('username', __('Username')); ?></th>
                        <th><?= $this->Paginator->sort('username', __('Role')); ?></th>
                        <th><?= $this->Paginator->sort('status', __('Status')); ?></th>
                        <th><?= __('Plan') ?></th>
                        <th><?= __('Expiration') ?></th>
                        <th><?= $this->Paginator->sort('disable_earnings', __('Disable Earnings')); ?></th>
                        <th><?= $this->Paginator->sort('email', __('Email')); ?></th>
                        <th><?= $this->Paginator->sort('login_ip', __('Login IP')); ?></th>
                        <th><?= $this->Paginator->sort('register_ip', __('Register IP')); ?></th>
                        <th><?= $this->Paginator->sort('modified', __('modified')); ?></th>
                        <th><?= $this->Paginator->sort('created', __('Created')); ?></th>
                        <th style="width: 200px;">
                            <div class="d-flex gap-1">
                                <?= $this->Form->select('action', [
                                    '' => __('Mass Action'),
                                    'activate' => __('Activate'),
                                    'pending' => __('Pending'),
                                    'deactivate' => __('Deactivate'),
                                    'resendActivation' => __('Resend Activation Email'),
                                    'delete' => __('Delete with all data'),
                                ], [
                                    'label' => false,
                                    'class' => 'form-select form-select-sm',
                                    'required' => true,
                                    'templates' => [
                                        'inputContainer' => '{{content}}',
                                    ],
                                ]); ?>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bx bx-check"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?= $this->Form->checkbox('ids[]', [
                                    'hiddenField' => false,
                                    'label' => false,
                                    'value' => $user->id,
                                    'class' => 'form-check-input allcheckbox',
                                ]); ?>
                            </td>
                            <td><strong><?= $user->id ?></strong></td>
                            <td>
                                <?= $this->Html->link(
                                    $user->username,
                                    ['controller' => 'users', 'action' => 'view', $user->id, 'prefix' => 'admin']
                                ); ?>
                            </td>
                            <td>
                                <span class="badge bg-info"><?= $user->role ?></span>
                            </td>
                            <td>
                                <?php
                                $statusColors = [
                                    1 => 'success',
                                    2 => 'warning',
                                    3 => 'danger'
                                ];
                                $color = $statusColors[$user->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $color ?>"><?= $statuses[$user->status]; ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?= h($user->plan->title); ?></span>
                            </td>
                            <td>
                                <small class="text-muted"><?= display_date_timezone($user->expiration); ?></small>
                            </td>
                            <td>
                                <?php if ($user->disable_earnings == 1): ?>
                                    <span class="badge bg-danger"><?= __('Yes') ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= __('No') ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $user->email; ?></td>
                            <td><small class="text-muted"><?= $user->login_ip; ?></small></td>
                            <td><small class="text-muted"><?= $user->register_ip; ?></small></td>
                            <td><small class="text-muted"><?= display_date_timezone($user->modified); ?></small></td>
                            <td><small class="text-muted"><?= display_date_timezone($user->created); ?></small></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i> <?= __("Actions") ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?= $this->Html->link(
                                                '<i class="bx bx-show"></i> ' . __('View'),
                                                ['action' => 'view', $user->id],
                                                ['class' => 'dropdown-item', 'escape' => false]
                                            ); ?>
                                        </li>
                                        <li>
                                            <?= $this->Html->link(
                                                '<i class="bx bx-edit"></i> ' . __('Edit'),
                                                ['action' => 'edit', $user->id],
                                                ['class' => 'dropdown-item', 'escape' => false]
                                            ); ?>
                                        </li>
                                        <li>
                                            <?= $this->Html->link(
                                                '<i class="bx bx-envelope"></i> ' . __('Send a message'),
                                                ['action' => 'message', $user->id],
                                                ['class' => 'dropdown-item', 'escape' => false]
                                            ); ?>
                                        </li>

                                        <?php if ($user->status === 2) : ?>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <?= $this->Html->link(
                                                    '<i class="bx bx-mail-send"></i> ' . __('Resend Activation Email'),
                                                    [
                                                        'action' => 'resendActivation',
                                                        $user->id,
                                                        'token' => $this->request->getParam('_csrfToken'),
                                                    ],
                                                    [
                                                        'confirm' => __('Are you sure?'),
                                                        'class' => 'dropdown-item',
                                                        'escape' => false
                                                    ]
                                                ); ?>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($user->status === 1) : ?>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <?= $this->Html->link(
                                                    '<i class="bx bx-block"></i> ' . __('Deactivate'),
                                                    [
                                                        'action' => 'deactivate',
                                                        $user->id,
                                                        'token' => $this->request->getParam('_csrfToken'),
                                                    ],
                                                    [
                                                        'confirm' => __('Are you sure?'),
                                                        'class' => 'dropdown-item text-warning',
                                                        'escape' => false
                                                    ]
                                                ); ?>
                                            </li>
                                            <li>
                                                <?= $this->Html->link(
                                                    '<i class="bx bx-log-in"></i> ' . __('Login as user'),
                                                    ['action' => 'loginAsUser', $user->id],
                                                    ['class' => 'dropdown-item', 'escape' => false]
                                                ); ?>
                                            </li>
                                            <li>
                                                <?= $this->Html->link(
                                                    '<i class="bx bx-money"></i> ' . __('Withdrawal Request'),
                                                    [
                                                        'controller' => 'Withdraws',
                                                        'action' => 'request',
                                                        $user->id,
                                                        'token' => $this->request->getParam('_csrfToken'),
                                                    ],
                                                    [
                                                        'confirm' => __('Are you sure?'),
                                                        'class' => 'dropdown-item',
                                                        'escape' => false
                                                    ]
                                                ); ?>
                                            </li>
                                        <?php endif; ?>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <?= $this->Html->link(
                                                '<i class="bx bx-export"></i> ' . __('Export'),
                                                [
                                                    'action' => 'dataExport',
                                                    $user->id,
                                                    'token' => $this->request->getParam('_csrfToken'),
                                                ],
                                                [
                                                    'confirm' => __('Are you sure?'),
                                                    'class' => 'dropdown-item',
                                                    'escape' => false
                                                ]
                                            ); ?>
                                        </li>
                                        <li>
                                            <?= $this->Html->link(
                                                '<i class="bx bx-trash"></i> ' . __('Delete'),
                                                [
                                                    'action' => 'delete',
                                                    $user->id,
                                                    'token' => $this->request->getParam('_csrfToken'),
                                                ],
                                                [
                                                    'confirm' => __('Are you sure?'),
                                                    'class' => 'dropdown-item text-danger',
                                                    'escape' => false
                                                ]
                                            ); ?>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

<!-- Pagination -->
<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <?php
        $this->Paginator->setTemplates([
            'ellipsis' => '<li class="page-item disabled"><a class="page-link" href="javascript: void(0)">...</a></li>',
        ]);

        if ($this->Paginator->hasPrev()) {
            echo $this->Paginator->prev('«', ['class' => 'page-link']);
        }

        echo $this->Paginator->numbers([
            'modulus' => 4,
            'first' => 2,
            'last' => 2,
        ]);

        if ($this->Paginator->hasNext()) {
            echo $this->Paginator->next('»', ['class' => 'page-link']);
        }
        ?>
    </ul>
</nav>

<?php $this->start('scriptBottom'); ?>
<script>
    $(document).ready(function() {
        // Select all checkbox
        $('#select-all').change(function() {
            $('.allcheckbox').prop('checked', $(this).prop('checked'));
        });

        $('.allcheckbox').change(function() {
            if ($(this).prop('checked') == false) {
                $('#select-all').prop('checked', false);
            }
            if ($('.allcheckbox:checked').length == $('.allcheckbox').length) {
                $('#select-all').prop('checked', true);
            }
        });
    });
</script>
<?php $this->end(); ?>