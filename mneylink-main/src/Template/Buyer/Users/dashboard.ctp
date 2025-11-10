<?php
$this->assign('title', __('Bảng Điều Khiển'));
$this->assign('description', '');
$this->assign('content_title', __('Bảng Điều Khiển'));
?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-bullhorn"></i>
            <h3 class="box-title">Thông Báo</h3>
        </div>
        <div class="box-body chat">
            <?php if (!empty($page)) : ?>
            <div class="item">
                <p class="testimonial">
                    <span>
                        <small class="text-muted pull-right"><i class="fa fa-clock-o" style="margin-right: 4px"></i><?=\Cake\I18n\Time::parse($page->modified)?></small>
                        <i class="fa fa-angle-double-right"></i> <b><?=$page->title?></b>
                    </span>
                </p>
                <div class="content" style="padding: 0"><?=$page->content?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php $this->start('scriptBottom'); ?>
<?php $this->end();
