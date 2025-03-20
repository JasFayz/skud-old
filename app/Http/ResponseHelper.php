<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{

    public static function success($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => true
        ], $code);
    }

    public static function error($data, $message = 'Error', $code = 400)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => false
        ], $code);
    }

    public static function handle(ActionMessage $actionMessage, int $code = null)
    {
        if ($actionMessage->success) {
            return self::success($actionMessage->data, $actionMessage->message, $code ? $code : Response::HTTP_OK);
        } else {
            return self::error($actionMessage->data, $actionMessage->message, $code ? $code : Response::HTTP_BAD_REQUEST);
        }
    }
}
