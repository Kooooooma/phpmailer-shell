<?php

$baseDir = dirname(__DIR__);
require $baseDir.'/vendor/autoload.php';

//必须要设置时区
date_default_timezone_set('Etc/UTC');

$mailer = new \PHPMailerShell\Mailer();

$mailer::$config->setDriverConfig(array(
    'bootstrap.servers' => '172.17.0.6:9092',
    'group.id' => 'TicketMailConsumer',
    'topic'    => ['tickets-email'], //注意对于消费者而言，topic是一个数组,
    'offset'   => 'latest', //latest, beginning
    'timeout'  => 12*1000
));

$mailer::$config->setSenderConfig(array(
    'auth' => true,
    'username' => 'ticket.support@easemob.com',
    'password' => 'password',
    'host' => 'smtp.exmail.qq.com',
    'port' => 587,
    'secure' => 'tsl',
    'autoTSL' => true
));

try {
    $mailer->consume();
} catch (\Exception $e) {
    var_dump("----- Error ---------");
    var_dump($e->getMessage());
}
