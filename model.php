<?php

class API
{
    protected $jsonfile;

    function __construct()
    {
        // DB(Json)のファイルパス
        $this->jsonfile = "./json/db.json";
    }

    // ユーザーを確認するメインコード
    function check_user(string $id): string
    {
        $data = $this->load_json();
        if (!isset($data[$id])) {
            return "";
        } else {
            return $data[$id]["name"];
        }
    }

    // システムのチェックインを行うメインのコード
    function check_in(string $id): bool
    {
        $data = $this->load_json();
        if (!isset($data[$id])) {
            return false;
        }
        // 自分のカードをリストに追加
        $this->add_card($id, $id);
        // トークン発行
        $token = $this->create_auth_token();
        $data[$id]["token"] = $token;
        $this->save_json($data);
        // トークンの有効期限は1日(86400)
        if (setcookie("auth", $token, time() + 86400) === false) {
            return false;
        } else {
            return true;
        }
    }

    // 所有カード一覧を取得する。
    function load_card(string $id): array
    {
        $data = $this->load_json();
        return $data[$id]["cards"];
    }

    // カードを追加するメインコード
    function add_card(string $id, string $card_id): bool
    {
        $data = $this->load_json();
        array_unshift($data[$id]["cards"], $card_id);
        if ($this->save_json($data)) {
            return true;
        } else {
            return false;
        }
    }

    // カードをすでに持っているか確認する。
    function add_check(string $id, string $card_id): bool
    {
        $data = $this->load_json();
        if (in_array($card_id, $data[$id]["cards"])) {
            return false;
        } else {
            return true;
        }
    }

    // 認証トークンをユーザーIDに変換する
    function token_to_id(string $token): string
    {
        $id = "";
        $data = $this->load_json();
        foreach ($data as $key => $value) {
            if ($value["token"] === $token) {
                $id = $key;
                break;
            }
        }
        return $id;
    }

    // 認証用トークンを発行
    protected function create_auth_token(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(40));
    }

    // DB(Json)の情報を全取得
    protected function load_json(): array
    {
        $data = file_get_contents($this->jsonfile);
        $data = json_decode($data, true);
        return $data;
    }

    // DB(Json)に情報を書き込む
    protected function save_json(array $data): bool
    {
        $data = json_encode($data);
        if (file_put_contents($this->jsonfile, $data) === false) {
            return false;
        } else {
            return true;
        }
    }
}
?>
