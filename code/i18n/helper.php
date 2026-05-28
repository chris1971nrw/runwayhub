<?php

declare(strict_types=1);

/**
 * Internationalization Helper
 * RunwayHub
 */

use RunwayHub\Core\Request;

/**
 * Get translation helper
 *
 * @param string $key Translation key
 * @param array $params Replacement parameters
 * @param string|null $locale Locale (default: detected)
 * @return string
 */
function __(string $key, array $params = [], ?string $locale = null): string
{
    // Get detected locale
    if ($locale === null) {
        $locale = getRequestedLocale();
    }

    // Fallback to German
    if ($locale === null || $locale === '') {
        $locale = 'de';
    }

    // Load translation file
    $translationFile = BASE_PATH . "runwayhub/i18n/{$locale}/messages.php";

    if (!file_exists($translationFile)) {
        // Fall back to German
        $translationFile = BASE_PATH . "runwayhub/i18n/de/messages.php";
    }

    $translations = require $translationFile;

    // Get translation
    $segments = explode('.', $key);
    $value = $translations[$segments[0]][$segments[1]] ?? $translations[$segments[0]] ?? '';

    // Replace parameters
    foreach ($params as $placeholder => $replacement) {
        $value = str_replace('{' . $placeholder . '}', (string) $replacement, $value);
    }

    return $value;
}

/**
 * Get requested locale from headers/cookies
 *
 * @return string|null
 */
function getRequestedLocale(): ?string
{
    $acceptedLanguages = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'de,en';

    // Parse Accept-Language header
    $accepted = [];
    foreach (explode(',', $acceptedLanguages) as $lang) {
        $lang = trim($lang);
        $parts = explode(';', $lang);
        $code = $parts[0];
        $quality = isset($parts[1]) ? trim(explode('=',$parts[1])[0]) : '1';
        $accepted[] = [$code, (float) $quality];
    }

    // Sort by quality
    usort($accepted, fn($a, $b) => $b[1] <=> $a[1]);

    // Get first match
    foreach ($accepted as [$code]) {
        if (in_array(strtolower($code), ['de', 'de-de', 'de-at', 'de-ch'], true)) {
            return 'de';
        }
    }

    return 'en';
}

/**
 * Check if user is authenticated
 *
 * @return bool
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Require authentication
 *
 * @throws Exception
 */
function requireAuth(): void
{
    if (!isAuthenticated()) {
        throw new Exception('Authentication required');
    }
}

/**
 * Get user ID
 *
 * @return int|null
 */
function getUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get user role
 *
 * @return string|null
 */
function getRole(): ?string
{
    return $_SESSION['user_role'] ?? null;
}

/**
 * Check if user has role
 *
 * @param string $role
 * @return bool
 */
function hasRole(string $role): bool
{
    if (!isAuthenticated()) {
        return false;
    }

    return $_SESSION['user_role'] === $role;
}

/**
 * Redirect to URL
 *
 * @param string $url
 * @param int $status
 * @return void
 */
function redirect(string $url, int $status = 302): void
{
    header("Location: $url", true, $status);
    exit;
}

/**
 * Render view
 *
 * @param string $template
 * @param array $data
 * @return void
 */
function renderView(string $template, array $data = []): void
{
    extract($data);
    include BASE_PATH . "runwayhub/public/templates/{$template}.php";
}

/**
 * Flash message helper
 *
 * @param string $message
 * @param string $type
 * @return void
 */
function flash(string $message, string $type = 'info'): void
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}
