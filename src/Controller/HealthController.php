<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/health')]
final class HealthController extends AbstractController
{
    private const string OK = 'ok';
    private const string UNAVAILABLE = 'unavailable';
    private const string ERROR = 'error';

    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Checks the database connection and returns the overall health status.
     * Logs database connection failures with structured error information.
     *
     * @return JsonResponse JSON response containing health status and database connectivity
     */
    #[Route('', methods: ['GET'])]
    public function health(): JsonResponse
    {
        try {
            $this->connection->executeQuery('SELECT 1');
            $databaseStatus = self::OK;
        } catch (DBALException $e) {
            $databaseStatus = self::UNAVAILABLE;
            $this->logger->error(
                'Database connection failed during health check',
                [
                    'exception' => $e,
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ]
            );
        }

        $healthy = self::OK === $databaseStatus;

        $response = $this->json(
            [
                'status' => $healthy ? self::OK : self::ERROR,
                'database' => $databaseStatus,
            ],
            $healthy ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE,
        );

        $response->headers->set('Cache-Control', 'public, max-age=60');

        return $response;
    }
}
