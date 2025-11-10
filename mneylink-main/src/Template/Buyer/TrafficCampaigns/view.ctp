<?php
/**
 * @var \App\View\AppView $this
 */

$this->assign('title', __('Bảng Điều Khiển'));
$this->assign('description', '');
$this->assign('content_title', __('Bảng Điều Khiển'));

$data = (!empty($data)) ? $data : [];
extract($data);

$package_list = packages();
$package_current_id = $traffic->buyer_package ?? 1;
$package_data = $package_list[$package_current_id];
?>

<?php $this->start('scriptTop'); ?>

<?php $this->end(); ?>

    <div class="text-center">
        <div style="display: flex; justify-content: center; align-items: center; gap: 10px">
            <?= $this->Form->create(null, ['type' => 'get']); ?>

            <?= $this->Form->control('month', [
                'label' => false,
                'options' => $year_month,
                'value' => ($this->request->getQuery('month')) ? h($this->request->getQuery('month')) : '',
                'class' => 'form-control input-lg',
                'onchange' => 'this.form.submit();',
                'style' => 'width: 300px;',
            ]); ?>

            <?= $this->Form->button(__('Submit'), ['class' => 'hidden']); ?>

            <?= $this->Form->end(); ?>

            <div class="download-excel">
                <a href="<?=\Cake\Routing\Router::url(['controller' => 'TrafficCampaigns', 'action' => 'downloadExcel',$traffic->id])?>" class="btn btn-primary">
                    Download Excel
                </a>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-bar-chart"></i>
            <h3 class="box-title"><?= __('Thống kê') ?></h3>
        </div>
        <div class="box-body no-padding">
            <div id="chart_div" style="position: relative; height: 300px; width: 100%;"></div>
            <div class="small text-right" style="padding-right: 10px;">
                <?= __('Data is reported in {0} timezone', get_option('timezone', 'UTC')) ?>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-body">

            <legend>Chi tiết về Quảng Cáo</legend>

            <?php if (get_option('ember_code')) : ?>
                <div class="form-group">
                    <label>Embed code</label>
                    <pre><?=htmlentities(get_option('ember_code'))?></pre>
                </div>
            <?php endif; ?>

            <table class="table table-hover table-striped">
                <tbody>
                <tr>
                    <td>Trạng thái</td>
                    <td><?=($traffic->buyer_invoice->status == 1) ? "Đã" : "Chưa"?> Thanh Toán</td>
                </tr>
                <tr>
                    <td>Gói</td>
                    <td><?=$package_data->name ?? ''?></td>
                </tr>
                <tr>
                    <td>Xem hôm nay</td>
                    <td><?=$traffic_view_today?></td>
                </tr>
                <tr>
                    <td>Xem mỗi ngày</td>
                    <td><?=$traffic->count_day?></td>
                </tr>
                <tr>
                    <td>Tổng đã xem</td>
                    <td><?=$traffic_view?></td>
                </tr>
                <tr>
                    <td>Total views left</td>
                    <td><?=$traffic->count - $traffic_view?></td>
                </tr>
                <tr>
                    <td>Tổng xem</td>
                    <td><?=$traffic->count?></td>
                </tr>
                <tr>
                    <td>Tổng tiền</td>
                    <td><?=currency_format($package_data->price * $traffic->count)?></td>
                </tr>
                <tr>
                    <td>Tạo</td>
                    <td><?=\Cake\I18n\Time::parse($traffic->created)?></td>
                </tr>
                </tbody>
            </table>


        </div><!-- /.box-body -->
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <legend>Thống kê theo ngày</legend>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Click</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($statists) && count($statists)): ?>
                    <?php foreach ($statists as $k => $item): ?>
                        <tr>
                            <td><?=\Cake\I18n\Time::parse($k)->format('d/m/Y')?></td>
                            <td><?=number_format($item)?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box box-primary">
        <table class="table table-hover table-striped">
            <tbody><tr>
                <th>Keyword</th>
                <th>Url</th>
                <th>Tổng xem</th>
                <th>Xem mỗi ngày</th>
                <th>Viewed today</th>
                <th>Viewed</th>
                <th>View còn lại</th>
            </tr>
            <tr>
                <td><?=$traffic->keywords?></td>
                <td><?=$traffic->url?></td>
                <td><?=$traffic->count?></td>
                <td><?=$traffic->count_day?></td>
                <td><?=$traffic_view_today?></td>
                <td><?=$traffic_view?></td>
                <td><?=$traffic->count - $traffic_view?></td>
            </tr>
            </tbody></table>
    </div>

    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <i class="fa fa-hand-pointer-o"></i>
            <h3 class="box-title">IPs</h3>
        </div>
        <div class="box-body" style="height: 300px; overflow: auto;">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>IP</th>
                    <th>Time</th>
                    <th>Quốc gia</th>
                </tr>
                </thead>
                <?php if ($traffic_ips) : ?>
                    <tbody>
                        <?php foreach ($traffic_ips as $ip) : ?>
                            <tr>
                                <td><?=$ip->ip?></td>
                                <td><?=\Cake\I18n\Time::parse($ip->created)?></td>
                                <td><?=$ip->country?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php endif; ?>
            </table>


        </div>
    </div>

<?php $this->start('scriptBottom'); ?>
    <?php $totalView = [];
    if ($statists) {
        foreach ($statists as $k => $item) {
            $totalView[] = [
                'date' => $k,
                'views' => $item
            ];
        }
    } ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.css">
    <script src="https://cdn.jsdelivr.net/gh/DmitryBaranovskiy/raphael@v2.1.0/raphael-min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function() {
            new Morris.Line({
                element: 'chart_div',
                resize: true,
                data: <?=json_encode($totalView)?>,
                xkey: 'date',
                xLabels: 'day',
                ykeys: ['views'],
                labels: ['Lượt xem'],
                lineColors: ['#3c8dbc'],
                lineWidth: 2,
                hideHover: 'auto',
                smooth: false,
            });
        });
    </script>
<?php $this->end();
