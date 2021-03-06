<?php
namespace Lumia\Packages\Fractal;

use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

trait HasFractal
{

    protected $fractal;

    protected $statusCode = 200;

    protected function parseIncludes($includes = [])
    {
        if ($includes) {
            app()->fractal->parseIncludes($includes);
        }
        return $this;
    }

    /**
     * Gets the current status code
     *
     * @return Int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the status code for the response
     *
     * @param Int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorForbidden($message = null)
    {
        return $this->setStatusCode(403)->respondWithError(($message ?: Lang::get('response.error.forbidden')));
    }

    /**
     * Generates a response with a 410 HTTP header and a given message
     *
     * @param mixed $message
     * @return void
     */
    public function errorExpired($message = null)
    {
        return $this->setStatusCode(410)->respondWithError(($message ?: Lang::get('response.error.expired')));
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorInternalError($message = null)
    {
        return $this->setStatusCode(500)->respondWithError(($message ?: Lang::get('response.error.internal')));
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorUnauthorized($message = null)
    {
        return $this->setStatusCode(401)->respondWithError(($message ?: Lang::get('response.error.unauthorized')));
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorWrongArgs($message = null)
    {
        return $this->setStatusCode(400)->respondWithError(($message ?: Lang::get('response.error.wrong_args')));
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorNotFound($message = null)
    {
        return $this->setStatusCode(404)->respondWithError(($message ?: Lang::get('response.error.not_found')));
    }

    public function errorUnprocessable($data)
    {
        return $this->setStatusCode(422)->respondWithError($data);
    }

    public function respondWithNoContent()
    {
        return response(null, 204);
    }

    public function respondWithSuccess($message = null)
    {
        return $this->respondWithArray([
            'success' => [
                'http_code' => $this->statusCode,
                'message' => $message
            ]
        ]);
    }

    public function respondWithComplete($status = 201)
    {
        return $this->setStatusCode($status)->respondWithArray([
            'processed' => true
        ]);
    }

    /**
     * Returns an error response
     *
     * @param String $message
     * @param String $errorCode
     * @return Array
     */
    protected function respondWithError($message = null)
    {
        if ($this->statusCode == 200) {
            trigger_error(Lang::get('response.error.200'));
        }
        
        return $this->respondWithArray([
            'error' => [
                'http_code' => $this->statusCode,
                'message' => $message
            ]
        ]);
    }

    /**
     * Respond with an item
     *
     * @param array $item
     * @param object $callback
     *            The Lang::getformer to use
     * @return array
     */
    protected function respondWithItem($item, $callback, $meta = [])
    {
        if (app('request')->includes) {
            $this->parseIncludes(app('request')->includes);
        }
        
        $resource = new Item($item, $callback);
        
        $meta = array_merge([
            'lang' => app()->getLocale()
        ], $meta);
        
        $resource->setMeta($meta);
        
        $rootScope = app()->fractal->createData($resource);
        
        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Respond with a collection
     *
     * @param array $paginator
     * @param object $callback
     *            The Lang::getformer to use
     * @return array
     */
    protected function respondWithCollection($paginator, $callback, $meta = [])
    {
        if (app('request')->includes) {
            $this->parseIncludes(app('request')->includes);
        }
        
        if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $collection = $paginator->getCollection();
        } else {
            $collection = $paginator;
        }
        
        $resource = new Collection($collection, $callback);
        
        $meta = array_merge([
            'lang' => app()->getLocale()
        ], $meta);
        
        $resource->setMeta($meta);
        
        if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        }
        
        $rootScope = app()->fractal->createData($resource);
        
        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Builds a response array
     *
     * @param array $array
     *            The array of data
     * @param array $headers
     *            Any headers to attach to the response
     * @return array
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response($array, $this->statusCode)->withHeaders($headers);
    }
}
