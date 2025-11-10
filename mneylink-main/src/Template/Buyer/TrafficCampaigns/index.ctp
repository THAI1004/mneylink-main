<?php
/**
 * @var \App\View\AppView $this
 */

$this->assign('title', __('Bảng Điều Khiển'));
$this->assign('description', '');
$this->assign('content_title', __('Bảng Điều Khiển'));

$data = (!empty($data)) ? $data : [];
extract($data);

$packages = (!empty($packages)) ? json_decode($packages) : [];
$package_list = $package_list_name = [];
foreach ($packages as $k => $package){
    $package_list_name[$k+1] = $package->name;
    $package_list[$k+1] = [
        'id' => $k+1,
        'name' => $package->name,
        'time' => $package->time,
        'price' => $package->price
    ];
}

$infoBanking = function ($content){
    $infoTag = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_banking">Thông tin chuyển khoản</button>';
    return str_replace('[info_banking]',$infoTag,$content);
};

?>

<?php $this->start('scriptTop'); ?>
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
        .badge-secondary{
            color: white;
        }
        .badge-primary{
            background: #007bff;
            color: white;
        }
    </style>
<?php $this->end(); ?>

    <?php if (!empty(get_option('nf_1'))) : ?>
    <div class="callout bg-green">
        <?=$infoBanking(get_option('nf_1'))?>
    </div>
    <?php endif; ?>

    <?php if (!empty(get_option('nf_2'))) : ?>
        <div class="callout bg-red">
            <?=$infoBanking(get_option('nf_2'))?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-3">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?=(!empty($traffic_total) && $traffic_total['active']) ? $traffic_total['active'] : 0?></h3>
                    <p>Tổng Camp đang chạy</p>
                </div>
                <div class="icon"><i class="fa fa-money"></i></div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box bg-fuchsia">
                <div class="inner">
                    <h3><?=(!empty($traffic_total) && $traffic_total['pause']) ? $traffic_total['pause'] : 0?></h3>
                    <p>Tổng Camp đang dừng</p>
                </div>
                <div class="icon"><i class="fa fa-money"></i></div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?=(!empty($traffic_total) && $traffic_total['viewedToday']) ? $traffic_total['viewedToday'] : 0?></h3>
                    <p>Tổng lượt xem đã chạy hôm nay</p>
                </div>
                <div class="icon"><i class="fa fa-exchange"></i></div>
            </div>
        </div>
        <!--<div class="col-sm-3">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>0</h3>
                    <p>Tổng lượt xem cần chạy hôm nay</p>
                </div>
                <div class="icon"><i class="fa fa-share"></i></div>
            </div>
        </div>-->
        <!--<div class="col-sm-3">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>0</h3>
                    <p>Tổng lượt xem đang chờ chạy hôm nay</p>
                </div>
                <div class="icon"><i class="fa fa-usd"></i></div>
            </div>
        </div>-->

        <div class="col-sm-3">
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3><?=(!empty($traffic_total) && $traffic_total['views']) ? number_format($traffic_total['views']) : 0?></h3>
                    <p>Tổng xem</p>
                </div>
                <div class="icon"><i class="fa fa-eye"></i></div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>
                        <?=(!empty($traffic_total) && $traffic_total['viewed']) ? $traffic_total['viewed'] : 0?>
                        <span style="font-size: 14px"><?=(!empty($traffic_total) && $traffic_total['viewed']) ? currency_format($traffic_total['viewedMoney']) : "0đ"?></span>
                    </h3>
                    <p>Tổng đã xem</p>
                </div>
                <div class="icon"><i class="fa fa-eye"></i></div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?=(!empty($traffic_total) && $traffic_total['allMoney']) ? currency_format($traffic_total['allMoney']) : "0đ"?></h3>
                    <p>Tổng All Money</p>
                </div>
                <div class="icon"><i class="fa fa-usd"></i></div>
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

    <div class="box box-solid">
        <div class="box-body">
            <div class="d-flex justify-content-between">
                <?=$this->Form->create('traffic-campaigns',[
                    'class' => 'form-inline pull-left'
                ])?>

                <?=$this->Form->control('Filter.id',[
                    'label' => false,
                    'class' => 'form-control',
                    'placeholder' => 'ID'
                ])?>

                <?=$this->Form->control('Filter.status',[
                    'label' => false,
                    'type' => 'select',
                    'class' => 'form-control',
                    'options' => [
                        '' => 'Trạng thái',
                        0 => 'Tạm dừng',
                        1 => 'Hoạt động',
                        2 => 'Hoàn thành',
                    ]
                ])?>

                <?=$this->Form->control('Filter.url',[
                    'label' => false,
                    'class' => 'form-control',
                    'placeholder' => 'Địa chỉ trang web'
                ])?>

                <?=$this->Form->control('Filter.traffic_package_id',[
                    'label' => false,
                    'type' => 'select',
                    'class' => 'form-control',
                    'options' => array_merge(['Select Package'],$package_list_name)
                ])?>

                <?= $this->Form->button('Lọc',['class' => 'btn btn-default btn-sm']) ?>
                <a href="<?=$this->Url->build(['_name' => 'buyer_trafficCampaigns'])?>" class="btn btn-link btn-sm">Cài lại</a>

                <?=$this->Form->end()?>

                <a href="<?=$this->Url->build(['_name' => 'buyer_create_trafficCampaigns'])?>" class="btn btn-info pull-right">Create</a>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-body">

            <div class="table-responsive" style="height: 500px">
                <table style="min-height: 300px;" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th><a class="desc" href="<?=$this->Url->build(['_name' => 'buyer_trafficCampaigns'])?>">ID</a></th>
                            <th><a href="#">Trạng thái</a></th>
                            <th>Từ khóa</th>
                            <th>Url</th>
                            <th><a href="#">Tổng xem</a></th>
                            <th><a href="#">Tổng đã xem</a></th>
                            <th><a href="#">Xem hôm nay</a></th>
                            <th><a href="#">Xem mỗi ngày</a></th>
                            <th><a href="#">Tổng tiền</a></th>
                            <th><a href="#">Tạo</a></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($traffics)) : ?>
                        <?php foreach ($traffics as $traffic) : ?>
                            <?php $status_text = $status_class = '';
                                $invoice_status = '';
                                $buyer_invoice = $traffic->buyer_invoice;
                                if (!empty($buyer_invoice)) $invoice_status = $buyer_invoice->status;
                                if ($invoice_status){
                                    switch ($traffic->status){
                                        case 1: {
                                            $status_text = 'Hoạt Động';
                                            $status_class = 'badge-success';
                                        } break;
                                        case 2: {
                                            $status_text = 'Hoàn Thành';
                                            $status_class = 'badge-primary';
                                        } break;
                                        case 3: {
                                            $status_text = 'Kết Thúc';
                                            $status_class = 'badge-secondary';
                                        } break;
                                        default : {
                                            $status_text = 'Tạm dừng';
                                            $status_class = 'badge-warning';
                                        }
                                    }
                                } else {
                                    $status_text = 'Chưa thanh toán';
                                    $status_class = 'badge-warning';
                                }
                            ?>

                            <tr>
                                <td><a href="<?=$this->Url->build(['controller' => 'TrafficCampaigns','action' => 'view', 'id' => $traffic->id])?>"><?=$traffic->id?></a></td>
                                <td><span class="badge <?=$status_class?>"><?=$status_text?></span></td>
                                <td>
                                    <div style="max-height: 20px; overflow: hidden">
                                        <div><?=$traffic->keywords?></div>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-height: 20px; overflow: hidden">
                                        <div><?=$traffic->url?></div>
                                    </div>
                                </td>
                                <td><?=number_format($traffic->count,0,',','.')?></td>
                                <td><?=number_format($traffic->views,0,',','.')?></td>
                                <td><?=number_format($traffic->viewToday,0,',','.')?></td>
                                <td><?=number_format($traffic->count_day,0,',','.')?></td>
                                <td>
                                    <?php $package_id = '';
                                        if (!empty($traffic->buyer_campaign->traffic_package_id)) $package_id = $traffic->buyer_campaign->traffic_package_id;
                                        if (!empty($traffic->buyer_package)) $package_id = $traffic->buyer_package;
                                    ?>

                                    <?php $package_price = packages()[$package_id]->price ?>
                                    <?=currency_format($traffic->count * $package_price)?>
                                </td>
                                <td><?=\Cake\I18n\Time::parse($traffic->created)?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-block btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Select Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?=$this->Url->build(['controller' => 'TrafficCampaigns','action' => 'view', 'id' => $traffic->id])?>">Lượt xem</a></li>
                                            <li><a href="<?=$this->Url->build(['controller' => 'TrafficCampaigns','action' => 'edit', 'id' => $traffic->id])?>">Sửa</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div><!-- /.box-body -->
    </div>

    <ul class="pagination">
    </ul>

    <?php if (!empty(get_option('info_banking'))): ?>
    <div class="modal fade" id="info_banking" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Thông tin cần chuyển khoản.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?=get_option('info_banking')?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

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
