<?php

/*
 * This file is part of the PHPMailerShell package.
 *
 * (c) Koma <komazhang@foxmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPMailerShell\Receiver;

use PHPMailerShell\Mailer;

abstract class IReceiver
{
    /*
     * 登录邮箱用户信息
     */
    public $username = '';
    public $password = '';

    /*
     * 数据传输编码设置
     */
    public $charset = 'UTF-8';

    /*
     * 邮箱主机信息
     * 同时需要指定收取协议，针对IMAP或者POP3，它们对应的端口号不同
     * IMAP 993
     * POP3 995
     * 默认采用IMAP协议
     */
    public $host     = '';
    public $port     = 993;
    public $protocol = 'imap';

    /*
     * 默认都启用SSL
     */
    public $autoTSL = true;

    /*
     * 附件存放地址，稍后会用来解析附件
     */
    public $attachmentDir = '/tmp';

    /*
     * 针对收取到的邮件在解析完成之后是否进行删除
     */
    public $doDelete = false;

    /*
     * 针对收取到的邮件在解析完成之后是否标记为已读
     */
    public $asRead = false;

    /*
     * 收取规则
     * @See http://php.net/manual/zh/function.imap-search.php
     */
    public $criteria = 'ALL';

    /*
     * 每次收取的信件数
     */
    public $limit = 10;

    /*
     * 收取邮件实现类
     */
    public $class = '';

    //邮件收取方法
    abstract function receive();
}

/**
 * receiver abstract class
 *
 * @author Koma <komazhang@foxmail.com>
 */
class Receiver extends IReceiver
{
    public function __construct()
    {
        $this->class = Mailer::$config->receiver['class'];

        foreach ( Mailer::$config->receiver[$this->class] as $config => $value ) {
            $this->{$config} = $value;
        }
    }

    //邮件收取方法
    public function receive()
    {
        $class = '\PHPMailerShell\Receiver\\'.$this->class;
        $receiver = new $class($this);

        return $receiver->receive();
    }
}