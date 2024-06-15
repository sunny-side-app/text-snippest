provider "aws" {
  region = "ap-northeast-1c" # 適切なAWSリージョンを指定してください
}

data "aws_instance" "existing_instance" {
  instance_id = "i-015a497d474fea151" # 既存のインスタンスIDを指定してください
}

resource "null_resource" "deploy" {
  provisioner "file" {
    source      = "${path.module}/text-snippest"
    destination = "/home/ubuntu/text-snippest"

    connection {
      type        = "ssh"
      user        = "ubuntu"
      private_key = var.ssh_private_key
      host        = data.aws_instance.existing_instance.public_ip
    }
  }

  provisioner "remote-exec" {
    inline = [
      "cd /home/ubuntu/text-snippest",
      "sudo chown -R ubuntu:ubuntu .",
      "sudo chmod +x deploy.sh",
      "./deploy.sh"
    ]

    connection {
      type        = "ssh"
      user        = "ubuntu"
      private_key = var.ssh_private_key
      host        = data.aws_instance.existing_instance.public_ip
    }
  }
}

variable "ssh_private_key" {
  type = string
}

output "instance_ip" {
  value = data.aws_instance.existing_instance.public_ip
}
