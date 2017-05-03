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
    private $options = null;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function send()
    {
        $phpMailer = new \PHPMailer();

        $phpMailer->isSMTP();
        $phpMailer->SMTPDebug = 2;

        $phpMailer->Host = $this->options['host'];
        $phpMailer->Port = $this->options['port'];

        $phpMailer->SMTPAuth = $this->options['auth'];
        $phpMailer->Username = $this->options['username'];
        $phpMailer->Password = $this->options['username'];

        $phpMailer->setFrom($this->options['from']['address'], $this->options['from']['name']);

        $phpMailer->addReplyTo($this->options['replayTo']['address'], $this->options['replayTo']['name']);

        foreach ( $this->options['address'] as $item) {
            $phpMailer->addAddress($item['address'], $item['name']);
        }

        $phpMailer->Subject = $this->options['subject'];

        $phpMailer->msgHTML($this->options['body']);

        foreach ( $this->options['attachment'] as $item ) {
            $phpMailer->addAttachment($item);
        }

        if ( !$phpMailer->send() ) {
            return $phpMailer->ErrorInfo;
        }

        return true;
    }
}