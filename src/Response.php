<?php

namespace Lixus\ValueFirst;

use DateTime;
use Exception;
use Illuminate\Support\Arr;

class Response
{
    /* @var string $guid */
    private $guid;

    /* @var DateTime $submitDate*/
    private $submitDate;

    /* @var array $errors */
    private $errors;

    /* @var int @status */
    private $httpStatus;

    /**
     * Response constructor.
     * @param int $status
     * @param array $response
     * @throws Exception
     */
    public function __construct(int $status, array $response)
    {
        $this->httpStatus = $status;

        $this->guid = Arr::get($response,"MESSAGEACK.GUID.GUID");
        $this->submitDate = new DateTime(Arr::get($response, "MESSAGEACK.GUID.SUBMITDATE"));

        $errors = array_merge(
            Arr::get($response, "MESSAGEACK.Err", []),
            Arr::get($response, "MESSAGEACK.GUID.ERROR", [])
        );

        $this->errors = $errors;
    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @return DateTime
     */
    public function getSubmitDate(): DateTime
    {
        return $this->submitDate;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isError():bool
    {
        return !$this->guid || (is_array($this->errors) && count($this->errors) > 0);
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

}