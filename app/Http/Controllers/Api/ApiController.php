<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Logs exception and returns a serializable JSON exception
     *
     * @param Exception $exception
     * @param string $message
     * 
     * @return JsonException
     */
    protected function handleException(Exception $exception, string $message = 'Unknown error occurred.')
    {
        /*
         * Log exception message
         */
        Log::error($exception->getMessage(), ['file' => $exception->getFile()]);
            
        return new JsonException(__($message));
    }
}
