<?php

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Users Export'));
$this->assign('description', '');
$this->assign('content_title', __('Users Export'));
?>

<?php
$statuses = [
    1 => __('Active'),
    2 => __('Pending'),
    3 => __('Inactive')
]
?>

<style>
    .export-header-gradient {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .field-checkbox-item {
        transition: all 0.2s ease;
    }

    .field-checkbox-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.15);
    }

    .btn-export-gradient {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
    }

    .btn-export-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mb-4">
                <div class="card-header text-white text-center py-4">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle" style="width: 70px; height: 70px;">
                            <i class="fa fa-download fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="mb-2 fw-bold"><?= __('Export Users Data') ?></h3>
                    <p class="mb-0 opacity-75"><?= __('Select the fields you want to include in your export') ?></p>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="fa fa-info-circle me-2 fs-5"></i>
                        <div><?= __('Choose one or more fields below. The exported file will contain only the selected data.') ?></div>
                    </div>

                    <?= $this->Form->create(null); ?>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-flex align-items-center mb-3">
                            <i class="fa fa-list-ul text-success me-2"></i>
                            <?= __('Select fields to export') ?>
                            <span class="badge bg-success ms-2" id="selectedCount">0 selected</span>
                        </label>

                        <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                            <input type="checkbox" class="form-check-input me-2 fs-5" id="selectAll">
                            <label class="form-check-label fw-bold mb-0" for="selectAll">
                                <?= __('Select / Deselect All') ?>
                            </label>
                        </div>

                        <!-- Hidden original multi-select for form submission -->
                        <?= $this->Form->control('fields', [
                            'type' => 'select',
                            'multiple' => true,
                            'options' => [
                                'id' => __('Id'),
                                'status' => __('Status'),
                                'username' => __('Username'),
                                'email' => __('Email'),
                                'first_name' => __('First Name'),
                                'last_name' => __('Last Name'),
                                'login_ip' => __('Login IP'),
                                'register_ip' => __('Register IP'),
                                'created' => __('Created')
                            ],
                            'label' => false,
                            'class' => 'form-control d-none'
                        ]); ?>

                        <!-- Custom checkbox grid using Bootstrap -->
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="id" id="field_id">
                                            <label class="form-check-label fw-semibold" for="field_id">
                                                <?= __('Id') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="status" id="field_status">
                                            <label class="form-check-label fw-semibold" for="field_status">
                                                <?= __('Status') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="username" id="field_username">
                                            <label class="form-check-label fw-semibold" for="field_username">
                                                <?= __('Username') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="email" id="field_email">
                                            <label class="form-check-label fw-semibold" for="field_email">
                                                <?= __('Email') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="first_name" id="field_first_name">
                                            <label class="form-check-label fw-semibold" for="field_first_name">
                                                <?= __('First Name') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="last_name" id="field_last_name">
                                            <label class="form-check-label fw-semibold" for="field_last_name">
                                                <?= __('Last Name') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="login_ip" id="field_login_ip">
                                            <label class="form-check-label fw-semibold" for="field_login_ip">
                                                <?= __('Login IP') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="register_ip" id="field_register_ip">
                                            <label class="form-check-label fw-semibold" for="field_register_ip">
                                                <?= __('Register IP') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card field-checkbox-item h-100 border-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input field-check" type="checkbox" value="created" id="field_created">
                                            <label class="form-check-label fw-semibold" for="field_created">
                                                <?= __('Created') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center pt-3 border-top">
                        <?= $this->Form->button('<i class="fa fa-download me-2"></i>' . __('Export Data'), [
                            'class' => 'btn btn-lg btn-export-gradient text-white fw-bold px-5',
                            'escape' => false
                        ]); ?>
                    </div>

                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const fieldCheckboxes = document.querySelectorAll('.field-check');
        const hiddenSelect = document.querySelector('select[name="fields[]"]');
        const countBadge = document.getElementById('selectedCount');

        // Update hidden select and count
        function updateSelection() {
            const selectedValues = Array.from(fieldCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            // Update hidden select
            Array.from(hiddenSelect.options).forEach(option => {
                option.selected = selectedValues.includes(option.value);
            });

            // Update count badge
            countBadge.textContent = selectedValues.length + ' selected';
        }

        // Select/Deselect all
        selectAllCheckbox.addEventListener('change', function() {
            fieldCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateSelection();
        });

        // Individual checkbox change
        fieldCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Update "select all" state
                const allChecked = Array.from(fieldCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                updateSelection();
            });
        });

        // Initialize
        updateSelection();
    });
</script>