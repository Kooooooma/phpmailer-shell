<?php

/*
 * This file is part of the PHPMailerShell package.
 *
 * (c) Koma <komazhang@foxmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPMailerShellTest;

use PHPMailerShell\MailBean;
use PHPUnit\Framework\TestCase;
use PHPMailerShell\Mailer;

/**
 * Class TestAsyncSend
 *
 * 测试异步发送邮件
 *
 * @package PHPMailerShellTest
 */
class TestAsyncSend extends TestCase
{
    public function testASyncSend()
    {
        $mailer = new Mailer();

        $mailer::$config->setDriverConfig(array(
            'bootstrap.servers' => '192.168.1.6:9092',
            'message.send.max.retries' => 3,
            'client.id' => 'TicketsMailKafka',
            'topic' => 'tickets-email',
        ));

        $mailBean = new MailBean();
        $mailBean->setFrom('ticket.support@easemob.com')
            ->setSubject('test for phpmailerShell send email async------1')
            ->setBody('<h1>this is a h1</h1>')
            ->setTo(array('komazhang@foxmail.com', 'zhangqiang@easemob.com'))
            ->setReplyTo('501729495@qq.com');

        $mailBean->setSenderConfig(array(
            'auth' => true,
            'username' => 'ticket.support@easemob.com',
            'password' => 'password',
            'host' => 'smtp.exmail.qq.com',
            'port' => 587,
            'secure' => 'tsl',
            'autoTSL' => true
        ));

        $ret = $mailer->send($mailBean, true);

        var_dump($ret);
    }
}
