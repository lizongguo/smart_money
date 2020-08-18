<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $data
     * @param  array  $header
     * @return mixed
     */
    public function respond($data, $header = [])
    {
        return Response::json($data, 200, $header);
    }

    /**
     * @param $status
     * @param  array  $data
     * @param  null  $code
     * @return mixed
     */
    public function status($status, array $data, $code = null)
    {
        if ($code) {
            $this->setStatusCode($code);
        }

        $status = [
            'message' => $status,
            'status' => $this->statusCode,
        ];

        $data = array_merge($status, $data);

        return $this->respond($data);
    }

    /**
     * @param $message
     * @param  int  $code
     * @param  string  $status
     * @return mixed
     */
    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST, $status = 'error')
    {
        return $this->setStatusCode(-1)->message($message, $status);
    }

    /**
     * @param $message
     * @param  string  $status
     * @param  null  $data
     * @return mixed
     */
    public function message($message, $status = "success", $data = null)
    {
        return $this->status($status, [
            'data' => $data,
            'msg' => $message,
        ]);
    }

    /**
     * @param  string  $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!")
    {
        return $this->failed($message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param  string  $message
     * @return mixed
     */
    public function created($message = "created")
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)->message($message);
    }

    /**
     * @param  string  $message
     * @return mixed
     */
    public function deleted($message = "deleted")
    {
        return $this->setStatusCode(FoundationResponse::HTTP_NO_CONTENT)->message($message);
    }

    /**
     * @param $data
     * @param  string  $status
     * @param  string  $msg
     * @return mixed
     */
    public function success($data, $status = "success", $msg = "")
    {
        return $this->status($status, ['data' => $data, 'msg' => $msg]);
    }

    /**
     * @param  string  $message
     * @return mixed
     */
    public function notFond($message = 'Not Fond!')
    {
        return $this->failed($message, Foundationresponse::HTTP_NOT_FOUND);
    }
}
