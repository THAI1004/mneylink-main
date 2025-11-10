<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var \App\Model\Entity\Post $post
 * @var string $ad_captcha_above
 * @var string $ad_captcha_below
 */
$this->assign('title', get_option('site_name'));
$this->assign('description', get_option('description'));
$this->assign('content_title', get_option('site_name'));
$this->assign('og_title', $link->title);
$this->assign('og_description', $link->description);
$this->assign('og_image', $link->image);
?>

<?php $this->start('scriptTop'); ?>
<style>
    .report-form .loader{
        font-size: 2px;
        display: none;
        top: -4px;
        left: 11px;
    }
    .report-form.loading .loader{
        display: inline-block;
    }
    .report-error{
        display: none;
    }
</style>

<script type="text/javascript">
  if (window.self !== window.top) {
    window.top.location.href = window.location.href;
  }
</script>
<style>
    .d-desktop {display: block; border:  1px solid red;}
    .d-mobile {display: none;border:  1px solid red;}
    @media only screen and (max-width: 768px) {
        .d-desktop {display: none;}
        .d-mobile {display: block;}
    }
    .input-group-addon.copy-it:hover{
        color: red;
    }
    </style>
<?php $this->end(); ?>

<?php
    $keywords = explode(PHP_EOL, $traffic->keywords);
    $rand = rand(0,count($keywords)-1);
    $keyword = $keywords[$rand];
    $copy = $traffic->copy;
    $isProxy = (!empty($isProxy)) ? $isProxy : 0;
?>

<div class="row">
    <?php if (!$isProxy) : ?>
    <div class="col-md-10 col-md-offset-1">
        <div class="box box-success">
            <div class="box-body text-left">
                <?= $this->Form->create(null, ['id' => 'link-view']); ?>
                <?= $this->Form->hidden('action', ['value' => 'traffic']); ?>
				<?= $this->Form->hidden('_id_traffic', ['value' => $traffic->id]); ?>
                <?=
			        $this->Form->control('keyword_', [
			        	'label' => 'Lấy mã và nhập vào ô dưới đây',
			            'class' => 'form-control',
			            'type' => 'text',
			            'style' => 'display: none',
			            'value' => $keyword
			        ]);
			    ?>
                <?= $this->Flash->render() ?>
                <div style="font-size: 17px; margin-top: 20px;">

                    <h4>Video hướng dẫn lấy mã DOWNLOAD:</h4>
                    <center>
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/G2bUX7AFE3A"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </center>
                    <hr>
                    <h3 style="text-align: center;">Bạn hãy lấy mã theo các bước để có thể tải file nhé:</h3>
                    <h4 style="text-align: center;"><span style="color: #ff0000;">LƯU Ý: Không sử dụng VPN hoặc 1.1.1.1 khi vượt link
                    </h4>
<?php if ($traffic->kind == "google") : ?>
                    <p><strong>Bước 1: </strong>Mở tab mới, truy cập <a href="https://google.com.vn"
                                                                        target="_blank"><strong>Google.com</strong></a>
                        <img src="https://uploads-ssl.webflow.com/634a0555a626bc61e897026c/634a17cda7ad04579c63321e_google.png"
                             width="150px"></p>
                    <div style="margin-bottom: 10px">
                        <strong>Bước 2: </strong>Tìm kiếm từ khóa: <strong
                                style="color: red; cursor: no-drop;-webkit-user-select: none;-webkit-touch-callout: none;-moz-user-select: none;-ms-user-select: none;user-select: none;"><?= $keyword ?></strong>
                        <?php if ($copy) : ?>
                            <div class="input-group-addon copy-it" data-clipboard-text="<?= $keyword ?>"
                                 data-toggle="tooltip" data-placement="top" title="" data-original-title="Sao chép"
                                 style="display: inline;border: transparent;background: transparent;cursor: pointer;font-size: 20px;">
                                <i class="fa fa-clone"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <p>
                        <img src="https://uploads-ssl.webflow.com/634a0555a626bc61e897026c/634a381f007b7f7403708829_thanh-tim-kiem.png"
                             width="100%"></p>
                    <p><strong>Bước 3: </strong>Trong kết quả tìm kiếm Google, hãy tìm website giống dưới hình:</p>
                    <center>

                        <img src="<?= $traffic->img_desktop ?>" class="d-desktop"/>
                        <img src="<?= $traffic->img_mobile ?>" class="d-mobile"/>

                    </center>
                    <br>
                    <p>(Nếu trang 1 không có hãy tìm ở trang 2 nhé <img
                                src="https://uploads-ssl.webflow.com/634a0555a626bc61e897026c/634a383760887091a91b4a5a_trang-google.png"
                                width="300px">)</p>
