<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

/**
 * A standardized, fluent class for building JSON responses for AJAX requests.
 *
 * This class ensures all API/AJAX responses follow a consistent structure:
 * {
 * "success": (bool),
 * "message": (string|null),
 * "data": (mixed|null),
 * "errors": (object|array|null)
 * }
 */
class AjaxResponse
{
    protected bool $success = true;
    protected ?string $message = null;
    protected mixed $data = null;
    protected mixed $errors = null;
    protected int $httpStatusCode = 200;

    /**
     * Protected constructor to force use of static factory methods.
     */
    protected function __construct() {}

    // --- FACTORY METHODS ---

    /**
     * Create a new successful response.
     *
     * @param mixed|null $data
     * @param string|null $message
     * @return self
     */
    public static function success(mixed $data = null, ?string $message = null): self
    {
        $instance = new self();
        $instance->success = true;
        $instance->httpStatusCode = 200;
        $instance->data = $data;
        $instance->message = $message;
        return $instance;
    }

    /**
     * Create a new error response.
     *
     * @param string|null $message
     * @param int $httpStatusCode
     * @param mixed|null $errors
     * @return self
     */
    public static function error(?string $message = null, int $httpStatusCode = 400, mixed $errors = null): self
    {
        $instance = new self();
        $instance->success = false;
        $instance->httpStatusCode = $httpStatusCode;
        $instance->message = $message;
        $instance->errors = $errors;
        return $instance;
    }

    /**
     * Create a new 'Validation Failed' (422) error response.
     *
     * @param mixed $errors
     * @param string $message
     * @return self
     */
    public static function validation(mixed $errors, string $message = 'Validation failed.'): self
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Create a new 'Not Found' (404) error response.
     *
     * @param string $message
     * @return self
     */
    public static function notFound(string $message = 'Resource not found.'): self
    {
        return self::error($message, 404);
    }

    /**
     * Create a new 'Server Error' (500) error response.
     *
     * @param string $message
     * @return self
     */
    public static function serverError(string $message = 'An internal server error occurred.'): self
    {
        return self::error($message, 500);
    }

    // --- FLUENT METHODS ---

    /**
     * Set the 'data' payload for the response.
     *
     * @param mixed $data
     * @return self
     */
    public function withData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set the 'message' for the response.
     *
     * @param string $message
     * @return self
     */
    public function withMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the 'errors' for the response.
     *
     * @param mixed $errors
     * @return self
     */
    public function withErrors(mixed $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Set the HTTP status code for the response.
     *
     * @param int $httpStatusCode
     * @return self
     */
    public function withStatusCode(int $httpStatusCode): self
    {
        $this->httpStatusCode = $httpStatusCode;
        return $this;
    }

    // --- TERMINATOR METHODS ---

    /**
     * Build the final response array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
            'errors'  => $this->errors,
        ];
    }

    /**
     * Build and return the final JsonResponse object.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(): JsonResponse
    {
        return response()->json($this->toArray(), $this->httpStatusCode);
    }
}
