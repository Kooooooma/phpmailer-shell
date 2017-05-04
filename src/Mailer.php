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
use PHPMailerShell\Sender\SMTPSender;

/**
 * Main class
 *
 * @author Koma <komazhang@foxmail.com>
 */
class Mailer
{
    public static $config = null;

    private $isSMTP = true;
    private $SMTPAuth = false;
    private $SMTPUsername = '';
    private $SMTPPassword = '';

    private $host = '';
    private $port = '';

    private $from = '';

    private $replayTo = array();

    private $addresses = array();

    private $cc = array();

    private $subject = '';

    private $body = '';

    private $attachments = array();

    public function __construct()
    {
        date_default_timezone_set('UTC');

        $this->initConfig();
    }

    public function isSMTP($isSMTP = true)
    {
        $this->isSMTP = $isSMTP;

        return $this;
    }

    public function SMTPAuth($username, $password)
    {
        $this->SMTPAuth = true;
        $this->SMTPUsername = $username;
        $this->SMTPPassword = $password;

        return $this;
    }

    public function setHost($host, $port)
    {
        $this->host = $host;
        $this->port = $port;

        return $this;
    }

    public function setFrom($address, $name)
    {
        $this->from = array(
            'address' => $address,
            'name' => $name
        );

        return $this;
    }

    /**
     * 添加快捷回复人
     *
     * @param $address
     * @param $name
     * @return $this
     */
    public function addReplyTo($address, $name)
    {
        $this->replayTo = array(
            'address' => $address,
            'name' => $name
        );

        return $this;
    }

    /**
     * 添加收件人
     *
     * @param $address
     * @param $name
     * @return $this
     */
    public function addAddress($address, $name)
    {
        $this->addresses[] = array(
            'address' => $address,
            'name' => $name
        );

        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function addAttachment($path)
    {
        $this->attachments[] = $path;

        return $this;
    }

    /**
     * 发送邮件
     * 根据 $nonblock 参数指定是异步发送还是同步发送
     *
     * @param bool $nonblock 设置为 true 表示异步发送
     * @return bool|string
     */
    public function send($nonblock = false)
    {
        if ( !$nonblock ) {
            if ( $this->isSMTP ) {
                $sender = new SMTPSender($this->getOptions());
            }
        } else {
            $driver = 'PHPMailerShell\Driver\\'.self::$config->driver['type'];
            $sender = new $driver($this->getOptions());
        }

        return $sender->send();
    }

    public function consume()
    {
        $driver = 'PHPMailerShell\Driver\\'.self::$config->driver['type'];
        $driver = new $driver();

        $driver->consume();
    }

    public function getOptions()
    {
        return array(
            'username'   => $this->SMTPUsername,
            'password'   => $this->SMTPPassword,
            'auth'       => $this->isSMTP ? $this->SMTPAuth : true,
            'host'       => $this->host,
            'port'       => $this->port,
            'from'       => $this->from,
            'replayTo'   => $this->replayTo,
            'address'    => $this->addresses,
            'cc'         => $this->cc,
            'subject'    => $this->subject,
            'body'       => $this->body,
            'attachment' => $this->attachments
        );
    }

    private function initConfig()
    {
        if ( ! self::$config instanceof Config ) {
            self::$config = Config::getInstance();
        }
    }
}