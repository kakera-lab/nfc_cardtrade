# NFC カードシステム。
## 用途
* kakera主催のイベント用に交流のためのエンタメとして作成。
* 応用用途として、宝探しで宝物を取集するなど。
## 中身
* NFCをiPhone/Androidで読み込んでAPI通信を行うシステム。
  * NFC読み取る処理は端末に丸投げしている。 
* API側でhttp通信の内容に応じて処理を分ける。
* データベース使わずJsonで実装
  *　今回の用途では大規模なデータを扱う必要がなかったため使用していない。
## NFCカード
* AmazonでNFCのカードを購入
  * [例えばこれとか(2023年)](https://www.amazon.co.jp/50%E6%9E%9ANFC%E3%82%AB%E3%83%BC%E3%83%89NTAG215%E3%82%AB%E3%83%BC%E3%83%89%E7%99%BD%E7%84%A1%E5%9C%B0-%E3%83%9B%E3%83%AF%E3%82%A4%E3%83%88PVC%E3%82%AB%E3%83%BC%E3%83%89-215%E3%82%AB%E3%83%BC%E3%83%89%E3%82%BF%E3%82%B0-android%E5%AF%BE%E5%BF%9C-%E3%82%B7%E3%83%A7%E3%83%BC%E3%83%88%E3%82%AB%E3%83%83%E3%83%88%E3%82%A2%E3%83%97%E3%83%AA%E5%AF%BE%E5%BF%9C/dp/B07P6679ZV/ref=sr_1_16?crid=2LVNAS404H7KJ&keywords=nfc%E3%82%AB%E3%83%BC%E3%83%89&qid=1692941866&sprefix=NFC%2Caps%2C177&sr=8-16&th=1)
* 書き込む情報
  * http通信を行う処理
  * 個別のID（それぞれのカードに固有のIDを記録する）
* [NFC Tools](https://apps.apple.com/jp/app/nfc-tools/id1252962749):NFCに情報を書き込むツール
## 現在の実装の処理
* APIとやりとりする情報は2つ
  * トークン
  * カード個別のID
* やりとりの流れ
  * Check in :
    * カード個別のIDだけをAPIに飛ばした場合、初めてAPIにアスセスしたとしてトークンを発行
    * トークンをクライアントに送信（cookieに保存：有効期間は24h）
  * Interaction :
    * Check in済みの端末からトークンとカード個別のIDの両方を送信した場合。
    * 処理の分岐を増やしたい場合はhttp通信のGetなりに分岐用の情報を付加するなり、APIのURLを分けるなりしてください。
