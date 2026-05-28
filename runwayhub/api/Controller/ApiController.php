<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class ApiController extends Controller
{
    /**
     * @var string
     */
    protected string $apiKey;

    /**
     * ApiController constructor
     *
     * @param Request $request
     * @param Response $response
     * @param string $apiKey
     */
    public function __construct(
        Request $request,
        Response $response,
        ?string $apiKey = null
    ) {
        parent::__construct($request, $response);
        $this->apiKey = $apiKey ?? getenv('API_KEY') ?: '';
    }

    /**
     * Validate API key
     *
     * @return bool
     */
    protected function validateApiKey(): bool
    {
        $providedKey = $this->request->getHeader('Authorization');
        
        // Check Bearer token
        if (str_starts_with($providedKey, 'Bearer ')) {
            $providedKey = substr($providedKey, 7);
        }
        
        return $providedKey === $this->apiKey;
    }

    /**
     * Handle request
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function handle(Request $request, Response $response): mixed
    {
        if ($request->getMethod() === 'OPTIONS') {
            return $this->handleCORS();
        }

        if (!$this->validateApiKey()) {
            $response->status(401);
            $response->contentType('application/json');
            $response->content(json_encode([
                'success' => false,
                'error' => true,
                'message' => 'Unauthorized: Invalid API key',
                'code' => 'UNAUTHORIZED',
            ]));
            $response->send();
            return null;
        }

        // Route to specific handler
        return $this->dispatch($request, $response);
    }

    /**
     * Handle CORS preflight
     *
     * @return Response
     */
    protected function handleCORS(): Response
    {
        $response = new Response();
        $response->status(200);
        $response->contentType('text/plain');
        $response->content('OK');
        return $response;
    }

    /**
     * Dispatch to specific endpoint handler
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    protected function dispatch(Request $request, Response $response): mixed
    {
        $path = $request->getPath();
        $method = $request->getMethod();

        // Route mapping
        $routes = [
            'airport' => [$this, 'getAirport'],
            'weather' => [$this, 'getWeather'],
            'flight' => [$this, 'getFlight'],
            'aircraft' => [$this, 'getAircraft'],
            'pilots' => [$this, 'getPilots'],
            'airlines' => [$this, 'getAirlines'],
            'routes' => [$this, 'getRoutes'],
            'bookings' => [$this, 'getBookings'],
            'pireps' => [$this, 'getPireps'],
            'statistics' => [$this, 'getStatistics'],
            'leaderboard' => [$this, 'getLeaderboard'],
            'status' => [$this, 'getStatus'],
            'health' => [$this, 'getHealth'],
            'version' => [$this, 'getVersion'],
        ];

        // Extract route name from path
        foreach ($routes as $name => $handler) {
            if (str_contains($path, $name)) {
                $action = $handler[0] ?? null;
                return call_user_func([$action, ...$handler[1] ?? []]);
            }
        }

        // No matching route
        $response->status(404);
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => false,
            'error' => true,
            'message' => 'Not Found: ' . $path,
        ]));
        $response->send();
        return null;
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function getStatus(Response $response): Response
    {
        return $response->contentType('application/json')
            ->content(json_encode([
                'success' => true,
                'data' => [
                    'status' => 'operational',
                    'version' => '1.0.0',
                    'api_version' => '1.0.0',
                ],
            ]))
            ->send();
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function getHealth(Response $response): Response
    {
        return $response->contentType('application/json')
            ->content(json_encode([
                'success' => true,
                'data' => [
                    'status' => 'healthy',
                    'uptime' => time(),
                    'database' => 'connected',
                ],
            ]))
            ->send();
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function getVersion(Response $response): Response
    {
        return $response->contentType('application/json')
            ->content(json_encode([
                'success' => true,
                'data' => [
                    'version' => '1.0.0',
                    'build' => '2026-05-27',
                ],
            ]))
            ->send();
    }
}
