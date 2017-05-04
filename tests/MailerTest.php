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

use PHPUnit\Framework\TestCase;
use PHPMailerShell\Mailer;

class MailerTest extends TestCase
{
    public function testSend()
    {
        $mailer = new Mailer();

        $mailer::$config->setDriverConfig(array(
            'bootstrap.servers' => '172.17.0.6:9092',
            'message.send.max.retries' => 3,
            'client.id' => 'TicketsMailKafka',
            'topic' => 'tickets_mail',
        ));

        $ret = $mailer->SMTPAuth('kefu.tickets@easemob.com', '5fa86E73907aB7==')
                ->setHost('smtp.exmail.qq.com', 587)
                ->setFrom('kefu.tickets@easemob.com', '工作宝')
                ->setSubject('test for phpmailerShell')
                ->addAddress('komazhang@foxmail.com', 'zhang')
                ->addReplyTo('501729495@qq.com', 'qiang')
                ->setBody('<h1>this is a h1</h1>')
//                ->addAttachment('/tmp/1.png')
//                ->addAttachment('/tmp/2.png')
                ->send(false);

        var_dump($ret);
    }

    public function testConsume()
    {
        $mailer = new Mailer();

        $mailer::$config->setDriverConfig(array(
            'bootstrap.servers' => '172.17.0.6:9092',
            'group.id' => 'TicketMailConsumer',
            'topic' => ['tickets_mail'] //注意对于消费者而言，topic是一个数组
        ));

        $mailer->consume();
    }
}