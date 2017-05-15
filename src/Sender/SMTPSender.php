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

/**
 * SMTP sender class
 *
 * @author Koma <komazhang@foxmail.com>
 */
class SMTPSender
{
    public function __construct(\PHPMailerShell\Sender\Sender $sender)
    {
        $this->sender   = $sender;
        $this->mailBean = $sender->mailBean;
    }

    public function send()
    {
        $phpMailer = new \PHPMailer();

        $phpMailer->isSMTP();
        $phpMailer->SMTPDebug = 2;

        $phpMailer->Host = $this->sender->host;
        $phpMailer->Port = $this->sender->port;

        $phpMailer->SMTPAutoTLS = $this->sender->autoTSL;
        $phpMailer->SMTPSecure  = $this->sender->secure;

        $phpMailer->SMTPAuth = $this->sender->auth;
        if ( $phpMailer->SMTPAuth ) {
            $phpMailer->Username = $this->sender->username;
            $phpMailer->Password = $this->sender->password;
        }

        $phpMailer->setFrom($this->mailBean->getFrom(), $this->mailBean->getFromName());

        foreach ( $this->mailBean->getReplyTo() as $address => $name ) {
            $phpMailer->addReplyTo($address, $name);
        }

        foreach ( $this->mailBean->getTo() as $address => $name ) {
            $phpMailer->addAddress($address, $name);
        }

        $phpMailer->Subject = $this->mailBean->getSubject();

        //支持发送html内容
        $phpMailer->msgHTML($this->mailBean->getBody());

        if ( !$phpMailer->send() ) {
            return $phpMailer->ErrorInfo;
        }

        return true;
    }
}