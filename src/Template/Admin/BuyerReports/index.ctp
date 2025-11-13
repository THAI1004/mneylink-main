<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statistic $first_statistic
 * @var mixed $campaign_countries
 * @var mixed $campaign_earnings
 * @var mixed $campaign_referers
 */
$this->assign('title', __('Yêu cầu cập nhật'));
$this->assign('description', '');
$this->assign('content_title', __('Yêu cầu cập nhật'));
?>

<?php $this->start('scriptTop') ?>
    <style>
        .badge{
            color: #212529;
            border-radius: .25rem;
            font-size: 100%;
        }
        .badge-warning {
            background-color: #ffc107;
        }
        .badge-success {
            background-color: #00a65a;
            color: white;
        }
    </style>
<?php $this->end() ?>

<div class="box box-primary">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tbody>
                <tr>
                    <th><a>ID</a></th>
                    <th><a>Tên Camp</a></th>
                    <th><a>Username</a></th>
                    <th><a>Status</a></th>
                    <th><a>Ngày tạo</a></th>
                    <th>Hành động</th>
                </tr>

                <!-- Here is where we loop through our $posts array, printing out post info -->
                <?php if (!empty($reports)) : ?>
                    <?php foreach ($reports as $k => $report) :  ?>
                        <?php
                        switch ($report->status){
                            case 1:{
                                $status = 'Đã phê duyệt';
                                $status_class = 'badge-success';
                            } break;
                            default :{
                                $status = 'Đợi phê duyệt';
                                $status_class = 'badge-warning';
                            }
                        } ?>
                        <tr>
                            <td><?=$report->id?></td>
                            <td><?= $this->Html->link($report->traffic->title, [
                                    'controller' => 'traffics',
                                    'action' => 'edit',
                                    $report->traffic->id
                                ]); ?></td>
                            <td><?=$report->user->username?></td>
                            <td><span class="badge <?=$status_class?>"><?=$status?></span></td>
                            <td><?=\Cake\I18n\Time::parse($report->date)?></td>
                            <td>
                                <a href="<?=$this->Url->build(['controller' => 'BuyerReports','action' => 'edit','id' => $report->id])?>" class="btn btn-primary btn-xs">Xem</a>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $report->id],
                                    ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-xs']);

                                ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
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
