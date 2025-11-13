<?php

namespace App\Services;

use App\Models\ServicePackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ServicePackageResponse
{
    /**
     * Return a successful response with ServicePackage data
     *
     * @param ServicePackage|Collection|array $data
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success($data = null, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = self::formatData($data);
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error response
     *
     * @param string $message
     * @param array|null $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message, ?array $errors = null, int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a successful response for a single ServicePackage
     *
     * @param ServicePackage $package
     * @param string|null $message
     * @return JsonResponse
     */
    public static function package(ServicePackage $package, ?string $message = null): JsonResponse
    {
        return self::success($package, $message);
    }

    /**
     * Return a successful response for a collection of ServicePackages
     *
     * @param Collection|array $packages
     * @param string|null $message
     * @return JsonResponse
     */
    public static function packages($packages, ?string $message = null): JsonResponse
    {
        return self::success($packages, $message);
    }

    /**
     * Return a successful creation response
     *
     * @param ServicePackage $package
     * @param string|null $message
     * @return JsonResponse
     */
    public static function created(ServicePackage $package, ?string $message = null): JsonResponse
    {
        return self::success($package, $message ?: 'Service package created successfully', 201);
    }

    /**
     * Return a successful update response
     *
     * @param ServicePackage $package
     * @param string|null $message
     * @return JsonResponse
     */
    public static function updated(ServicePackage $package, ?string $message = null): JsonResponse
    {
        return self::success($package, $message ?: 'Service package updated successfully');
    }

    /**
     * Return a successful deletion response
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public static function deleted(?string $message = null): JsonResponse
    {
        return self::success(null, $message ?: 'Service package deleted successfully');
    }

    /**
     * Return a not found response
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public static function notFound(?string $message = null): JsonResponse
    {
        return self::error($message ?: 'Service package not found', null, 404);
    }

    /**
     * Return a validation error response
     *
     * @param array $errors
     * @param string|null $message
     * @return JsonResponse
     */
    public static function validationError(array $errors, ?string $message = null): JsonResponse
    {
        return self::error($message ?: 'Validation failed', $errors, 422);
    }

    /**
     * Format ServicePackage data for response
     *
     * @param ServicePackage|Collection|array $data
     * @return array
     */
    private static function formatData($data): array
    {
        if ($data instanceof ServicePackage) {
            return self::formatPackage($data);
        }

        if ($data instanceof Collection || is_array($data)) {
            return array_map(function ($package) {
                return $package instanceof ServicePackage
                    ? self::formatPackage($package)
                    : $package;
            }, $data instanceof Collection ? $data->all() : $data);
        }

        return $data;
    }

    /**
     * Format a single ServicePackage for response
     *
     * @param ServicePackage $package
     * @return array
     */
    private static function formatPackage(ServicePackage $package): array
    {
        return [
            'id' => $package->id,
            'name' => $package->name,
            'description' => $package->description,
            'price' => $package->price,
            'duration' => $package->duration,
            'duration_unit' => $package->duration_unit ?? null,
            'discount' => $package->discount ?? null,
            'created_at' => $package->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $package->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
