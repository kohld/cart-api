<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
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

    public function __construct(private readonly Connection $connection)
    {
    }

    #[Route('', methods: ['GET'])]
    public function health(): JsonResponse
    {
        try {
            $this->connection->executeQuery('SELECT 1');
            $databaseStatus = self::OK;
        } catch (DBALException $e) {
            $databaseStatus = self::UNAVAILABLE;
            error_log(sprintf(
                'Database connection failed: %s',
                $e->getMessage()
            ));
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
