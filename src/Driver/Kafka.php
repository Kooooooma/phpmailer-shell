<?php

/*
 * This file is part of the PHPMailerShell package.
 *
 * (c) Koma <komazhang@foxmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPMailerShell\Driver;

/**
 * 异步邮件发送 Kafka 驱动类
 *
 * @package PHPMailerShell\Driver
 */
class Kafka
{
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function send($payload)
    {
        $conf = new \RdKafka\Conf();
        $conf->set('bootstrap.servers', $this->config['bootstrap.servers']);
        $conf->set('message.send.max.retries', $this->config['message.send.max.retries']);
        $conf->set('client.id', $this->config['client.id']);

        //消息递达回调函数
        $conf->setDrMsgCb(function($kafka, $message) {
            //@TODO do log here
        });

        //发送错误回调函数
        $conf->setErrorCb(function($kafka, $err, $reason) {
            //@TODO do error log here
        });

        $topicConf = new \RdKafka\TopicConf();
        $topicConf->setPartitioner(RD_KAFKA_MSG_PARTITIONER_CONSISTENT);

        //0->Broker does not send any response/ack to client
        //1->Only the leader broker will need to ack the message
        //-1->broker will block until message is committed by all in sync replicas (ISRs)
        // or broker's in.sync.replicas setting before sending response
        $topicConf->set('request.required.acks', 1);

        $producer = new \RdKafka\Producer($conf);
        $producerTopic = $producer->newTopic($this->config['topic'], $topicConf);

        $producerTopic->produce(
            RD_KAFKA_PARTITION_UA,
            0,
            $payload
        );

        //-1 poll the events sync
        //0 poll the events async
        $producer->poll(0);

        return true;
    }

    public function consume($callback, $debug = false)
    {
        $conf = new \RdKafka\Conf();

        $conf->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    echo "Assign\n";
                    $kafka->assign($partitions);
                    break;
                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    echo "Revoke\n";
                    $kafka->assign(NULL);
                    break;
                default:
                    throw new \Exception($err);
                    break;
            }
        });

        //设置consumer组Id，组里的每个consumer将会均衡的消费各条记录
        $conf->set('group.id', $this->config['group.id']);
        $conf->set('bootstrap.servers', $this->config['bootstrap.servers']);

        $topicConf = new \RdKafka\TopicConf();

        //设置consumer的offset存储位置为broker，可选项为file，表示本地文件，需要指明路径
        $topicConf->set('offset.store.method', 'broker');

        //设置开始消费起点（beginning, latest）
        $topicConf->set('auto.offset.reset', $this->config['offset']);

        $conf->setDefaultTopicConf($topicConf);

        $consumer = new \RdKafka\KafkaConsumer($conf);
        $consumer->subscribe($this->config['topic']);

        while ( true ) {
            $message = $consumer->consume($this->config['timeout']);

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    if ( is_callable($callback) ) call_user_func_array($callback, array($message->payload, $debug));
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }
}