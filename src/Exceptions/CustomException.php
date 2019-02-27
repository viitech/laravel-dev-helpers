<?php

namespace VIITech\Helpers\Exceptions;

use Illuminate\Support\MessageBag;
use Dingo\Api\Contract\Debug\MessageBagErrors;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use VIITech\Helpers\GlobalHelpers;

class CustomException extends HttpException implements MessageBagErrors
{
    /**
     * MessageBag errors.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Create a new resource exception instance.
     *
     * @param string $message
     * @param \Illuminate\Support\MessageBag|array $errors
     * @param int $code
     */
    public function __construct($message = null, $errors = null, $code = 200)
    {
        $previous = null;
        $headers = [];

        if (is_null($errors)) {
            $this->errors = new MessageBag;
        } else {
            $this->errors = is_array($errors) ? new MessageBag($errors) : $errors;
        }

        parent::__construct($code, $message, $previous, $headers, $code);
    }

    /**
     * Get the errors message bag.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if message bag has any errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !$this->errors->isEmpty();
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param $request Request
     * @param bool $success
     * @param string $message
     * @param array $data
     * @param int $status
     * @param array $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, $success, $message, $data, $status, $error)
    {
        return GlobalHelpers::returnResponse($success, $message, $data, $error, $status, $request->headers->all());
    }
}
