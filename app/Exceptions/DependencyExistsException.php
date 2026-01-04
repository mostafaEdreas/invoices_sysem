<?php

namespace App\Exceptions;

use Exception;

class DependencyExistsException extends Exception
{
    public function __construct($message = "Cannot delete resource because it has existing dependencies.", $code = 400) {
        parent::__construct($message, $code);
    }

    public function render($request)
    {
        // If it's an API request, return JSON
        if ($request->expectsJson() || $request->is('api/*') || $request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => $this->getMessage()], 400);
        }

        // If it's a browser request, redirect back with session error
        return back()->withErrors(['product_error' => $this->getMessage()]);
    }
}
