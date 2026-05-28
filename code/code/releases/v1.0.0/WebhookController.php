<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Database;
use RunwayHub\Core\Response;
use Ramsey\Uuid\Uuid;

/**
 * Webhook Controller
 * 
 * Handles external integrations:
 * - ACARS webhook
 * - OTA (AeroTools) integration
 * - Email notifications (SMTP)
 */
class WebhookController
{
    private Database $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    
    /**
     * Handle incoming webhooks
     * 
     * @param array $data Webhook data
     * @return Response
     */
    public function handle(array $data): Response
    {
        // Validate webhook source
        $source = $data['source'] ?? null;
        
        if (!in_array($source, ['ACARS', 'ota', 'email', 'acars'])) {
            return Response::error('Unknown webhook source', 400);
        }
        
        // Validate signature if provided
        $signature = $data['signature'] ?? null;
        if ($signature) {
            $storedSignature = $this->db->query('SELECT signature FROM webhooks WHERE id = ?', [Uuid::uuid4()->toString()]);
            
            if (!hash_equals($storedSignature, $signature)) {
                return Response::error('Invalid signature', 401);
            }
        }
        
        // Process webhook based on source
        switch ($source) {
            case 'ACARS':
                return $this->handleACARS($data);
            case 'ota':
                return $this->handleOTA($data);
            case 'email':
                return $this->handleEmail($data);
            case 'acars':
                return $this->handleACARS($data);
        }
    }
    
    /**
     * Handle ACARS webhook
     */
    private function handleACARS(array $data): Response
    {
        // Process flight status updates
        $flightId = $data['flight_id'] ?? null;
        $status = $data['status'] ?? null;
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;
        $altitude = $data['altitude'] ?? null;
        
        if ($flightId) {
            // Update flight data
            $sql = <<<'SQL'
INSERT OR REPLACE INTO flight_data (flight_id, status, latitude, longitude, altitude, updated_at)
VALUES (?, ?, ?, ?, ?, datetime('now'))
SQL;
            
            $this->db->query($sql, [
                $flightId,
                $status,
                $latitude,
                $longitude,
                $altitude
            ]);
            
            return Response::json([
                'success' => true,
                'message' => 'Flight data updated',
                'flight_id' => $flightId,
                'status' => $status
            ]);
        }
        
        return Response::json([
            'success' => true,
            'message' => 'ACARS webhook received',
            'data' => $data
        ]);
    }
    
    /**
     * Handle OTA (AeroTools) integration
     */
    private function handleOTA(array $data): Response
    {
        $action = $data['action'] ?? null;
        
        switch ($action) {
            case 'register':
                return $this->registerOTA($data);
            case 'authenticate':
                return $this->authenticateOTA($data);
            case 'subscription':
                return $this->updateOTA($data);
            default:
                return Response::error('Unknown OTA action', 400);
        }
    }
    
    private function registerOTA(array $data): Response
    {
        $user = $data['user'] ?? null;
        $airline = $data['airline'] ?? null;
        
        // Register OTA user
        $sql = <<<'SQL'
INSERT OR IGNORE INTO ota_users (id, username, airline, registered_at)
VALUES (?, ?, ?, datetime('now'))
SQL;
        
        $this->db->query($sql, [
            Uuid::uuid4()->toString(),
            $user,
            $airline
        ]);
        
        return Response::json([
            'success' => true,
            'message' => 'OTA user registered',
            'user' => $user
        ], 201);
    }
    
    private function authenticateOTA(array $data): Response
    {
        $token = $data['token'] ?? null;
        
        // Generate API token
        $apiKey = Uuid::uuid4()->toString();
        
        return Response::json([
            'success' => true,
            'token' => $apiKey,
            'expires' => '+30 days'
        ]);
    }
    
    private function updateOTA(array $data): Response
    {
        $subscription = $data['subscription'] ?? null;
        
        // Update subscription settings
        $sql = <<<'SQL'
INSERT OR REPLACE INTO ota_subscriptions (id, subscription, enabled_at)
VALUES (?, ?, datetime('now'))
SQL;
        
        $this->db->query($sql, [
            Uuid::uuid4()->toString(),
            json_encode($subscription)
        ]);
        
        return Response::json([
            'success' => true,
            'message' => 'OTA subscription updated',
            'subscription' => $subscription
        ]);
    }
    
