<?php

/*
 * This file is part of the PHPMailerShell package.
 *
 * (c) Koma <komazhang@foxmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPMailerShell\Sender;

use PHPMailerShell\MailBean;
use PHPMailerShell\Mailer;

abstract class ISender
{
    /*
     * 邮箱权限验证用户信息
     */
    public $auth = true;
    public $username = '';
    public $password = '';

    /*
     * 邮箱主机信息
     */
    public $host    = '';

    /*
     * 默认端口使用 587 时 TSL 开启使用tsl规范
     * 端口使用 465 时，TSL 关闭使用ssl规范
     */
    public $port    = 587;
    public $secure  = 'tsl';
    public $autoTSL = true;
    /*
    public $port    = 465;
    public $secure  = 'ssl';
    public $autoTSL = false;
     */

    /*
     * 邮件发送驱动器类
     */
    public $driver = null;

    /*
     * 发送邮件实现类
     */
    public $class = null;

    /*
     * 邮件信息
     */
    public $mailBean = null;

    /**
     * 发送邮件
     * 根据 $async 参数指定是异步发送还是同步发送
     *
     * @param \PHPMailerShell\MailBean $mailBean 邮件实体
     * @param bool $async
     *
     * @return mixed
     */
    abstract function send(\PHPMailerShell\MailBean $mailBean, $async = false);
}

class Sender extends ISender
{
    public function __construct()
    {
        $this->class = Mailer::$config->sender['class'];

        if ( isset(Mailer::$config->sender[$this->class]) ) {
            foreach ( Mailer::$config->sender[$this->class] as $config => $value ) {
                $this->{$config} = $value;
            }
        }
    }

    public function send(\PHPMailerShell\MailBean $mailBean, $async = false)
    {
        $this->mailBean = $mailBean;

        if ( $async ) {
            $driver = $this->getDriver();

            return $driver->send($mailBean->toString());
        } else {
            return $this->doSend();
        }
    }

    public function consume($debug = false)
    {
        $driver = $this->getDriver();

        $driver->consume(function($payload, $debug) {
            if ( $debug ) Mailer::log($payload);
            $payload = json_decode($payload, true);

            $this->mailBean = new MailBean($payload);
            return $this->doSend();
        }, $debug);
    }

    public function doSend()
    {
        $class  = '\PHPMailerShell\Sender\\'.$this->class;
        $sender = new $class($this);

        return $sender->send();
    }

    public function getDriver()
    {
        $driver = Mailer::$config->sender['driver'];
        $driverConfig = Mailer::$config->sender[$driver];

        $driver = '\PHPMailerShell\Driver\\'.$driver;
        $driver = new $driver($driverConfig);

        return $driver;
    }
}
