<?php


namespace App\Infrastructure;


class Response extends \Illuminate\Support\Facades\Response
{
    public static function error($message, $status = 400, $headers = [])
    {
        if (request()->acceptsJson()) {
            return static::json(['message' => $message], $status, $headers);
        }

        return static::make($message, $status, $headers);
    }
}
