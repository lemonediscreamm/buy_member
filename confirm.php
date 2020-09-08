<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy_member\confirm.php
*ファイル名:confirm.php
*アクセスURL:http://localhost/DT/buy_member/confirm.php
*/
namespace buy_member;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use buy_member\master\initMaster;
use buy_member\lib\Database;
use buy_member\lib\Common;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
    'cache' => Bootstrap::CACHE_DIR
]);

$db = new Database(Bootstrap::DB_HOST,Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME);
$common = new Common();

//モード判定（どの画面から来たのか判断）
//登録画面からきた場合
if (isset($_POST['confirm']) === true) {
    $mode = 'confirm';
}
//戻る場合
if (isset($_POST['back']) === true) {
    $mode = 'back';
}
//登録完了
if (isset($_POST['complete']) === true) {
    $mode = 'complete';
}
$errmail ='';
$errpass ='';
$errpass1 ='';
$mask_pass = '';
//ボタンのモードよって処理をかえる
switch ($mode) {
    case 'confirm'://新規登録
                   //データを受け継ぐ
                   // ↓この情報は入力には必要ない
        unset($_POST['confirm']);

        $dataArr = $_POST;

        //この値を入れないでPOSTするとUndefinedとなるので未定義の場合は空白状態としてセットしておく
        if (isset($_POST['sex']) === false) {
            $dataArr['sex'] = "";
        }
        //メールアドレス重複チェック
        if($dataArr['email'] !==''){
            $query ='';
            $query= "select email from buy_member";
            $res = $db->select($query);
            $emailArray = array_column($res, 'email');
            $result = array_search($dataArr['email'], $emailArray);
            if($result !== false){
            $errmail='すでに登録されているメールアドレスです';
            }
        }
        //パスワードが8桁以上かチェック
        $passNum = strlen($dataArr['password']);
        if($dataArr['password'] !==0 && $passNum < 8){
            $errpass='パスワードは８桁以上です';
        }        
        $passNum1 = strlen($dataArr['password1']);
        if($dataArr['password1']!==0 && $passNum1 < 8){
            $errpass1='パスワードは８桁以上です';
        }  
        //パスワードを*で表示
        $mask_pass = str_repeat("*", mb_strlen($dataArr['password'], "UTF8"));
        //エラーメッセージの配列作成
        $errArr = $common->errorCheck($dataArr);
        $err_check = $common->getErrorFlg();
        //err_check = false →エラーがあります
        //err_check = true →エラーがないです
        //エラーがなければconfirm.tpl 　あるとregist.tpl
        $template = ($err_check === true && $errmail === '' && $errpass ==='' && $errpass1 ==='') ? 'confirm.html.twig' : 'regist.html.twig';
        break;
    case 'back'://戻ってきたとき
                //ポストされたデータを元に戻すので、$dataArrに入れる
        $dataArr = $_POST;
        unset($dataArr['back']);
        
        //エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'regist.html.twig';
        break;
    case 'complete'://登録完了
        $dataArr =  $_POST;
        //↓この情報はいらないので外しておく
        unset($dataArr['complete']);
        $column = '';
        $insData = '';

        //foreach の中でSQL文を作る
        foreach ($dataArr as $key => $value) {
            $column .= $key . ', ';
            if ($key === 'password') {
                $value = password_hash ($dataArr['password'], PASSWORD_DEFAULT);
            }
            if ($key === 'password1') {
                $value = password_hash ($dataArr['password1'], PASSWORD_DEFAULT);
            }
            $insData .= ($key === 'sex') ? $db->quote($value) . ',' :
            $db->str_quote($value) . ', ';
        }

    $query = " INSERT INTO buy_member ( "
            . $column
            . " regist_date "
            ." ) VALUES ( "
            . $insData
            ." NOW() "
            . " ) ";
 
    $res = $db->execute($query);
    $db->close();
    
    if ($res === true) {
        //登録成功時は完成時はページへ
        //header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php');
        header('Location:http://localhost/DT/buy_member/complete.php');
        exit();
    } else {
        //登録失敗時は登録画面に戻る
        $template = 'regist.html.twig';
       
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }
    }

    break;
}
$sexArr = initMaster::getSex();

$context['sexArr'] = $sexArr;


list($yearArr, $monthArr, $dayArr) = initMaster::getDate();

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['errmail'] = $errmail;
$context['errpass'] = $errpass;
$context['errpass1'] = $errpass1;
$context['mask_pass'] = $mask_pass;
$template = $twig->loadTemplate($template);
$template->display($context);
