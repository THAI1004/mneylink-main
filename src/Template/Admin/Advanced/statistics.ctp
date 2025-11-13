<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statistic[]|\Cake\Collection\CollectionInterface $statistics
 */
$this->assign('title', __('Statistics Table'));
$this->assign('description', '');
$this->assign('content_title', __('Statistics Table'));
?>


<div class="container-fluid">
    <!-- Header Card -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header stats-header-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fa fa-table me-2"></i><?= __('Statistics Data') ?>
                </h5>
                <span class="badge bg-white text-primary">
                    <i class="fa fa-database me-1"></i><?= $this->Paginator->counter('{{count}} records') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="fa fa-filter me-2"></i><?= __('Filters') ?>
            </h6>
        </div>
        <div class="card-body p-4">
            <?php
            $base_url = ['controller' => 'Advanced', 'action' => 'statistics'];

            echo $this->Form->create(null, [
                'url' => $base_url,
                'class' => 'row g-3',
            ]);
            ?>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.user_id', [
                    'label' => __('User ID'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                    'placeholder' => __('Enter User ID'),
                ]); ?>
            </div>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.reason', [
                    'label' => __('Reason'),
                    'options' => get_statistics_reasons(),
                    'empty' => __('Select Reason'),
                    'class' => 'form-select',
                ]); ?>
            </div>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.link_id', [
                    'label' => __('Link ID'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                    'placeholder' => __('Enter Link ID'),
                ]); ?>
            </div>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.referral_id', [
                    'label' => __('Referral ID'),
                    'class' => 'form-control',
                    'type' => 'text',
                    'placeholder' => __('Enter Referral ID'),
                ]); ?>
            </div>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.ad_type', [
                    'label' => __('Ad Type'),
                    'options' => get_allowed_ads(),
                    'empty' => __('Select Ad Type'),
                    'class' => 'form-select',
                ]); ?>
            </div>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.campaign_id', [
                    'label' => __('Campaign ID'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                    'placeholder' => __('Enter Campaign ID'),
                ]); ?>
            </div>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.ip', [
                    'label' => __('IP Address'),
                    'class' => 'form-control',
                    'type' => 'text',
                    'placeholder' => __('Enter IP Address'),
                ]); ?>
            </div>

            <div class="col-md-6 col-lg-3">
                <?= $this->Form->control('Filter.country', [
                    'label' => __('Country'),
                    'class' => 'form-control',
                    'type' => 'text',
                    'placeholder' => __('Enter Country Code'),
                ]); ?>
            </div>

            <div class="col-12">
                <div class="d-flex gap-2 justify-content-end">
                    <?= $this->Form->button(
                        '<i class="fa fa-search me-2"></i>' . __('Apply Filters'),
                        ['class' => 'btn btn-primary text-white fw-semibold p-2', 'escape' => false]
                    ); ?>
                    <?= $this->Html->link(
                        '<i class="fa fa-refresh me-2"></i>' . __('Reset'),
                        $base_url,
                        ['class' => 'btn btn-outline-secondary fw-semibold px-4', 'escape' => false]
                    ); ?>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
    </div>

    <!-- Delete Old Data -->
    <div class="text-center mb-4">
        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Advanced', 'action' => 'deleteAll'],
        ]); ?>
        <?= $this->Form->button(
            '<i class="fa fa-trash me-2"></i>' . __('Xóa dữ liệu cũ'),
            [
                'class' => 'btn btn-danger btn-lg fw-semibold px-5',
                'escape' => false,
                'onclick' => 'return confirm("' . __('Are you sure you want to delete old data? This action cannot be undone.') . '");'
            ]
        ); ?>
        <?= $this->Form->end(); ?>
    </div>

    <!-- Statistics Table -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="fa fa-list me-2"></i><?= __('Statistics Records') ?>
                </h6>
                <div class="text-muted small">
                    <i class="fa fa-info-circle me-1"></i>
                    <?= __('Scroll horizontally to view all columns') ?>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (count($statistics) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover stats-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center"><?= __('ID') ?></th>
                                <th><?= __('Created') ?></th>
                                <th><?= __('Reason') ?></th>
                                <th><?= __('User') ?></th>
                                <th class="text-center"><?= __('Link ID') ?></th>
                                <th><?= __('Referral ID') ?></th>
                                <th><?= __('Ad Type') ?></th>
                                <th class="text-center"><?= __('Campaign ID') ?></th>
                                <th class="text-center"><?= __('Campaign User') ?></th>
                                <th><?= __('IP Address') ?></th>
                                <th><?= __('Country') ?></th>
                                <th class="text-end"><?= __('Owner Earn') ?></th>
                                <th class="text-end"><?= __('Publisher Earn') ?></th>
                                <th class="text-end"><?= __('Referral Earn') ?></th>
                                <th><?= __('Referer Domain') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistics as $statistic) : ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark"><?= $statistic->id ?></span>
                                    </td>
                                    <td>
                                        <small><?= display_date_timezone($statistic->created) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-reason bg-info text-white">
                                            <?= isset(get_statistics_reasons()[$statistic->reason]) ? get_statistics_reasons()[$statistic->reason] : $statistic->reason ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-primary"><?= $statistic->user->username ?></span>
                                    </td>
                                    <td class="text-center"><?= $statistic->link_id ?></td>
                                    <td>
                                        <small class="text-muted"><?= $statistic->referral_id ?: '-' ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= isset(get_allowed_ads()[$statistic->ad_type]) ? get_allowed_ads()[$statistic->ad_type] : $statistic->ad_type ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= $statistic->campaign_id ?: '-' ?></td>
                                    <td class="text-center"><?= $statistic->campaign_user_id ?: '-' ?></td>
                                    <td>
                                        <code class="small"><?= $statistic->ip ?></code>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?= isset(get_countries()[$statistic->country]) ? get_countries()[$statistic->country] : $statistic->country ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-success fw-semibold"><?= number_format($statistic->owner_earn, 4) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-success fw-semibold"><?= number_format($statistic->publisher_earn, 4) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-success fw-semibold"><?= number_format($statistic->referral_earn, 4) ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= $statistic->referer_domain ?: '-' ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa fa-table text-muted" style="font-size: 64px;"></i>
                    <h5 class="text-muted mt-3"><?= __('No Statistics Found') ?></h5>
                    <p class="text-muted"><?= __('Try adjusting your filters or check back later.') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (count($statistics) > 0): ?>
        <div class="d-flex justify-content-center">
            <ul class="pagination">
                <?php
                $this->Paginator->setTemplates([
                    'ellipsis' => '<li class="page-item disabled"><a class="page-link" href="javascript:void(0)">...</a></li>',
                ]);

                if ($this->Paginator->hasPrev()) {
                    echo $this->Paginator->prev('«', ['class' => 'page-link']);
                }

                echo $this->Paginator->numbers([
                    'modulus' => 4,
                    'first' => 2,
                    'last' => 2,
                    'class' => 'page-link'
                ]);

                if ($this->Paginator->hasNext()) {
                    echo $this->Paginator->next('»', ['class' => 'page-link']);
                }
                ?>
            </ul>
        </div>
    <?php endif; ?>
</div>