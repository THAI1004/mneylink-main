<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page[]|\Cake\Collection\CollectionInterface $pages
 */
$this->assign('title', __('Quản lý Camp traffic'));
$this->assign('description', '');
$this->assign('content_title', __('Quản lý Camp traffic'));
$statuses = [
    0 => __('Tạm dừng'),
    1 => __('Đang chạy'),
    2 => __('Hoàn thành'),
    3 => __('Kết thúc'),
];
$user = $this->request->getSession()->read('Auth.User');
?>

<?php if(in_array($user['role'],is_admin())): ?>
<div class="text-center">
    <div style="display: inline-block; margin-bottom: 20px">
        <?=
        $this->Form->create(null, [
            'url' => ['controller' => 'Traffics', 'action' => 'deleteAll'],
        ]);
        ?>

        <?= $this->Form->button(__('Xóa dữ liệu cũ'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-sm-3">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?=number_format($traffics_statics['date'])?></h3>
                <p>Đã chạy hôm nay</p>
            </div>
            <div class="icon"><i class="fa fa-money"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?=number_format($traffics_statics['count_day'])?></h3>
                <p>Cần chạy hôm nay</p>
            </div>
            <div class="icon"><i class="fa fa-exchange"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=number_format($traffics_statics['views'])?></h3>
                <p>Đã chạy</p>
            </div>
            <div class="icon"><i class="fa fa-share"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?=number_format($traffics_statics['count'])?></h3>
                <p>Tổng lượng mua</p>
            </div>
            <div class="icon"><i class="fa fa-usd"></i></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?=number_format($traffics_statics['status'])?>/<?=number_format($traffics_statics['all'])?></h3>
                <p>Đang chạy/Tổng</p>
            </div>
            <div class="icon"><i class="fa fa-money"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=number_format(($traffics_statics['device']/$traffics_statics['views'])*100)?>%</h3>
                <p>Mobile&Tablet/PC</p>
            </div>
            <div class="icon"><i class="fa fa-exchange"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?=number_format($traffics_statics['views']*1500)?></h3>
                <p>Tiền đã chạy</p>
            </div>
            <div class="icon"><i class="fa fa-share"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?=number_format($traffics_statics['count']*1500)?></h3>
                <p>Tổng chi phí</p>
            </div>
            <div class="icon"><i class="fa fa-usd"></i></div>
        </div>
    </div>
</div>

<div class="box box-solid">
    <div class="box-body">
        <?=$this->Form->create('filter-form',[
            'class' => 'form-inline',
            'type' => 'GET'
        ])?>

        <?=$this->Form->control('Filter.id',[
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => 'ID'
        ])?>

        <?=$this->Form->control('Filter.name',[
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => 'Tên Camp'
        ])?>

        <?=$this->Form->control('Filter.url',[
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => 'Url'
        ])?>

        <?=$this->Form->control('Filter.user',[
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => 'User'
        ])?>

        <?=$this->Form->control('Filter.status',[
            'label' => false,
            'class' => 'form-control',
            'type' => 'select',
            'options' => [
                '' => 'Lựa chọn',
                '0' => 'Tạm dừng',
                '1' => 'Đang chạy',
                '2' => 'Hoàn thành',
                '3' => 'Kết thúc'
            ]
        ])?>

        <?=$this->Form->button('Bộ lọc',['class' => 'btn btn-default'])?>
        <a href="<?=$this->Url->build(['action' => 'index'])?>" class="btn btn-link btn-sm">Đặt lại</a>

        <?=$this->Form->end(); ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body no-padding">
        <?= $this->Form->create('bulkEdit',[
            'action' => 'bulkEdit'
        ]) ?>
        <div class="table-responsive">
            <?php if(in_array($user['role'],is_admin())): ?>
            <div class="form-inline" style="margin: 15px 0; padding: 0 15px">
                <?=
                $this->Form->control('action', [
                    'label' => false,
                    'options' => [
                        '' => __('Mass Action'),
                        'active' => __('Đang chạy'),
                        'pending' => __('Tạm dừng'),
                        'delete' => __('Delete with all data'),
                    ],
                    'class' => 'form-control input-sm',
                    'required' => true,
                    'templates' => [
                        'inputContainer' => '{{content}}',
                    ],
                ]);
                ?>

                <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-default btn-sm']); ?>
            </div>
            <?php endif; ?>
            <table class="table table-hover table-striped">
                <tr>
                    <?php if(in_array($user['role'],is_admin_camp())): ?>
                    <th><input type="checkbox" id="select-all"></th>
                    <?php endif; ?>
                    <th><?= $this->Paginator->sort('id', __('ID')); ?></th>
                    <th><?= $this->Paginator->sort('title', __('Tên Camp')); ?></th>
                    <th><?= $this->Paginator->sort('url', __('URL')); ?></th>
                    
                    <th><?= $this->Paginator->sort('count_day', __('Mỗi ngày')); ?></th>
                    <th><?= $this->Paginator->sort('view_day', __('Chạy hôm nay')); ?></th>
			<th><?= $this->Paginator->sort('views', __('Đã chạy')); ?></th>
                    <th><?= $this->Paginator->sort('count', __('Tổng')); ?></th>
                    <th><?= $this->Paginator->sort('confirm_key', __('Chi phí')); ?></th>
                    <th><?= $this->Paginator->sort('status', __('Trạng thái')); ?></th>
                    <th><?= $this->Paginator->sort('user_id', __('Người tạo')); ?></th>
                    <th><?= $this->Paginator->sort('kind', __('Kind')); ?></th>
                    <th><?= $this->Paginator->sort('created', __('Ngày tạo')); ?></th>
                    <?php if(in_array($user['role'],is_admin_camp())): ?>
                    <th><?php echo __('Hành động') ?></th>
                    <?php endif; ?>
                </tr>

                <!-- Here is where we loop through our $posts array, printing out post info -->
                <?php $count_day = $views = $count = 0; ?>
                <?php foreach ($traffics as $traffic): ?>
                    <?php 
                        $count_day += $traffic->count_day; 
                        $views += $traffic->views; 
                        $count += $traffic->count;
                    ?>
                    <tr>
                        <?php if(in_array($user['role'],is_admin_camp())): ?>
                        <td>
                            <?= $this->Form->checkbox('ids[]', [
                                'hiddenField' => false,
                                'label' => false,
                                'value' => $traffic->id,
                                'class' => 'allcheckbox',
                            ]);
                            ?>
                        </td>
                        <?php endif; ?>
                        <td><?= $traffic->id ?></td>
                        <td>
                            <?= $this->Html->link($traffic->title, [
                                'action' => 'view',
                                $traffic->id
                            ]); ?>
                        </td>
                        <td><?= $traffic->url ?></td>
                        <!-- <td><?= $traffic->confirm_key ?></td> -->
                        <td><?= number_format($traffic->count_day) ?></td>
                        <td><?= number_format($traffic->view_day) ?></td>
                        <td><?= number_format($traffic->views) ?></td>
                        <td><?= number_format($traffic->count) ?></td>
                        <td><?= number_format($traffic->count*1500) ?></td>
                        <td><?= $statuses[$traffic->status] ?></td>
                        <td><?=(!empty($traffic->user)) ? $traffic->user->username : null?></td>
                        <td><?= $traffic->kind ?></td>
                        <td><?= display_date_timezone($traffic->created) ?></td>
                        <?php if(in_array($user['role'],is_admin_camp())): ?>
                        <td>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $traffic->id],
                                ['class' => 'btn btn-primary btn-xs']); ?>

                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $traffic->id],
                                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-xs']);

                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?= number_format($count_day) ?></th>
                    <th></th>
                    <th><?= number_format($views) ?></th>
                    <th><?= number_format($count) ?></th>
                    <th><?= number_format($count*1500) ?></th>
                    <th></th>
                </tfoot>
                <?php unset($traffics); ?>
            </table>
        </div>
        <?= $this->Form->end() ?>

    </div><!-- /.box-body -->
</div>

<ul class="pagination">
    <?php
    $this->Paginator->setTemplates([
        'ellipsis' => '<li><a href="javascript: void(0)">...</a></li>',
    ]);

    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev('«');
    }

    echo $this->Paginator->numbers([
        'modulus' => 4,
        'first' => 2,
        'last' => 2,
    ]);

    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next('»');
    }
    ?>
</ul>

<?php $this->start('scriptBottom'); ?>
    <script>
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
    </script>
<?php $this->end(); ?>