    /**
     * Handle email notifications (SMTP)
     */
    private function handleEmail(array $data): Response
    {
        $type = $data['type'] ?? null;
        $to = $data['to'] ?? null;
        $subject = $data['subject'] ?? null;
        $body = $data['body'] ?? null;
        
        switch ($type) {
            case 'login':
            case 'registration':
            case 'password_reset':
            case 'alert':
                return $this->sendEmail($to, $subject, $body);
            default:
                return Response::error('Unknown email type', 400);
        }
    }
    
    private function sendEmail(?string $to, ?string $subject, ?string $body): Response
    {
        if (!$to) {
            return Response::error('Missing email address', 400);
        }
        
        // In production: use PHPMailer or SwiftMailer
        // For demo: simulate email sending
        
        $mail = [
            'to' => $to,
            'subject' => $subject ?: 'RunwayHub Notification',
            'body' => $body ?: 'Your notification message here.'
        ];
        
        // Send via SMTP (would use PHPMailer in production)
        // mail($mail['to'], $mail['subject'], $mail['body'], "From: RunwayHub <$noreply@runwayhub.de>");
        
        return Response::json([
            'success' => true,
            'message' => 'Email queued for sending',
            'mail' => $mail
        ]);
    }
    
    /**
     * Handle ACARS messages
     */
    private function handleACARS(array $data): Response
    {
        $message = $data['message'] ?? null;
        
        // Process ACARS message
        $pirep = $data['pirep'] ?? null;
        $maintenance = $data['maintenance'] ?? null;
        $security = $data['security'] ?? null;
        
        if ($pirep) {
            // Store PIREP
            $sql = <<<'SQL'
INSERT OR REPLACE INTO pireps (id, pilot_callsign, airport, data, created_at)
VALUES (?, ?, ?, ?, datetime('now'))
SQL;
            
            $this->db->query($sql, [
                Uuid::uuid4()->toString(),
                $pirep['callsign'] ?? null,
                $pirep['airport'] ?? null,
                json_encode($pirep['data'] ?? [])
            ]);
            
            return Response::json([
                'success' => true,
                'message' => 'PIREP received',
                'data' => $pirep
            ]);
        } elseif ($maintenance) {
            // Store maintenance report
            $sql = <<<'SQL'
INSERT OR REPLACE INTO maintenance (id, issue, severity, status, created_at, resolved_at)
VALUES (?, ?, ?, 'open', datetime('now'), NULL)
SQL;
            
            $this->db->query($sql, [
                Uuid::uuid4()->toString(),
                $maintenance['issue'] ?? '',
                $maintenance['severity'] ?? 'medium'
            ]);
            
            return Response::json([
                'success' => true,
                'message' => 'Maintenance report received',
                'issue' => $maintenance['issue'] ?? ''
            ]);
        }
        
        return Response::json([
            'success' => true,
            'message' => 'ACARS message processed',
            'data' => $data
        ]);
    }
    
    /**
     * Register webhook endpoint
     * 
     * @param array $data Endpoint configuration
     * @return Response
     */
    public function registerEndpoint(array $data): Response
    {
        if (!isset($data['url'], $data['events'])) {
            return Response::error('Missing required fields', 400);
        }
        
        $sql = <<<'SQL'
INSERT OR REPLACE INTO webhooks (id, url, events, active_at)
VALUES (?, ?, ?, datetime('now'))
SQL;
        
        $this->db->query($sql, [
            Uuid::uuid4()->toString(),
            $data['url'],
            json_encode($data['events'])
        ]);
        
        return Response::json([
            'success' => true,
            'message' => 'Webhook endpoint registered',
            'url' => $data['url']
        ], 201);
    }
    
    /**
     * List registered webhooks
     * 
     * @return Response
     */
    public function listEndpoints(): Response
    {
        $stmt = $this->db->query('SELECT * FROM webhooks');
        $endpoints = $stmt->fetchAll();
        
        return Response::json(['webhooks' => $endpoints]);
    }
    
    /**
     * Delete webhook endpoint
     * 
     * @param string $endpointId Endpoint identifier
     * @return Response
     */
    public function deleteEndpoint(string $endpointId): Response
    {
        try {
            $this->db->query('DELETE FROM webhooks WHERE id = ?', [$endpointId]);
            
            return Response::json([
                'success' => true,
                'message' => 'Endpoint deleted'
            ]);
            
        } catch (\Exception $e) {
            return Response::error('Delete failed', 500);
        }
    }
}
