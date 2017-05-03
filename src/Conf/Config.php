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
    public $driver = array(
        'type'   => 'Kafka',
        'sender' => 'SMTP'
    );

    public function setType($type)
    {
        $this->driver['type'] = $type;

        return $this;
    }

    public function setSender($sender)
    {
        $this->driver['sender'] = $sender;

        return $this;
    }

    public function setDriverConfig($options)
    {
        $this->driver[$this->driver['type']] = $options;

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