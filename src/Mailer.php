<?php

/*
 * This file is part of the PHPMailerShell package.
 *
 * (c) Koma <komazhang@foxmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPMailerShell;

use PHPMailerShell\Conf\Config;
use PHPMailerShell\Receiver\Receiver;
use PHPMailerShell\Sender\Sender;

/**
 * Main class
 *
 * @author Koma <komazhang@foxmail.com>
 */
class Mailer
{
    public static $config = null;

    public function __construct()
    {
        date_default_timezone_set('UTC');

        $this->initConfig();
    }

    /**
     * 发送邮件
     * 根据 $async 参数指定是异步发送还是同步发送
     *
     * @param \PHPMailerShell\MailBean $mailBean 邮件实体
     * @param bool $async
     *
     * @return mixed
     */
    public function send(\PHPMailerShell\MailBean $mailBean, $async = false)
    {
        $sender = new Sender();

        return $sender->send($mailBean, $async);
    }

    public function consume($debug = false)
    {
        $sender = new Sender();

        return $sender->consume($debug);
    }

    public function receive()
    {
        $receiver = new Receiver();

        return $receiver->receive();
    }

    public static function log($message)
    {
        error_log('phpmailer-shell: '.$message);
    }

    private function initConfig()
    {
        if ( ! self::$config instanceof Config ) {
            self::$config = Config::getInstance();
        }
    }
}