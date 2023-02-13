<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;

class InvalidSQLQueryException extends Exception
{

    public function render(Request $request): Response
    {
        $status = 500;
        $error = "Invalid SQL query, either arguments are missing or typing is wrong.";
        $message = $this->getMessage();
        return response(["error" => $error, "message" => $message], $status);
    }
}