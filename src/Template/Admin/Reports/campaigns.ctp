<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statistic $first_statistic
 * @var mixed $campaign_countries
 * @var mixed $campaign_earnings
 * @var mixed $campaign_referers
 */
$this->assign('title', __('Campaigns Report'));
$this->assign('description', '');
$this->assign('content_title', __('Campaigns Report'));
?>

<style>
    .filter-card-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stats-card-gradient {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .countries-card-gradient {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .referers-card-gradient {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .table-container {
        max-height: 300px;
        overflow-y: auto;
    }

    .table-container::-webkit-scrollbar {
        width: 8px;
    }

    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .btn-filter-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-filter-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
</style>

<!-- Filter Section -->
<div class="card shadow-sm border-0 rounded-lg mb-4">
    <div class="card-header filter-card-gradient text-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="fa fa-filter me-2"></i><?= __('Filter Reports') ?>
        </h5>
    </div>
    <div class="card-body p-4">
        <?php
        $base_url = ['controller' => 'Reports', 'action' => 'campaigns'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'type' => 'get',
            'class' => 'row g-3',
        ]);
        ?>

        <div class="col-md-6 col-lg-3">
            <?= $this->Form->control('Filter.user_id', [
                'label' => __('User ID'),
                'placeholder' => __('Enter User Id'),
                'class' => 'form-control',
                'type' => 'text',
                'value' => $this->request->getQuery('Filter.user_id', ''),
            ]); ?>
        </div>

        <div class="col-md-6 col-lg-3">
            <?= $this->Form->control('Filter.campaign_id', [
                'label' => __('Campaign ID'),
                'placeholder' => __('Enter Campaign Id'),
                'class' => 'form-control',
                'type' => 'text',
                'value' => $this->request->getQuery('Filter.campaign_id', ''),
            ]); ?>
        </div>

        <div class="col-md-6 col-lg-3">
            <label class="form-label"><?= __('Date From') ?></label>
            <div class="d-flex gap-2">
                <?= $this->Form->control('Filter.date_from', [
                    'type' => 'date',
                    'label' => false,
                    'minYear' => $first_statistic->created->year,
                    'maxYear' => date('Y'),
                    'year' => ['class' => 'form-select form-select-sm'],
                    'month' => ['class' => 'form-select form-select-sm'],
                    'day' => ['class' => 'form-select form-select-sm'],
                    'empty' => true,
                    'value' => $this->request->getQuery('Filter.date_from', ''),
                ]); ?>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <label class="form-label"><?= __('Date To') ?></label>
            <div class="d-flex gap-2">
                <?= $this->Form->control('Filter.date_to', [
                    'type' => 'date',
                    'label' => false,
                    'minYear' => $first_statistic->created->year,
                    'maxYear' => date('Y'),
                    'year' => ['class' => 'form-select form-select-sm'],
                    'month' => ['class' => 'form-select form-select-sm'],
                    'day' => ['class' => 'form-select form-select-sm'],
                    'empty' => true,
                    'value' => $this->request->getQuery('Filter.date_to', ''),
                ]); ?>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <?= $this->Form->button('<i class="fa fa-search me-2"></i>' . __('Filter'), [
                    'class' => 'btn btn-filter-custom text-white fw-semibold px-4',
                    'escape' => false
                ]); ?>
                <?= $this->Html->link('<i class="fa fa-refresh me-2"></i>' . __('Reset'), $base_url, [
                    'class' => 'btn btn-outline-secondary fw-semibold px-4',
                    'escape' => false
                ]); ?>
            </div>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>

<!-- Campaign Clicks Details -->
<?php $reasons = get_statistics_reasons(); ?>
<div class="card shadow-sm border-0 rounded-lg mb-4">
    <div class="card-header stats-card-gradient text-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="fa fa-bar-chart me-2"></i><?= __("Campaign Clicks Details") ?>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (isset($campaign_earnings) && count($campaign_earnings) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold py-3 px-4"><?= __('Click Type') ?></th>
                            <th class="fw-bold py-3 px-4"><?= __('Count') ?></th>
                            <th class="fw-bold py-3 px-4"><?= __('Publisher Earnings') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campaign_earnings as $campaign_earning): ?>
                            <tr>
                                <td class="py-3 px-4"><?= $reasons[$campaign_earning->reason] ?></td>
                                <td class="py-3 px-4">
                                    <span class="badge bg-primary rounded-pill"><?= $campaign_earning->count ?></span>
                                </td>
                                <td class="py-3 px-4 fw-semibold text-success">
                                    <?= display_price_currency($campaign_earning->earnings); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="text-center py-5">
                <i class="fa fa-bar-chart text-muted" style="font-size: 48px;"></i>
                <p class="text-muted mt-3 mb-0"><?= __("No available data.") ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Countries and Referers Row -->
<div class="row g-4">
    <!-- Countries Section -->
    <div class="col-lg-6">
        <?php
        $countries = get_countries(true);
        $cam_countries = ['Others' => 'Others'] + $countries;
        ?>
        <div class="card shadow-sm border-0 rounded-lg h-100">
            <div class="card-header countries-card-gradient text-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fa fa-globe me-2"></i><?= __("Countries") ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (isset($campaign_countries) && count($campaign_countries) > 0) : ?>
                    <div class="table-container">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="fw-bold py-3 px-4"><?= __('Country') ?></th>
                                    <th class="fw-bold py-3 px-4"><?= __('Count') ?></th>
                                    <th class="fw-bold py-3 px-4"><?= __('Earnings') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($campaign_countries as $campaign_country): ?>
                                    <tr>
                                        <td class="py-3 px-4">
                                            <i class="fa fa-flag text-primary me-2"></i>
                                            <?= $cam_countries[$campaign_country->country] ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="badge bg-info rounded-pill"><?= $campaign_country->count ?></span>
                                        </td>
                                        <td class="py-3 px-4 fw-semibold text-success">
                                            <?= display_price_currency($campaign_country->earnings); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="text-center py-5">
                        <i class="fa fa-globe text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-3 mb-0"><?= __("No available data.") ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Referers Section -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-lg h-100">
            <div class="card-header referers-card-gradient text-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fa fa-share me-2"></i><?= __("Referers") ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (isset($campaign_referers) && count($campaign_referers) > 0) : ?>
                    <div class="table-container">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="fw-bold py-3 px-4"><?= __('Referer') ?></th>
                                    <th class="fw-bold py-3 px-4"><?= __('Count') ?></th>
                                    <th class="fw-bold py-3 px-4"><?= __('Earnings') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($campaign_referers as $campaign_referer): ?>
                                    <tr>
                                        <td class="py-3 px-4">
                                            <i class="fa fa-link text-primary me-2"></i>
                                            <small><?= $campaign_referer->referer_domain ?></small>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="badge bg-warning rounded-pill"><?= $campaign_referer->count ?></span>
                                        </td>
                                        <td class="py-3 px-4 fw-semibold text-success">
                                            <?= display_price_currency($campaign_referer->earnings); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="text-center py-5">
                        <i class="fa fa-share text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-3 mb-0"><?= __("No available data.") ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>