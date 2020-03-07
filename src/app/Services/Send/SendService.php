<?php
declare(strict_types=1);

namespace App\Services\Send;


use stdClass;

abstract class SendService implements SendInterface
{
    /** @var string */
    protected const METHOD_POST = 'POST';

    /** @var array */
    protected $headers = [];
    /** @var string */
    protected $method = '';
    /** @var string */
    protected $url = '';

    /**
     * SendService constructor.
     */
    public function __construct()
    {
        $this->header([
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @param array $header
     * @return $this
     */
    public function header(array $header): self
    {
        $this->headers = array_merge($this->headers, $header);
        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return $this
     */
    public function post(): self
    {
        return $this->method(self::METHOD_POST);
    }

    /**
     * @param string $method
     * @return $this
     */
    public function method(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return stdClass
     */
    abstract public function send(): stdClass;
}
