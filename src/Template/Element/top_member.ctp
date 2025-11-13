<?php
/**
 * @var \App\View\AppView $this
 */
?>

<?php
    $memberBonus = json_decode($memberBonus ?? []);
    $bonus_checked = function ($view,$memberBonus){
        $result = 'Chưa đạt';
        if (empty($memberBonus)) return $result;
        foreach ($memberBonus as $bonus){
            if ($view >= $bonus->view) $result = number_format($bonus->bonus,0,',','.');
        }
        return $result;
    };


   /* $bonus = [
        0 => 'Chưa đạt',
        1 => 'Chưa đạt', //300
        2 => '100.000', //1000
        3 => '300.000', //2000
        4 => '600.000', //3000
        5 => '1.000.000', //4000
        6 => '2.000.000' // 5000
    ];*/
    $logged_user = user();
?>

<div class="payout-rates">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation">
                    <a href="#today" aria-controls="today" role="tab" data-toggle="tab">
                        <?= __('Hôm nay') ?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#yesterday" aria-controls="yesterday" role="tab" data-toggle="tab">
                        <?= __('Hôm qua') ?>
                    </a>
                </li>
                
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
                
                <div role="tabpanel" id="today" class="tab-pane">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= __('STT') ?></th>
                                <th><?= __('Username') ?></th>
                                <th><?= __('View') ?></th>
                                <th><?= __('Thưởng') ?></th>
                            </tr>
                            </thead>
                            <?php $i = 1; ?>
                            <?php foreach ($td_top_members as $top_member) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <?php if (in_array($logged_user->role, group_admin())) : ?>
                                    <td><?=$top_member->user->username ?></td>
                                    <?php else: ?>
                                     <td><?= h(substr($top_member->user->username, 0, 3)) . '******' ?></td>
                                    <?php endif; ?>
                                    <td><?= number_format($top_member->count) ?></td>
                                    <td>
                                        <?php echo $bonus_checked($top_member->count,$memberBonus) ?>
                                        <?php
/*                                            if($top_member->count >= 5000) {
                                                echo '<span class="text-success">'.$bonus[6].'</span>';
                                            } elseif($top_member->count >= 4000) {
                                                echo '<span class="text-info">'.$bonus[5].'</span>';
                                            } elseif($top_member->count >= 3000) {
                                                echo $bonus[4];
                                            } elseif($top_member->count >= 2000) {
                                                echo $bonus[3];
                                            } elseif($top_member->count >= 1000) {
                                                echo $bonus[2];
                                            } elseif($top_member->count >= 500) {
                                                echo $bonus[1];
                                            } else {
                                                echo $bonus[0];
                                            }
                                        */?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="yesterday">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= __('STT') ?></th>
                                <th><?= __('Username') ?></th>
                                <th><?= __('View') ?></th>
                                <th><?= __('Thưởng') ?></th>
                            </tr>
                            </thead>
                            <?php $i = 1; ?>
                            <?php foreach ($yes_top_member as $top_member) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <?php if (in_array($logged_user->role, group_admin())) : ?>
                                    <td><?=$top_member->user->username ?></td>
                                    <?php else: ?>
                                     <td><?= h(substr($top_member->user->username, 0, 3)) . '******' ?></td>
                                    <?php endif; ?>
                                    <td><?= number_format($top_member->count) ?></td>
                                     <td>
                                        <?php echo $bonus_checked($top_member->count,$memberBonus) ?>
                                        <?php
/*                                            if($top_member->count >= 5000) {
                                                echo '<span class="text-success">'.$bonus[6].'</span>';
                                            } elseif($top_member->count >= 4000) {
                                                echo '<span class="text-info">'.$bonus[5].'</span>';
                                            } elseif($top_member->count >= 3000) {
                                                echo $bonus[4];
                                            } elseif($top_member->count >= 2000) {
                                                echo $bonus[3];
                                            } elseif($top_member->count >= 1000) {
                                                echo $bonus[2];
                                            } elseif($top_member->count >= 100) {
                                                echo $bonus[1];
                                            } else {
                                                echo $bonus[0];
                                            }
                                        */?>
                                    </td>
                                    
                                   
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
        </div>

    </div>
