<?php

namespace App\Http\Library;

use Symfony\Component\HttpFoundation\Response;

class HttpResponse
{
  public static function respondWithSuccess($data, $message = null)
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => $data,
    ]);
  }

  public static function respondError($message = null, $code = 401)
  {
    return response()->json([
      'success' => false,
      'errors' => $message,
    ], $code);
  }

  public static function respondUnAuthenticated($message = "Unauthenticated")
  {
    return response()->json([
      'success' => false,
      'message' => $message
    ], Response::HTTP_UNAUTHORIZED);
  }

  public static function respondNotFound($data, $message = "Not found")
  {
    return response()->json([
      'success' => false,
      'message' => $message
    ], Response::HTTP_NOT_FOUND);
  }
}
