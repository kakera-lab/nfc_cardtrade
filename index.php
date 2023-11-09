<?php

include "./page.php";
include "./model.php";

$API = new API();
// トークンがない => チェックイン画面を表示
if (!isset($_COOKIE["auth"])) {
    // チェックイン画面から遷移してきたとき => チェックイン処理 => リダイレクト => トークンが存在する処理へ
    if (isset($_POST["check_in"]) && $API->check_in($_GET["id"])) {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
    // チェックイン画面を表示
    elseif (isset($_GET["id"]) && $API->check_user($_GET["id"]) !== "") {
        $Page = new View("check_user", ["id" => $_GET["id"]]);
    }
    // 何かしら不備がある => エラーメッセージ
    else {
        setcookie("auth", "", time() - 600);
        exit("ページを閉じて再度カードを読み込んでください。");
    }
}
// トークンが存在
else {
    $id = $API->token_to_id($_COOKIE["auth"]);
    $card_id = isset($_GET["id"]) ? $_GET["id"] : $id;
    // トークンが有効
    if ($id !== "") {
        // カードを持っていないので追加する => 追加画面を表示
        if ($API->add_check($id, $card_id) && $API->add_card($id, $card_id)) {
            $Page = new View("card_add", ["id" => $card_id]);
        }
        // カード一覧を表示
        else {
            $Page = new View("card_list", $API->load_card($id));
        }
    }
    // トークンが無効 => エラーメッセージ
    else {
        setcookie("auth", "", time() - 600);
        exit("ページを閉じて再度カードを読み込んでください。");
    }
}
?>
