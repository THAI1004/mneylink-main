<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Traffic $Traffic
 */
$this->assign('title', __('Campaign #{0}', $traffic->id));
$this->assign('description', '');
$this->assign('content_title', __('Campaign #{0}', $traffic->id));
?>

<?php
$views_total = ['views' => 0, 'total' => 0];
foreach ($campaign->campaign_items as $campaign_item) {
    $views_total['views'] += $campaign_item->views;
    $views_total['total'] += $campaign_item->purchase * 1000;
}

$statuses = [
    0 => __('Tạm dừng'),
    1 => __('Đang chạy'),
    2 => __('Hoàn thành'),
    3 => __('Kết thúc'),
];

?>

<div class="box box-primary">
    <div class="box-body">

        <legend><?= __('Campaign Details') ?></legend>

        <table class="table table-hover table-striped">


            <tr>
                <td><?= __('Campaign Name') ?></td>
                <td><?= h($traffic->title) ?></td>
            </tr>

                        <tr>
                <td><?= __('URL chiến dịch') ?></td>
                <td><?= h($traffic->url) ?></td>
            </tr>

            <tr>
                <td><?= __('Từ khóa') ?></td>
                <td><?= h($traffic->keywords) ?></td>
            </tr>





            <tr>
                <td><?= __('Mỗi ngày') ?></td>
                <td><?= number_format($traffic->count_day) ?></td>
            </tr>
            <tr>
                <td><?= __('Đã chạy') ?></td>
                <td><?= number_format($traffic->views) ?></td>
            </tr>
            <tr>
                <td><?= __('Tổng') ?></td>
                <td><?= number_format($traffic->count) ?></td>
            </tr>

            <tr>
                <td><?= __('Ngày tạo') ?></td>
                <td><?= display_date_timezone($traffic->created) ?></td>
            </tr>

                        <tr>
                <td><?= __('Status') ?></td>
                <td><?= $statuses[$traffic->status] ?></td>
            </tr>

        </table>

    </div><!-- /.box-body -->
</div>


<div class="box box-primary">
    <div class="box-body">
        <legend><?= __('Thống kê theo ngày') ?></legend>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('Date') ?></th>
                <th><?= __('Click') ?></th>
            </tr>
            </thead>
            <?php foreach ($traffic->datetfs as $traffic_date) : ?>
                <tr>
                    <td><?= $traffic_date->date ?></td>
                    <td><?= number_format($traffic_date->views) ?></td>

                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body">
        <legend><?= __('Thống kê IP') ?></legend>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('STT') ?></th>             
                <th><?= __('IP') ?></th>
                <th><?= __('Time') ?></th>
            </tr>
            </thead>
            <?php foreach ($traffic->jobtfs as $jobtf) : ?>
                <?php if($jobtf->status == 1): ?>
                <tr>
                    <td><?= $jobtf->id ?></td>
                    <td><?= $jobtf->ip ?></td>
                    <td><?= $jobtf->created ?></td>

                </tr>
            <?php endif; endforeach; ?>
        </table>
    </div>
</div>