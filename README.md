## サイトURL
  https://textsnippet.noviceapp.net/snippets

## 機能要件
### **1. スニペットのアップロード**

- ユーザーはテキストエリアにテキストやコードを貼り付けます。
- 可読性を向上させるために、構文ハイライトを適用したいプログラミング言語を選択できます。
- ユーザーが内容を送信すると、スニペット用の一意の URL が生成されます。URL は一意の文字列に基づいています。フォーマットは、https://{domain}/{path}/{unique-string} のような形で、URL のパースには、例えば [parse_url](https://www.php.net/parse_url) のようなライブラリを活用することが可能です。

### **2. スニペットの閲覧**

- 一意の URL にアクセスしてスニペットを閲覧できます。
- コードの場合、提出時に選択した言語に基づいてシンタックスハイライトを適用します。

### **3. スニペットの有効期限設定**

- スニペットの有効期限（例：10 分、1 時間、1 日、永続）を設定するオプションを持ちます。
- 期限切れになったスニペットは自動的に削除され、「Expired Snippet」というメッセージを表示します。
    
    →下記のように解釈：
    
    - **期限切れになったスニペットの自動削除**:
        - 要件に「自動的に削除」とありますが、実際には削除せずに表示内容を変更することを求められていると解釈
        - DB上のレコードは削除せずに残し、スニペットの内容を「Expired Snippet」に更新する
    - **「Expired Snippet」というメッセージの表示**:
        - 期限切れのスニペットは「Expired Snippet」というメッセージを表示する。
        - スニペット詳細画面で期限切れのスニペットには「Expired Snippet」を表示。
        - スニペット一覧画面では「Expired Snippet」と表示し、他のメタデータは表示したままにする。
    
    → 2つの実現方法
    
    - クーロンジョブにより定期実行
    - 一覧表示時にチェック

### **4. データストレージ**

- バックエンドへ送信される全てのユーザーからの入力は、厳格に検証とサニタイズが行われる必要があります。
- SQL インジェクションを防ぐために、スニペットは安全に保存します。

### **5. フロントエンドインターフェース**

- シンプルで使いやすいインターフェースを持ち、テキストまたはコードの送信を容易にします。
- スニペットが成功して送信されると、その内容にアクセスできる一意の URL が生成され、ユーザーに表示されます。

### **6. エラーハンドリング**

- 大量のテキストやコード、またはサポートされていない文字が送信された場合でも、適切に処理し、エラーメッセージを表示します。
- 
## 非機能要件
### **デプロイメント**

- サービスは、ユーザーが簡単に記憶できるドメインやサブドメインで公開する必要があります。
- サービスが常に利用可能で、サービスが利用できない時間が極力少ない状態を保つ必要があります。
- エンジニアが迅速に開発とデプロイを行えるように、リポジトリ同期の Git コマンドを実行するだけでコードの更新と同期がライブで行える必要があります。

### **パフォーマンス**

- スニペットを効率よく取得し、ユーザーが迅速に閲覧できるようにする必要があります。
- ページの読み込みが極端に遅くなることなく、速やかに構文ハイライトを表示できるようにします。

### **スケーラビリティ**

- 大量のスニペットが同時に送信されても、それらをスムーズに処理できるシステムを確立する必要があります。

### **セキュリティ**

- スニペットは安全に保存され、不正アクセスを防ぐ仕組みを整える必要があります。
- 安全な接続とデータの暗号化を保証するために、HTTPS を採用する必要があります。

## 技術要件

### **ウェブインターフェース**

- フロントエンドのデザインには HTML/CSS を使用します。
- ダイナミックなインタラクションには JavaScript を利用します。
- テキストやコード入力に monaco エディタの使用を検討します。

### **バックエンド**

- スニペットの送信、URL の生成、スニペットの提供を処理するために、静的型付けが可能なサーバサイドの OOP言語、例えば PHP 8.0 を使用します。
- 一意の URL 生成には、hash()のようなハッシュ関数を利用します。

### **データベース**

- 提出されたスニペット、それらの URL、ハイライト用のプログラム言語、送信時刻、有効期限を記録するために MySQL を使用します。

### **ミドルウェア**

- 必要なすべてのデータベーススキーマをセットアップするためのマイグレーション管理システムを使用します。
- データベースとのインタラクションには MySQLWrapper クラスを採用します。

## 今後実装・改善したいこと
- Unit TestおよびCI/CD環境構築
- Nginxの設定変更(スタイルファイル読み込みが404エラーになるのが1週間格闘しても解決できず...)