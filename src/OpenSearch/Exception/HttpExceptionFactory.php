<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

use OpenSearch\Serializers\SmartSerializer;

/**
 * Factory for creating HTTP exceptions.
 */
class HttpExceptionFactory
{
    public static function create(
        int $statusCode,
        string|array|null $data,
        array $headers = [],
        int $code = 0,
        ?\Throwable $previous = null
    ): HttpExceptionInterface {

        $errorMessage = ErrorMessageExtractor::extractErrorMessage($data);

        return match ($statusCode) {
            400 => self::createBadRequestException($errorMessage, $previous, $code, $headers),
            401 => new UnauthorizedHttpException($errorMessage, $headers, $code, $previous),
            403 => new ForbiddenHttpException($errorMessage, $headers, $code, $previous),
            404 => new NotFoundHttpException($errorMessage, $headers, $code, $previous),
            406 => new NotAcceptableHttpException($errorMessage, $headers, $code, $previous),
            409 => new ConflictHttpException($errorMessage, $headers, $code, $previous),
            429 => new TooManyRequestsHttpException($errorMessage, $headers, $code, $previous),
            500 => self::createInternalServerErrorException($errorMessage, $previous, $code, $headers),
            503 => new ServiceUnavailableHttpException($errorMessage, $headers, $code, $previous),
            default => new HttpException($statusCode, $errorMessage, $headers, $code, $previous),
        };
    }

    private static function createBadRequestException(
        string $message = '',
        ?\Throwable $previous = null,
        int $code = 0,
        array $headers = []
    ): HttpExceptionInterface {
        if (str_contains($message, 'script_lang not supported')) {
            return new ScriptLangNotSupportedException($message);
        }
        return new BadRequestHttpException($message, $headers, $code, $previous);
    }

    /**
     * Create an InternalServerErrorHttpException from the given parameters.
     */
    private static function createInternalServerErrorException(
        string $message = '',
        ?\Throwable $previous = null,
        int $code = 0,
        array $headers = []
    ): HttpExceptionInterface {
        if (str_contains($message, "RoutingMissingException")) {
            return new RoutingMissingException($message);
        }
        if (preg_match('/ActionRequestValidationException.+ no documents to get/', $message) === 1) {
            return new NoDocumentsToGetException($message);
        }
        if (str_contains($message, 'NoShardAvailableActionException')) {
            return new NoShardAvailableException($message);
        }
        return new InternalServerErrorHttpException($message, $headers, $code, $previous);
    }

}