<?php else: ?>
                    <p><strong>Bước 1: </strong>Mở tab mới trên trình duyệt</p>
                    <p><strong>Bước 2: </strong>Gõ địa chỉ <strong><?= $traffic->url?></strong></p>
                    <p><strong>Bước 3: </strong>Enter vào truy cập</p>
<?php endif; ?>
                    <?php if ($traffic->traffic_ver2) : ?>
                        <p style="margin-top: 15px"><strong>Bước 4:</strong> Kéo xuống cuối trang tìm và bấm vào nút <strong>LẤY MÃ NGAY</strong> sau đó
                            chờ 1 lát để hiện nút (Giống hình dưới). Click vào nút để qua trang chứa mã</p>
                        <center><img style="max-width: 600px; width: 100%; border: 1px dashed red"
                                     src="https://uploads-ssl.webflow.com/634a0555a626bc61e897026c/64229ce6a12fcdd4d5234bf4_mney-ver2-buoc4.png"/>
                        </center>
                        <p style="margin-top: 15px"><strong>Bước 5:</strong> Kéo xuống lại vị trí của nút <strong>LẤY MÃ NGAY</strong> trước đó và
                            chờ 1 lát để lấy mã: (Giống hình dưới)</p>
                        <center><img style="max-width: 600px; width: 100%; border: 1px dashed red"
                                     src="https://uploads-ssl.webflow.com/634a0555a626bc61e897026c/64229cea821fccb6bb9a4345_mney-ver2-buoc5.png"/>
                        </center>
                    <?php else: ?>
                        <p><strong>Bước 4:</strong> Cuộn xuống cuối bài viết rồi bấm vào nút <strong>LẤY MÃ NGAY</strong> và
                            chờ 1 lát để lấy mã: (Giống hình dưới)</p>
                        <center><img style="max-width: 600px; width: 100%; border: 1px dashed red"
                                     src="https://uploads-ssl.webflow.com/634a0555a626bc61e897026c/63f3093c48a58b3c34c46046_pass-mney-ok.png"/>
                        </center>
                    <?php endif; ?>
                </div>
                <br><br>
				<?=
			        $this->Form->control('code_', [
			        	'label' => 'Lấy mã và nhập vào ô dưới đây',
			            'class' => 'form-control',
			            'type' => 'text',
			            'placeholder' => 'Nhập mã vừa kiếm được vào đây'
			        ]);
			    ?>
                <?= $this->Form->button(__('Click here to continue'), [
                    'class' => 'btn btn-primary btn-captcha',
                    'id' => 'invisibleCaptchaShortlink',
                ]); ?>

                <?= $this->Form->end() ?>

                <hr>
                <div class="text-center">
                    <p>Bạn không lấy được mã?</p>
                    <a href="/change/<?=$alias?>"><button class="btn btn-danger btn-report" style="min-width: 193px">Lấy mã mới</button></a>
                    <div class="report-message" style="margin-top: 10px; color: green; font-weight: bold;"></div>
                </div>
                <?php if (empty($hasReport)) : ?>
                <div class="modal fade" id="modal-report" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Báo lỗi</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger report-error" role="alert"></div>
