<?php
// Temporary file to check current theme
// Access: http://localhost/check_theme.php

require '../config/bootstrap.php';

use Cake\ORM\TableRegistry;

$optionsTable = TableRegistry::getTableLocator()->get('Options');
$theme = $optionsTable->find()
    ->where(['option_name' => 'theme'])
    ->first();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Theme Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            background: #f5f5f5;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
        }

        .theme-name {
            font-size: 24px;
            color: #4CAF50;
            font-weight: bold;
            padding: 20px;
            background: #f0f0f0;
            border-radius: 5px;
            margin: 20px 0;
        }

        .path {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>🎨 Current Theme</h1>
        <?php if ($theme): ?>
            <div class="theme-name">
                <?= htmlspecialchars($theme->option_value) ?>
            </div>
            <div class="path">
                <strong>Layout file:</strong><br>
                plugins/<?= htmlspecialchars($theme->option_value) ?>/src/Template/Layout/front.ctp
            </div>
        <?php else: ?>
            <div class="theme-name">ClassicTheme (Default)</div>
            <div class="path">
                <strong>Layout file:</strong><br>
                src/Template/Layout/front.ctp
            </div>
        <?php endif; ?>

        <hr style="margin: 30px 0;">
        <p style="color: #999; font-size: 12px;">
            ⚠️ Delete this file after checking (webroot/check_theme.php)
        </p>
    </div>
</body>

</html>