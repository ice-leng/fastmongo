<?php


namespace Reasno\Fastmongo;


use Hyperf\Contract\ConfigInterface;

class DomainConfig extends \Hyperf\GoTask\Config\DomainConfig
{

    private $uri;
    private $readWriteTimeout;
    private $connectTimeout;
    /**
     * @var string
     */
    private $addr;

    public function __construct(ConfigInterface $config)
    {
        parent::__construct($config);
        $this->addr = \Hyperf\GoTask\ConfigProvider::address();
        $this->uri = $config->get('mongodb.uri', "mongodb://127.0.0.1:27017");
        $this->readWriteTimeout = $config->get('mongodb.read_write_timeout', '60s');
        $this->connectTimeout = $config->get('mongodb.connect_timeout', '3s');
    }

    public function getExecutable(): string
    {
        if ($this->isMac()) {
            return BASE_PATH . '/vendor/bin/mongo-proxy-darwin-amd64';
        }
        return BASE_PATH . '/vendor/bin/mongo-proxy-linux-amd64';
    }

    public function getArgs(): array
    {
        $args = parent::getArgs();
        return array_merge($args, [
           '-mongodb-uri',
            $this->uri,
            '-mongodb-connect-timeout',
            $this->connectTimeout,
            '-mongodb-read-write-timeout',
            $this->readWriteTimeout,
        ]);
    }

    public function getAddress(): string
    {
        $addr = parent::getAddress();
        return !empty($addr) ? $addr : $this->addr;
    }

    public function isEnabled(): bool
    {
        return true;
    }

    private function isMac() {
        return in_array(PHP_OS, [
            'Darwin',
        ]);
    }
}
