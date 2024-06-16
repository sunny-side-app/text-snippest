# AWSプロバイダーの設定を行います。この設定により、TerraformはAWSと対話します。
provider "aws" {
  region = "ap-northeast-1" # 適切なAWSリージョンを指定します
}

# 既存のAWSインスタンスのデータを取得します。
# instance_idを指定することで、そのインスタンスの情報を取得できます。
data "aws_instance" "existing_instance" {
  instance_id = "i-015a497d474fea151" # 既存のインスタンスIDを指定します
}

# null_resourceはリソースを作成しないリソースです。
# ここではプロビジョナーを使うために使用しています。
resource "null_resource" "deploy" {

  # ファイルプロビジョナーを使って、ローカルのディレクトリをリモートにコピーします。
  provisioner "file" {
    source      = "${path.module}/text-snippest" # ローカルのディレクトリパス
    destination = "/home/ubuntu/text-snippest"  # リモートのディレクトリパス

    # SSH接続の設定
    connection {
      type        = "ssh"
      user        = "ubuntu" # SSHユーザー名
      private_key = file(ssh_private_key) # 秘密鍵を使用して認証します
      host        = data.aws_instance.existing_instance.public_ip # インスタンスのパブリックIP
    }
  }

  # リモートエグゼクションプロビジョナーを使って、リモートでコマンドを実行します。
  provisioner "remote-exec" {
    inline = [
      "mkdir -p /home/ubuntu/text-snippest",  # ディレクトリを作成
      "cd /home/ubuntu/text-snippest",       # 作成したディレクトリに移動
      "sudo chown -R ubuntu:ubuntu .",       # ディレクトリの所有権を変更
      "sudo chmod +x deploy.sh",             # スクリプトに実行権限を付与
      "./deploy.sh",                         # デプロイスクリプトを実行
      "echo 'SSH Connection Test'",
      "echo 'Hostname: $(hostname)'",
      "echo 'Current Directory: $(pwd)'",
      "ls -la /home/ubuntu/text-snippest"
    ]

    # SSH接続の設定
    connection {
      type        = "ssh"
      user        = "ubuntu" # SSHユーザー名
      private_key = file(ssh_private_key) # 秘密鍵を使用して認証します
      host        = data.aws_instance.existing_instance.public_ip # インスタンスのパブリックIP
      timeout     = "10m"
      agent       = false
    }
  }
}

# SSH秘密鍵を変数として定義します。
variable "ssh_private_key" {
  type = string
  sensitive = true # 機密情報として扱います
}

# 出力としてインスタンスのパブリックIPを表示します。
output "instance_ip" {
  value = data.aws_instance.existing_instance.public_ip
}
