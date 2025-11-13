<?php
$baseDir = dirname(dirname(__FILE__));

return [
    'plugins' => [
        'ADmad/SocialAuth' => $baseDir . '/vendor/admad/cakephp-social-auth/',
        'AdminlteAdminTheme' => $baseDir . '/plugins/AdminlteAdminTheme/',
        'AdminlteMemberTheme' => $baseDir . '/plugins/AdminlteMemberTheme/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'ClassicTheme' => $baseDir . '/plugins/ClassicTheme/',
        'CloudTheme' => $baseDir . '/plugins/CloudTheme/',
        'DebugKit' => $baseDir . '/plugins/DebugKit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'ModernTheme' => $baseDir . '/plugins/ModernTheme/',
        'Queue' => $baseDir . '/vendor/dereuromark/cakephp-queue/',
        'WyriHaximus/TwigView' => $baseDir . '/vendor/wyrihaximus/twig-view/',
    ],
];
