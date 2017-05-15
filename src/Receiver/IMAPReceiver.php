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

use PhpImap\Exception;
use PHPMailerShell\MailBean;

/**
 * IMAP receiver class
 *
 * @author Koma <komazhang@foxmail.com>
 */
class IMAPReceiver
{
    public function __construct(\PHPMailerShell\Receiver\Receiver $receiver)
    {
        $this->receiver = $receiver;
        $this->imapPath = '{'.$this->receiver->host.':' .$this->receiver->port
                        . '/'.$this->receiver->protocol
                        . ($this->receiver->autoTSL ? '/ssl' : '/notls')
                        . '}INBOX';
    }

    public function receive()
    {
        try {
            $ret = array();

            $mailBox = new \PhpImap\Mailbox(
                $this->imapPath,
                $this->receiver->username,
                $this->receiver->password,
                $this->receiver->attachmentDir,
                $this->receiver->charset
            );
            $mailIds = $mailBox->searchMailbox($this->receiver->criteria);

            $idx = 0;
            while ( $this->receiver->limit > $idx ) {
                $mail = $mailBox->getMail($mailIds[$idx]);
                if ( $mail->subject == null ) break;

                $mailBean = new MailBean();
                $mailBean->setMailId($mail->id)
                    ->setFrom($mail->fromAddress)
                    ->setFromName($mail->fromName)
                    ->setDate($mail->date)
                    ->setCC($mail->cc)
                    ->setBCC($mail->bcc)
                    ->setSubject($mail->subject)
                    ->setBody(empty($mail->textPlain) ? $mail->textHtml : $mail->textPlain)
                    ->setTo($mail->to)
                    ->setReplyTo($mail->replyTo);

                if ( $this->receiver->asRead ) {
                    $mailBox->markMailAsRead($mail->id);
                }

                if ( $this->receiver->doDelete ) {
                    $mailBox->deleteMail($mail->id);
                }

                $ret[] = $mailBean;
                $idx++;
            }

            return $ret;
        } catch (Exception $e) {
            return false;
        }
    }
}