<!--                                --><?//=$this->Form->create('memberReport',[
//                                    'action' => 'report',
//                                    'class' => 'report-form'
//                                ])?>
<!--                                    <label>Lỗi gặp phải</label>-->
<!--                                    --><?//=$this->Form->radio('type', [
//                                        '1' => 'Không thấy mã',
//                                        '2' => 'Không thấy trang web',
//                                        '3' => 'Lỗi hãy thử lại'
//                                    ], [
//                                        'style' => 'margin-right: 10px',
//                                        'value' => 1,
//                                        'label' => [
//                                            'style' => 'display: block',
//                                        ],
//                                    ]); ?>
<!--                                    --><?//=$this->Form->hidden('traffic_id',['value' => $traffic->id])?>
<!--                                    --><?//=$this->Form->hidden('ip',['value' => get_ip()])?>
<!--                                    --><?//=$this->Form->button('<span>Report</span><span class="loader"></span>',['class' => 'btn btn-primary btn-block btn-flat btn-captcha'])?>
<!--                                --><?//=$this->Form->end()?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <hr>
                <?php $adsList = get_option('adsList'); ?>
                <?php if (!empty($adsList)) : $adsList = json_decode($adsList); ?>
                    <div class="ads-list" style="margin-top: 30px">
                        <?php foreach ($adsList as $k => $list) : ?>
                            <div class="ads-<?=$k?>">
                                <?=$list->script?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div id="adm-container-76" style="opacity: 0; position: absolute"></div><script data-cfasync="false" async type="text/javascript" src="//adcheap.network/display/items.php?76&34&250&250&1&0&0"></script>

<?php if(rand(0,0) == 1): ?>
    <style type="text/css">
        
.ads {
    opacity: 0;
}

.ads-abc {
    z-index: 999;
    position: fixed;
    top: 10%;
    left: 10%
}
    </style>
   
<!--<div class="ads">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5381380878358182"
     crossorigin="anonymous"></script>

<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-5381380878358182"
     data-ad-slot="1587454777"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5381380878358182"
     crossorigin="anonymous"></script>

<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-5381380878358182"
     data-ad-slot="5298086186"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5381380878358182"
     crossorigin="anonymous"></script>

<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-5381380878358182"
     data-ad-slot="6419596165"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>-->
<?php endif; ?> 

                <div class="text-left">

                    <h3><?= __('What is {0}?', h(get_option('site_name'))) ?></h3>
                    <p><?= __(
                            '{0} is a completely free tool where you can create short links, which apart ' .
                            'from being free, you get paid! So, now you can make money from home, when managing and ' .
                            'protecting your links. Register now!',
                            h(get_option('site_name'))
                        ) ?></p>

                    <h3><?= __('Shorten URLs and earn money') ?></h3>
                    <p><?= __("Signup for an account in just 2 minutes. Once you've completed your registration'.
                    'just start creating short URLs and sharing the links with your family and friends.") ?></p>

                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <?php else : ?>
    <div class="data-is-proxy">
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-warning"></i> Bạn đang sử dụng Proxy (VPN) vui lòng tắt đi và reset lại trang web!
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $this->start('scriptBottom'); ?>
<!-- <script>
    $(document).ready(function(){
        $('.ads').addClass('ads-abc');
    });
</script> -->
<script type="text/javascript">
    $('.btn-report').click(function (){
        if ($(this).hasClass('hasReport')) alert('Bạn đã báo lỗi rồi!')
    })
    $('.report-form').submit(function (e){
        e.preventDefault();
        let $this = $(this);
        if (!$this.hasClass('loaded')){
            let data = $this.serialize();
            $.ajax({
                url:$this.attr('action'),
                method:'POST',
                data:data,
                dataType:'JSON',
                beforeSend:function (){
                    $this.addClass('loading');
                    $this.closest('.modal').addClass('loading');
                },
                success:function (data){
                    $this.removeClass('loading').addClass('loaded');
                    $this.closest('.modal').removeClass('loading').addClass('loaded');
                    $('#modal-report').modal('hide');
                    $('.report-message').html(data.message);
                    if (data.status) $this.find('.btn-captcha').attr('disabled',true);
                }
            })
        }
    })
</script>
<?php $this->end(); ?>
