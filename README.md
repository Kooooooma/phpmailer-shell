# phpmailer-shell
phpmailer-shell是一个邮件发送外壳程序，支持邮件发送（同步＆异步）、邮件收取，通过简单的几行代码，使得应用嵌入邮件系统更加的方便。

# 依赖
* 依赖 PHPMailer 实现邮件发送
* 依赖 php-imap 实现邮件收取
* 邮件异步发送依赖驱动器类实现，目前已实现的是基于Kafka。也可编写自己的驱动器类实现异步发送。

# 安装使用
```composer
"require": {
    "phpmailer-shell/phpmailer-shell": "~1.0"
}
```

# 测试运行
```shell
cd /path/phpmailer-shell
phpunit -c tests/phpunit.xml.dist
```
# 使用示例
请参考测试用例，Kafka异步消费请参考 tests/testConsumer.php

# 反馈
请提 issue
