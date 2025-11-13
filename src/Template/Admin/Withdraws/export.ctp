<?php

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Withdraws Export'));
$this->assign('description', '');
$this->assign('content_title', __('Withdraws Export'));
?>

<?php
$statuses = [
    1 => __('Approved'),
    2 => __('Pending'),
    3 => __('Complete'),
    4 => __('Cancelled'),
    5 => __('Returned')
];

$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?= $this->Form->create(null); ?>

        <div class="row g-4">
            <!-- Select Fields Section -->
            <div class="col-12">
                <div class="card border">
                    <div class="card-header bg-dark">
                        <h5 class="mb-0">
                            <i class="bx bx-check-square text-primary"></i>
                            <?= __('Select fields to export') ?>
                        </h5>
                    </div>
                    <div class="card-body bg-dark text-white">
                        <div class="row g-3">
                            <?php
                            $fields = [
                                'id' => ['icon' => 'bx-hash', 'label' => __('Id')],
                                'status' => ['icon' => 'bx-info-circle', 'label' => __('Status')],
                                'user_id' => ['icon' => 'bx-user', 'label' => __('User Id')],
                                'publisher_earnings' => ['icon' => 'bx-wallet', 'label' => __('Publisher Earnings')],
                                'referral_earnings' => ['icon' => 'bx-transfer', 'label' => __('Referral Earnings')],
                                'amount' => ['icon' => 'bx-dollar-circle', 'label' => __('Amount')],
                                'method' => ['icon' => 'bx-credit-card', 'label' => __('Withdrawal Method')],
                                'account' => ['icon' => 'bx-id-card', 'label' => __('Withdrawal Account')],
                                'created' => ['icon' => 'bx-calendar', 'label' => __('Created')]
                            ];
                            foreach ($fields as $key => $field) :
                            ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="card border shadow-sm h-100">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <?= $this->Form->checkbox("fields[]", [
                                                    'value' => $key,
                                                    'class' => 'form-check-input field-checkbox',
                                                    'id' => 'field-' . $key,
                                                    'checked' => true,
                                                    'hiddenField' => false
                                                ]) ?>
                                                <label class="form-check-label w-100" for="field-<?= $key ?>" style="cursor: pointer; user-select: none;">
                                                    <i class="bx <?= $field['icon'] ?> text-primary fs-5"></i>
                                                    <strong><?= $field['label'] ?></strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" id="selectAll">
                                <i class="bx bx-check-double"></i> <?= __('Select All') ?>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="deselectAll">
                                <i class="bx bx-x"></i> <?= __('Deselect All') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conditions Section -->
            <div class="col-12">
                <div class="card border">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="bx bx-filter text-info"></i>
                            <?= __("Conditions") ?>
                        </h5>
                    </div>
                    <div class="card-body bg-dark text-white
                    ">
                        <div class="row g-3">
                            <!-- User Id -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-user"></i> <?= __('User Id') ?>
                                </label>
                                <?= $this->Form->number('conditions.user_id', [
                                    'label' => false,
                                    'class' => 'form-control',
                                    'placeholder' => __('Enter User Id...'),
                                    'min' => 0
                                ]); ?>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-info-circle"></i> <?= __('Status') ?>
                                </label>
                                <?= $this->Form->select('conditions.status', $statuses, [
                                    'label' => false,
                                    'empty' => __('All Statuses'),
                                    'class' => 'form-select'
                                ]); ?>
                            </div>

                            <!-- Withdrawal Method -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-wallet"></i> <?= __('Withdrawal Method') ?>
                                </label>
                                <?= $this->Form->select('conditions.method', $withdrawal_methods, [
                                    'label' => false,
                                    'empty' => __('All Methods'),
                                    'class' => 'form-select'
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Info -->
            <div class="col-12">
                <div class="alert alert-info border-0 d-flex align-items-center">
                    <i class="bx bx-download fs-4 me-3"></i>
                    <div>
                        <strong><?= __('Export Information') ?></strong>
                        <p class="mb-0 small"><?= __('The export will include all withdraws matching your selected conditions. The file will be downloaded in CSV format.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bx bx-export me-2"></i><?= __('Export Data') ?>
                    </button>
                    <button type="reset" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="bx bx-reset me-2"></i><?= __('Reset') ?>
                    </button>
                </div>
            </div>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>


<?php $this->start('scriptBottom'); ?>
<script>
    $(document).ready(function() {
        // Select All button
        $('#selectAll').on('click', function() {
            $('.field-checkbox').prop('checked', true);
        });

        // Deselect All button
        $('#deselectAll').on('click', function() {
            $('.field-checkbox').prop('checked', false);
        });

        // Form validation
        $('form').on('submit', function(e) {
            const selectedFields = $('.field-checkbox:checked').length;
            if (selectedFields === 0) {
                e.preventDefault();
                alert('<?= __('Please select at least one field to export') ?>');
                return false;
            }
        });

        // Reset button handler
        $('button[type="reset"]').on('click', function() {
            setTimeout(function() {
                $('.field-checkbox').prop('checked', true);
            }, 100);
        });
    });
</script>
<?php $this->end(); ?>