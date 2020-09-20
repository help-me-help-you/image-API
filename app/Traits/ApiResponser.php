<?php

namespace App\Traits;

trait ApiResponser
{
	private function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json([
			'status' => 'Error',
			'message' => $message,
			'code' => $code
		], $code);
	}
}
