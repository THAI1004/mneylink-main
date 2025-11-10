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
?>

<div class="text-center">
    <div style="display: inline-block; margin-bottom: 20px">
        <?php
        $base_url = ['controller' => 'Traffics', 'action' => 'checking'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline',
            'type' => 'get'
        ]);
        ?>
        <?=
        $this->Form->control('user_id', [
            'label' => false,
            'class' => 'form-control',
            'placeholder' => 'Nhập user ID',
            'type' => 'text',
            'default' => $user_id
        ])
        ?>
        <?= $this->Form->button(__('Tìm kiếm'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body no-padding">

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tr>
             
                    <th>USER_ID</th>
                    <th>IP</th>
                    <th>Referrer</th>
                    <th>Source</th>
                </tr>

                <!-- Here is where we loop through our $posts array, printing out post info -->
                <?php foreach ($jobtfs as $jb): ?>
                    <tr>
                        
                        <td><?=$jb->user_id?></td>
                        <td><?=$jb->ip?></td>
                        <td><?=$jb->referrer?></td>
                        <td><?=$jb->referrer_src?></td>
                    </tr>
                
                <?php endforeach; ?>
              
                <?php unset($jobtfs); ?>
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