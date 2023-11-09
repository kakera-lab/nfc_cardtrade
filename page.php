<?php

class View
{
    function __construct(string $type, array $data)
    {
        echo $this->main_view($this->$type($data));
    }

    // 基本となるViewで{$contents}の部分に固有の要素を組み込み表示する。
    function main_view($contents = null)
    {
        $data = date("ymdhis");
        return <<<EOF
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>kakera-lab</title>
<meta name="description" content="カード交換システム">
<link rel="stylesheet" href="style.css?{$data}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
{$contents}
</body>
</html>
EOF;
    }

    // チェックイン画面
    function check_user(array $data): string
    {
        $id = $data["id"];
        return <<<EOF
<form method="post" action="">
    <div class="view">
    {$this->card($id)}
    </div>
    <div><button class="button" type="submit" name="check_in" value=1>チェックイン</button></div>
    <div><button class="button" type="submit" name="check_out" value=1>戻る</button></div>
</form>
EOF;
    }

    // 新規取得カードを表示
    function card_add(array $data): string
    {
        $id = $data["id"];
        return <<<EOF
<div class="view">
{$this->card($id)}
</div>
<div><button class="button" type="button" onclick="location.href='./index.php'">戻る</button></div>
EOF;
    }

    //カードリストを描画する
    function card_list(array $ids): string
    {
        $data = "";
        foreach ($ids as $id) {
            $data = $data . $this->Card($id);
        }
        // 最大枚数を取得した場合
        if (count($ids) === 39) {
            $data = $data . $this->Card("full");
        }
        return <<<EOF
<div class="view">
{$data}
</div>
EOF;
    }

    // カードを描画する
    protected function card(string $id): string
    {
        return <<<EOF
\t<div class="box"><img class="card" src="./image/{$id}.png" alt="no image"/></div>\n
EOF;
    }
}
?>
