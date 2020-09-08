<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy_member\list.php
*ファイル名:list.php
*アクセスURL:http://localhost/DT/buy_member/list.php
*/
namespace buy_member;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use buy_member\Bootstrap;
use buy_member\master\initMaster;
use buy_member\lib\Database;
use buy_member\lib\Common;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);
$db = new Database(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME);

$query = " SELECT "
        . " mem_id, "
        . " family_name, "
        . " first_name, "
        . " family_name_kana, "
        . " first_name_kana, "
        . " sex, "
        . " email, "
        . " traffic, "
        . " regist_date "
        . " FROM "
        . "     buy_member ";
$dataArr = $db->select($query);
$db->close();

$context = [];
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('list.html.twig');
$template->display($context);