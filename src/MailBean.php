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

/**
 * MailBean Class
 *
 * @package PHPMailerShell
 */
class MailBean
{
    /*
     * String
     */
    public $from;
    public $fromName;
    public $subject;
    public $body;

    /*
     * 邮件收取到的时间 Y-m-d H:i:s
     */
    public $date;

    /*
     * String
     * 每封邮件的唯一标识
     */
    public $mailId;

    /*
     * Array
     * ['address' => 'name', ...]
     */
    public $cc;
    public $bcc;
    public $to;
    public $replyTo;

    /*
     * Array
     */
    public $attachments;

    /**
     * Array 当前邮件发送时的 sender 配置信息
     */
    public $senderConfig;

    public function __construct(Array $mailInfo = array())
    {
        if (!empty($mailInfo)) {
            $rf = new \ReflectionObject($this);
            foreach ($mailInfo as $propName => $value) {
                $property = $rf->getProperty($propName);
                $property->setValue($this, $value);
            }
        }
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function getFromName()
    {
        return $this->fromName;
    }

    public function setFromName($name)
    {
        $this->fromName = $name;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getMailId()
    {
        return $this->mailId;
    }

    public function setMailId($mailId)
    {
        $this->mailId = $mailId;
        return $this;
    }

    public function getCC()
    {
        return $this->cc;
    }

    public function setCC($cc)
    {
        if (is_string($cc)) {
            $this->cc[$cc] = '';
        } else if (is_array($cc)) {
            foreach ($cc as $key => $val) {
                if (is_numeric($key)) {
                    $this->cc[$val] = '';
                } else if (is_string($key)) {
                    $this->cc[$key] = $val;
                }
            }
        }

        return $this;
    }

    public function getBCC()
    {
        return $this->bcc;
    }

    public function setBCC($bcc)
    {
        if (is_string($bcc)) {
            $this->bcc[$bcc] = '';
        } else if (is_array($bcc)) {
            foreach ($bcc as $key => $val) {
                if (is_numeric($key)) {
                    $this->bcc[$val] = '';
                } else if (is_string($key)) {
                    $this->bcc[$key] = $val;
                }
            }
        }

        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        if (is_string($to)) {
            $this->to[$to] = '';
        } else if (is_array($to)) {
            foreach ($to as $key => $val) {
                if (is_numeric($key)) {
                    $this->to[$val] = '';
                } else if (is_string($key)) {
                    $this->to[$key] = $val;
                }
            }
        }

        return $this;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function setReplyTo($replyTo)
    {
        if (is_string($replyTo)) {
            $this->replyTo[$replyTo] = '';
        } else if (is_array($replyTo)) {
            foreach ($replyTo as $key => $val) {
                if (is_numeric($key)) {
                    $this->replyTo[$val] = '';
                } else if (is_string($key)) {
                    $this->replyTo[$key] = $val;
                }
            }
        }

        return $this;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    public function getSenderConfig()
    {
        return $this->senderConfig;
    }

    public function setSenderConfig($config)
    {
        $this->senderConfig = $config;
        return $this;
    }

    public function toString()
    {
        $result = array();

        $rf = new \ReflectionObject($this);
        foreach ( $rf->getProperties() as $property ) {
            $name = $property->getName();
            $result[$name] = $this->{$name};
        }

        return json_encode($result);
    }
}