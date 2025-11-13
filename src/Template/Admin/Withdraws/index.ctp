<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw[]|\Cake\Collection\CollectionInterface $withdraws
 * @var mixed $pending_withdrawn
 * @var mixed $publishers_earnings
 * @var mixed $referral_earnings
 * @var mixed $tolal_withdrawn
 */
$this->assign('title', __('Manage Withdraws'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Withdraws'));
$user = $this->request->getSession()->read('Auth.User');
?>

<?php
$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');
?>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-2 opacity-75"><?= __('Publishers Available Balance') ?></p>
                        <h4 class="mb-0 fw-bold"><?= display_price_currency($publishers_earnings); ?></h4>
                    </div>
                    <div class="ms-3">
                        <i class="bx bx-wallet fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm bg-info bg-gradient text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-2 opacity-75"><?= __('Referral Earnings') ?></p>
                        <h4 class="mb-0 fw-bold"><?= display_price_currency($referral_earnings); ?></h4>
                    </div>
                    <div class="ms-3">
                        <i class="bx bx-transfer fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm bg-warning bg-gradient text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-2 opacity-75"><?= __('Pending Withdrawn') ?></p>
                        <h4 class="mb-0 fw-bold"><?= display_price_currency($pending_withdrawn); ?></h4>
                    </div>
                    <div class="ms-3">
                        <i class="bx bx-time-five fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm bg-success bg-gradient text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-2 opacity-75"><?= __('Total Withdraw') ?></p>
                        <h4 class="mb-0 fw-bold"><?= display_price_currency($tolal_withdrawn); ?></h4>
                    </div>
                    <div class="ms-3">
                        <i class="bx bx-dollar-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions and Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <!-- Bulk Edit Section -->
            <?php if (in_array($user['role'], is_admin())): ?>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold">Hành động hàng loạt</label>
                    <?= $this->Form->unlockField('ids'); ?>
                    <?= $this->Form->create(null, [
                        'action' => 'bulkEdit',
                        'class' => 'd-flex gap-2'
                    ]) ?>
                    <?= $this->Form->select('action', [
                        '' => 'Lựa chọn',
                        'approve' => 'Phê Duyệt',
                        'complete' => 'Hoàn thành',
                    ], [
                        'label' => false,
                        'class' => 'form-select'
                    ]) ?>
                    <?= $this->Form->hidden('ids') ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check"></i> <?= __('Action') ?>
                    </button>
                    <?= $this->Form->end() ?>
                </div>
            <?php endif; ?>

            <!-- Filter Section -->
            <div class="col-lg-<?= in_array($user['role'], is_admin()) ? '8' : '12' ?>">
                <?php
                $base_url = ['controller' => 'Withdraws', 'action' => 'index'];
                echo $this->Form->create(null, [
                    'url' => $base_url,
                    'class' => 'd-flex gap-2 flex-wrap align-items-end'
                ]);
                ?>
                <div class="flex-grow-1">
                    <?= $this->Form->text('Filter.user_id', [
                        'label' => false,
                        'class' => 'form-control',
                        'placeholder' => __('User Id')
                    ]) ?>
                </div>

                <div class="flex-grow-1">
                    <?= $this->Form->select('Filter.status', withdraw_statuses(), [
                        'label' => false,
                        'empty' => __('Status'),
                        'class' => 'form-select'
                    ]) ?>
                </div>

                <div class="flex-grow-1">
                    <?= $this->Form->select('Filter.method', $withdrawal_methods, [
                        'label' => false,
                        'empty' => __('Withdrawal Method'),
                        'class' => 'form-select'
                    ]) ?>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-filter"></i> <?= __('Filter') ?>
                </button>

                <?= $this->Html->link('<i class="bx bx-reset"></i> ' . __('Reset'), $base_url, [
                    'class' => 'btn btn-outline-secondary',
                    'escape' => false
                ]); ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Withdraws Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <?php if (in_array($user['role'], is_admin())): ?>
                            <th style="width: 40px;">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                        <?php endif; ?>
                        <th><?= $this->Paginator->sort('id', __('ID')) ?></th>
                        <th><?= __('User') ?></th>
                        <th><?= $this->Paginator->sort('created', __('Date')) ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= $this->Paginator->sort('publisher_earnings', __('Publisher Earnings')) ?></th>
                        <th><?= $this->Paginator->sort('referral_earnings', __('Referral Earnings')) ?></th>
                        <th><?= __('Total Amount') ?></th>
                        <th><?= __('Withdrawal Method') ?></th>
                        <th><?= __('Withdrawal Account') ?></th>
                        <th style="width: 200px;"><?= __('Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdraws as $withdraw) : ?>
                        <tr>
                            <?php if (in_array($user['role'], is_admin())): ?>
                                <td>
                                    <?= $this->Form->checkbox('ids[]', [
                                        'hiddenField' => false,
                                        'label' => false,
                                        'value' => $withdraw->id,
                                        'class' => 'form-check-input allcheckbox',
                                    ]) ?>
                                </td>
                            <?php endif; ?>
                            <td>
                                <strong><?= $this->Html->link('#' . $withdraw->id, [
                                            'action' => 'view',
                                            $withdraw->id
                                        ]) ?></strong>
                            </td>
                            <td>
                                <?= $this->Html->link($withdraw->user->username, [
                                    'controller' => 'Users',
                                    'action' => 'view',
                                    $withdraw->user->id,
                                    'prefix' => 'admin'
                                ]) ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= display_date_timezone($withdraw->created); ?></small>
                            </td>
                            <td>
                                <?php
                                $statusColors = [
                                    1 => 'warning',
                                    2 => 'info',
                                    3 => 'success',
                                    4 => 'danger',
                                    5 => 'secondary'
                                ];
                                $color = $statusColors[$withdraw->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $color ?>"><?= h(withdraw_statuses($withdraw->status)) ?></span>
                            </td>
                            <td><?= display_price_currency($withdraw->publisher_earnings); ?></td>
                            <td><?= display_price_currency($withdraw->referral_earnings); ?></td>
                            <td><strong><?= display_price_currency($withdraw->amount); ?></strong></td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <?= (isset($withdrawal_methods[$withdraw->method])) ?
                                        $withdrawal_methods[$withdraw->method] : $withdraw->method ?>
                                </span>
                            </td>
                            <td>
                                <small><?= nl2br(h($withdraw->account)); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if ($withdraw->status != 5) : ?>
                                        <?= $this->Html->link(
                                            '<i class="bx bx-show"></i>',
                                            ['action' => 'view', $withdraw->id],
                                            ['class' => 'btn btn-outline-primary', 'escape' => false, 'title' => __('View')]
                                        ); ?>
                                    <?php endif; ?>

                                    <?php if (in_array($user['role'], is_admin())): ?>
                                        <?php if ($withdraw->status == 2) : ?>
                                            <?= $this->Form->postLink(
                                                '<i class="bx bx-check"></i>',
                                                ['action' => 'approve', $withdraw->id],
                                                [
                                                    'confirm' => __('Are you sure?'),
                                                    'class' => 'btn btn-outline-success',
                                                    'escape' => false,
                                                    'title' => __('Approve')
                                                ]
                                            ); ?>
                                        <?php endif; ?>

                                        <?php if ($withdraw->status == 1) : ?>
                                            <?= $this->Form->postLink(
                                                '<i class="bx bx-check-double"></i>',
                                                ['action' => 'complete', $withdraw->id],
                                                [
                                                    'confirm' => __('Are you sure?'),
                                                    'class' => 'btn btn-outline-success',
                                                    'escape' => false,
                                                    'title' => __('Complete')
                                                ]
                                            ); ?>
                                        <?php endif; ?>

                                        <?php if (in_array($withdraw->status, [1, 2])) : ?>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#withdraw-<?= $withdraw->id ?>" title="<?= __('Cancel') ?>">
                                                <i class="bx bx-x"></i>
                                            </button>

                                            <!-- Cancel Modal -->
                                            <div class="modal fade" id="withdraw-<?= $withdraw->id ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><?= __('Cancel Withdraw') ?> #<?= $withdraw->id ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?= $this->Form->create($withdraw, ['action' => 'cancel', $withdraw->id]); ?>
                                                            <?= $this->Form->hidden('id'); ?>
                                                            <div class="mb-3">
                                                                <label class="form-label"><?= __('Reason') ?></label>
                                                                <?= $this->Form->text('user_note', [
                                                                    'class' => 'form-control',
                                                                    'maxlength' => 191,
                                                                    'placeholder' => __('Enter cancellation reason...')
                                                                ]) ?>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="bx bx-x"></i> <?= __('Submit') ?>
                                                                </button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <?= __('Close') ?>
                                                                </button>
                                                            </div>
                                                            <?= $this->Form->end(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?= $this->Form->postLink(
                                                '<i class="bx bx-undo"></i>',
                                                ['action' => 'returned', $withdraw->id],
                                                [
                                                    'confirm' => __('Are you sure?'),
                                                    'class' => 'btn btn-outline-warning',
                                                    'escape' => false,
                                                    'title' => __('Return')
                                                ]
                                            ); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($withdraw); ?>
                </tbody>
            </table>
        </div>

        <!-- Status Legend -->
        <div class="alert alert-light border mt-4">
            <h6 class="alert-heading mb-3"><i class="bx bx-info-circle"></i> <?= __('Status Information') ?></h6>
            <ul class="mb-0 small">
                <li class="mb-1">
                    <span class="badge bg-warning text-dark">Pending</span> - <?= __("The payment is being checked by our team.") ?>
                </li>
                <li class="mb-1">
                    <span class="badge bg-info">Approved</span> - <?= __("The payment has been approved and is waiting to be sent.") ?>
                </li>
                <li class="mb-1">
                    <span class="badge bg-success">Complete</span> - <?= __("The payment has been successfully sent to your account.") ?>
                </li>
                <li class="mb-1">
                    <span class="badge bg-danger">Cancelled</span> - <?= __("The payment has been cancelled.") ?>
                </li>
                <li>
                    <span class="badge bg-secondary">Returned</span> - <?= __("The payment has been returned to the user account.") ?>
                </li>
            </ul>
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
    let ids = [];

    $('#select-all').change(function() {
        $('.allcheckbox').trigger('click');
    });

    $('.allcheckbox').change(function() {
        if ($(this).prop('checked') == false) {
            $('#select-all').prop('checked', false);
        }
        if ($('.allcheckbox:checked').length == $('.allcheckbox').length) {
            $('#select-all').prop('checked', true);
        }
        if ($(this).is(':checked')) {
            ids.push($(this).val());
        } else {
            let index = ids.indexOf($(this).val());
            if (index > -1) ids.splice(index, 1);
        }
        $('input[name=ids]').val(ids.toString());
    });
</script>
<?php $this->end(); ?>