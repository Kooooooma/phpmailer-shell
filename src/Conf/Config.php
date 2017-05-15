<?php

/*
 * This file is part of the PHPMailerShell package.
 *
 * (c) Koma <komazhang@foxmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPMailerShell\Conf;

/**
 * Config class for PHPMailerShell
 *
 * @author koma <komazhang@foxmail.com>
 */
class Config
{
    public $sender = array(
        'class'  => 'SMTPSender',
        'driver' => 'Kafka'
    );

    public $receiver = array(
        'class' => 'IMAPReceiver'
    );

    public function setReceiver($receiver)
    {
        $this->receiver['class'] = $receiver;

        return $this;
    }

    public function setReceiverConfig($options)
    {
        $this->receiver[$this->receiver['class']] = $options;

        return $this;
    }

    public function setSender($sender)
    {
        $this->sender['class'] = $sender;

        return $this;
    }

    public function setSenderConfig($options)
    {
        $sender = $this->sender['class'];
        $this->sender[$sender] = $options;

        return $this;
    }

    public function setDriver($driver)
    {
        $this->sender['driver'] = $driver;

        return $this;
    }

    public function setDriverConfig($options)
    {
        $driver = isset($this->sender['driver']) ? $this->sender['driver'] : '';
        if ( $driver == '' ) return false;

        $this->sender[$driver] = $options;
        return $this;
    }

    public static $ins = NULL;
    private function __construct()
    {
    }

    public static function getInstance()
    {
        if ( ! self::$ins instanceof Config ) {
            self::$ins = new Config();
        }

        return self::$ins;
    }
}