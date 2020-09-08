<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy_member\regist.php
*ファイル名:regist.php
*アクセスURL:http://localhost/DT/buy_member/regist.php
*/
namespace buy_member;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use buy_member\master\initMaster;
use buy_member\Bootstrap;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

//初期データを設定
$dataArr = [
    'family_name' => '',
    'first_name' => '',
    'family_name_kana' => '',
    'first_name_kana' => '',
    'sex' => '',
    'year' => '',
    'month' => '',
    'day' => '',
    'zip1' => '',
    'zip2' => '',
    'address' => '',
    'address1' => '',
    'email' => '',
    'password' => '',
    'password1' => '',
    'tel1' => '',
    'tel2' => '',
    'tel3' => '',
];

//エラーメッセージの定義、初期
$errArr = [];
foreach ($dataArr as $key => $value) {
    $errArr[$key] = '';
}

//静的クラス

list($yearArr, $monthArr, $dayArr) = initMaster::getDate();
//list:右辺の配列の要素を、左辺の変数に代入することができる
$sexArr = initMaster::getSex();

$context = [];

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['sexArr'] = $sexArr;
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate('regist.html.twig');
$template->display($context);