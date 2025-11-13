<?php

/**
 * @var \App\View\AppView $this
 */

// Lấy năm hiện tại
$currentYear = date("Y");
// Lấy tên trang web (đã được escape)
$siteName = h(get_option('site_name'));
// Kiểm tra action hiện tại
$isHomePage = $this->request->getParam('action') === 'home';
// Lấy các liên kết xã hội (đã được escape)
$facebookUrl = h(get_option('facebook_url'));
$twitterUrl = h(get_option('twitter_url'));
?>

<footer class="bg-light text-dark pt-5 pb-3 border-top">
    <?php if ($isHomePage) : ?>
        <div class="container mb-4">
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 payment-methods-list">
                <?php
                if (function_exists('get_withdrawal_methods')) :
                    foreach (get_withdrawal_methods() as $method) :
                        if (!empty($method['image'])) :
                            // Giả sử ảnh của phương thức thanh toán là ảnh sáng
                            echo '<div class="payment-method-item">';
                            echo $this->Assets->image($method['image'], ['class' => 'img-fluid', 'alt' => $method['name'] ?? 'Payment Method']);
                            echo '</div>';
                        endif;
                    endforeach;
                endif;
                ?>
            </div>
        </div>
        <div class="separator mb-4">
            <div class="container">
                <hr class="text-muted">
            </div>
        </div>
    <?php endif; ?>

    <div class="copyright-container">
        <div class="container py-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">

                <div class="footer-links order-md-1 order-1">
                    <?php
                    if (function_exists('menu_display')) :
                        echo menu_display('menu_footer', [
                            // list-unstyled, d-flex và gap-3 đảm bảo các liên kết nằm trên một dòng
                            'ul_class' => 'list-unstyled d-flex gap-3 m-0',
                            'li_class' => '',
                            'a_class' => 'text-dark text-decoration-none hover-link',
                        ]);
                    endif;
                    ?>
                </div>

                <div class="social-links order-md-2 order-3 mt-2 mt-md-0">
                    <ul class="list-inline d-flex gap-2 m-0">
                        <?php if (get_option('facebook_url')) : ?>
                            <li class="list-inline-item m-0">
                                <a href="<?= $facebookUrl ?>" target="_blank" rel="noopener noreferrer" class="text-primary fs-5">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (get_option('twitter_url')) : ?>
                            <li class="list-inline-item m-0">
                                <a href="<?= $twitterUrl ?>" target="_blank" rel="noopener noreferrer" class="text-info fs-5">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="copyright order-md-3 order-2 mt-2 mt-md-0">
                    <small class="text-muted">
                        <?= __('Copyright &copy;') ?> <?= $siteName ?> <?= $currentYear ?>
                    </small>
                </div>

            </div>
        </div>
    </div>
    <?php $addFooter = get_option('addFooter', null); ?>
    <?php if (!empty($addFooter)): ?>
        <div class="add-footer mt-3" style="display: none">
            <div class="container">
                <?php echo $addFooter; ?>
            </div>
        </div>
    <?php endif; ?>
</footer>

<?= $this->element('js_vars'); ?>
<?= $this->fetch('scriptBottom') ?>
<?php
$footerCode = get_option('footer_code');
if (!empty($footerCode)) :
    echo $footerCode;
endif;
?>