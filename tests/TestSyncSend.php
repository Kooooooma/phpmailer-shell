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
 * Class TestSyncSend
 *
 * 测试同步发送邮件
 *
 * @package PHPMailerShellTest
 */
class TestSyncSend extends TestCase
{
    public function testSyncSend()
    {
        //必须要设置时区
        date_default_timezone_set('Etc/UTC');

        $mailer = new Mailer();

        $mailer::$config->setSenderConfig(array(
            'auth' => true,
            'username' => 'xxx@yy.com',
            'password' => 'PASSWORD',
            'host' => 'smtp.exmail.qq.com',
            'port' => 587,
            'secure' => 'tsl',
            'autoTSL' => true
        ));

        $mailBean = new MailBean();
        $mailBean->setFrom('ticket.support@easemob.com')
            ->setSubject('test for phpmailerShell send email sync')
            ->setBody('<h1>this is a h1</h1>')
            ->setTo(array('komazhang@foxmail.com', 'zhangqiang@easemob.com'))
            ->setReplyTo('501729495@qq.com');

        $ret = $mailer->send($mailBean);

        var_dump($ret);
    }
}
