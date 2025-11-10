<?php
/**
 * @var \App\View\AppView $this
 */
?>

<?php
    $bonus_list = json_decode($bonus_list ?? []);
    $logged_user = user();
?>

<?php if (!empty($bonus_list)) : ?>
<ul class="bonus-list">
    <?php foreach ($bonus_list as $bonus) : ?>
        <li><strong><?=$bonus->view?></strong> view thưởng <span style="color:#e74c3c;"><strong><?=number_format($bonus->bonus,0,',','.')?>đ</strong></span></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
