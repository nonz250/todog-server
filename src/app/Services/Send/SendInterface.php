<?php
declare(strict_types=1);

namespace App\Services\Send;


interface SendInterface
{
    /**
     * @param array $header
     * @return SendService
     */
    public function header(array $header): SendService;

    /**
     * @return SendService
     */
    public function post(): SendService;

    /**
     * @param string $method
     * @return SendService
     */
    public function method(string $method): SendService;

    /**
     * @param string $url
     * @return SendService
     */
    public function url(string $url): SendService;
}
