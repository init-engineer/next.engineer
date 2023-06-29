<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class GeneralException extends Exception
{
    public $message;

    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        // All instances of GeneralException redirect back with a flash message to show a bootstrap alert-error.
        return redirect()
            ->back()
            ->withInput()
            ->withFlashDanger($this->message);
    }
}
