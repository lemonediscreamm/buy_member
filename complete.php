<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy_member\complete.php
*ファイル名:complete.php
*アクセスURL:http://localhost/DT/buy_member/complete.php
*/

namespace buy_member;

require_once ( dirname(__FILE__) . '/Bootstrap.class.php');

use buy_member\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
    'cache' => Bootstrap::CACHE_DIR
]);


$context = '';
$msg = '登録完了';
$context['msg'] = $msg;
$template = 'complete.html.twig';
$template = $twig->loadTemplate($template);
$template->display($context);



