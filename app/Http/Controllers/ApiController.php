<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var string
     */
    protected $message;


    public $user               = [];
    public $request            = [];
    public $response           = [];

    /**
     * ApiController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->response     = ['status'=> false, 'message' => ''];
        $this->request      = $request->all();
        $this->user         = $request->user('api');
    }

    /**
     * @param string $message
     *
     * @param string $data
     * @return json
     */
    public function responseWithError($message, $data = '')
    {
        return $this->response([
            'status'        => false,
            'code'          => 422, // $this->getStatusCode(), //OR 200
            'message'       => $message,
            'data'          => null,
            'errors'        => $data,
        ]);
    }

    /**
     * @param $data
     * @param array $headers
     *
     * @return json
     */
    public function response($data, $headers = [])
    {

        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode   = $statusCode;

        return $this;
    }

    /**
     * @param array $responseArray
     * @return json
     */
    public function responseSuccess(array $responseArray)
    {
        $data =
            [
                'status'        => true,
                'code'          => $this->getStatusCode(),
            ]
            + $responseArray +
            [
                'errors' => []
            ];

        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->response($data);
    }

    /**
     * @param $data
     *
     * @return json
     */
    public function responseSuccessWithOnlyData($data)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->response(['data' => $data]);
    }

    /**
     * @param $data
     * @return json
     */
    public function responseForbidden($data)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN)->responseWithError(trans('api.FORBIDDEN'), $data);
    }

    /**
     * @param $data
     * @return json
     */
    public function responseUnauthorized($data)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)->responseWithError(trans('api.UNAUTHORIZED'), $data);
    }

    /**
     * @param $message
     *
     * @return json
     */
    public function responseMethodNotAllowed()
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_METHOD_NOT_ALLOWED)->responseWithError(trans('api.METHOD_NOT_ALLOWED'));
    }

    /**
     *
     * @param $errors
     * @return json
     */
    public function responseValidationError($errors)
    {

        //        422 removed to solve android exception as developer request

        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->responseWithError(trans('api.INPUT_INVALID'), $errors); //$errors->all()
    }

    /**
     * @param $data
     * @return json
     */
    public function responseNotFound($data)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->responseWithError(trans('api.RESPONSE_NOT_FOUND'), $data);
    }

    /**
     * @return json
     */
    public function httpNotFound()
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->responseWithError(trans('api.URL_NOT_FOUND'));
    }

    /**
     *
     * @param $message
     * @param array $errors
     * @return json
     */
    public function responseError($message, $errors = [])
    {
        //        422 removed to solve android exception as developer request

        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->responseWithError($message, $errors);
    }
    /**
     * @param $message
     *
     * @return json
     */
    public function responseInternalError($message)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)->responseWithError(trans('api.INTERNAL_SERVER_ERROR'), [$message]);
    }

}
