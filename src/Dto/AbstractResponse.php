<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Ignore;

abstract class AbstractResponse
{
    #[Ignore]
    public int $status = 200;

    #[Ignore]
    public array $headers = [];

    /**
     * @param int $status
     * @param array $headers
     */
    public function __construct(int $status, array $headers)
    {
        $this->status = $status;
        $this->headers = $headers;
    }


}