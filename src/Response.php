<?php

namespace Framework;

class Response
{
    public int $responseCode = 200;

    public string $body;

    /** @var string[] */
    public array $headers = [];

    /**
     * @param string $body
     * @param int $responseCode
     * @param string[] $headers
     */
    public function __construct(string $body = "", int $responseCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->responseCode = $responseCode;
        $this->headers = $headers;
    }

    /**
     * Send the response to the client.
     */
    public function echo(): void
    {
        foreach ($this->headers as $header) {
            header($header);
        }
        http_response_code($this->responseCode);
        echo $this->body;
    }
}
