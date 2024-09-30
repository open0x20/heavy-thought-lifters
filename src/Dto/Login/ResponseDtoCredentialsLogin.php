<?php

namespace App\Api\Login;

use App\Dto\AbstractResponse;

class ResponseDtoCredentialsLogin extends AbstractResponse
{
    public string $message;

    /**
     * @param string $message
     */
    public function __construct(string $message, int $code = 200, array $headers = [])
    {
        $this->message = $message;
        parent::__construct($code, $headers);
    }


}