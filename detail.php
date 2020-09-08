<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy_member\detail.php
*ファイル名:detail.php
*アクセスURL:http://localhost/DT/buy_member/detail.php
*/

namespace buy_member;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use buy_member\Bootstrap;
use buy_member\master\initMaster;
use buy_member\lib\Database;
use buy_member\lib\Common;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, array(
    'cache' => Bootstrap::CACHE_DIR
));

$db = new Database(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME);
$initMaster = new initMaster();

if (isset($_GET['mem_id']) === true && $_GET['mem_id'] !== '') {
    $mem_id = $_GET['mem_id'];

    $query = " SELECT "
        . " mem_id, "
        . " family_name, "
        . " first_name, "
        . " family_name_kana, "
        . " first_name_kana, "
        . " sex, "
        . "year, "
        . "month, "
        . "day,"
        . "zip1, "
        . "zip2, "
        . "address, "
        . " email, "
        . "tel1, "
        . "tel2, "
        . "tel3, "
        . " traffic, "
        . "contents, "
        . " regist_date "
        . " FROM "
        . "     buy_member "
        . " WHERE "
        . "      mem_id = " . $db->quote($mem_id);
    $data = $db->select($query);
    $db->close();
    $dataArr = ($data !== "" && $data !== []) ? $data[0] : '';
    
    $dataArr['traffic'] = explode('_', $dataArr['traffic']);
    $context = [];
    $context['trafficArr'] = $initMaster->getTrafficWay();
    $context['dataArr'] = $dataArr;
    $template = $twig->loadTemplate('detail.html.twig');
    $template->display($context);
} else {
    header('Location: ' . Bootstrap::ENTRY_URL .'list.php');
    exit(); 
}    