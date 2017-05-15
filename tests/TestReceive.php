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

/**
 * Class TestReceive
 *
 * 测试邮件收取
 *
 * @package PHPMailerShellTest
 */
class TestReceive extends TestCase
{
    public function testReceive()
    {
        $mailer = new Mailer();
        $mailer::$config->setReceiverConfig(array(
            'host'     => 'imap.exmail.qq.com',
            'port'     => 993,
            'protocol' => 'imap',
            'autoTSL'  => true,
            'username' => 'xxx@yy.com',
            'password' => 'PASSWORD',
            'charset'  => 'UTF-8',
            'doDelete' => true,
            'asRead'   => true,
            'criteria' => 'ALL',
            'limit'    => 2
        ));

        $ret = $mailer->receive();
        var_dump($ret);
    }
}
