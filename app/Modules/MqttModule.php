<?php

namespace App\Modules;

use PhpMqtt\Client\MqttClient;

class MqttModule
{
    public MQTTClient $mqtt;

    public function __construct(string $host, string $port)
    {
        try {
            $this->mqtt = new MQTTClient($host, $port, 'server');
        } catch (\Throwable $throwable) {
            throw new \RuntimeException("Не удалось инициализировать к серверу mqtt -> $throwable", 0, $throwable);
        }
    }

    public function connect(): self
    {
        $this->mqtt->connect();

        return $this;
    }

    public function sendMessage(string $topic, string $message): self
    {
        $this->mqtt->publish($topic, $message);

        return $this;
    }

    public function disconnect(): void
    {
        $this->mqtt->disconnect();
    }
}
