<?php
/**
 * ╔══════════════════════════════════════════════════════════╗
 * ║          MÓDULO ANTI-BOT / ANTI-SCANNER v1.0            ║
 * ║     Blindaje multi-capa contra bots y scanners          ║
 * ╚══════════════════════════════════════════════════════════╝
 *
 * Intercepta requests antes de cualquier output HTML.
 * Incluir en la PRIMERA LÍNEA de index.php y admin.php.
 */

// ──────────────────────────────────────────────
// CONFIGURACIÓN
// ──────────────────────────────────────────────
define('ENABLE_LOG',       true);                          // Logging de IPs bloqueadas
define('LOG_FILE',         __DIR__ . '/logs/blocked.log'); // Ruta del log
define('FAKE_404',         true);                          // Respuesta 404 falsa a bots
define('REDIRECT_ON_BLOCK','');                            // URL de redirección (vacío = 404 falsa)

// ──────────────────────────────────────────────
// LISTA NEGRA DE USER-AGENTS
// ──────────────────────────────────────────────
$BLOCKED_USER_AGENTS = [
    // ── Servicios de análisis / reputación ──
    'virustotal',
    'phishtank',
    'netcraft',
    'urlvoid',
    'urlscan',
    'safebrowsing',
    'domaintools',
    'fortiguard',
    'barracuda',
    'trustwave',
    'webpulse',
    'bluecoat',

    // ── Scanners de seguridad / reconocimiento ──
    'shodan',
    'censys',
    'masscan',
    'nmap',
    'nikto',
    'sqlmap',
    'nessus',
    'openvas',
    'qualys',
    'acunetix',
    'burpsuite',
    'skipfish',
    'grabber',
    'w3af',
    'wapiti',
    'dirbuster',
    'gobuster',
    'nuclei',
    'zaproxy',
    'zap/',

    // ── Crawlers / Bots de motores de búsqueda ──
    'googlebot',
    'bingbot',
    'slurp',            // Yahoo
    'duckduckbot',
    'baiduspider',
    'yandexbot',
    'sogou',
    'exabot',
    'facebot',
    'ia_archiver',      // Wayback Machine / Archive.org
    'archive.org_bot',
    'wget',
    'curl/',
    'libcurl',
    'python-requests',
    'python-urllib',
    'go-http-client',
    'java/',
    'apache-httpclient',
    'okhttp',
    'axios/',
    'node-fetch',
    'got/',
    'undici',

    // ── Herramientas headless / automatización ──
    'headlesschrome',
    'phantomjs',
    'puppeteer',
    'selenium',
    'webdriver',
    'nightmare',
    'playwright',
    'cypress',
    'htmlunit',
    'triflejs',
    'slimerjs',

    // ── Rastreadores de SEO / análisis ──
    'ahrefsbot',
    'semrushbot',
    'mj12bot',
    'dotbot',
    'rogerbot',
    'uptimerobot',
    'pingdom',
    'statuscake',
    'site24x7',
    'gtmetrix',
    'pagespeed',
    'lighthouse',

    // ── Feeds / Agregadores ──
    'feedfetcher',
    'feedparser',
    'rss',
    'atom',
    'slackbot',

    // ── Genéricos sospechosos ──
    'httpclient',
    'lwp::simple',
    'libwww-perl',
    'wwwoffle',
    'peach',
    'ruby',
    'scrapy',
    'mechanize',
    'wget/',
];

// ──────────────────────────────────────────────
// OBTENER Y VALIDAR DATOS DE LA REQUEST
// ──────────────────────────────────────────────
$userAgent   = isset($_SERVER['HTTP_USER_AGENT'])  ? trim($_SERVER['HTTP_USER_AGENT'])  : '';
$acceptHeader= isset($_SERVER['HTTP_ACCEPT'])      ? trim($_SERVER['HTTP_ACCEPT'])      : '';
$clientIP    = get_client_ip();

// ──────────────────────────────────────────────
// REGLA 1: Bloquear User-Agent vacío o ausente
// ──────────────────────────────────────────────
if (empty($userAgent)) {
    log_blocked($clientIP, 'EMPTY_UA', $userAgent);
    serve_fake_response();
}

// ──────────────────────────────────────────────
// REGLA 2: Bloquear solicitudes sin Accept header
//          (patrón común de herramientas automáticas)
// ──────────────────────────────────────────────
if (empty($acceptHeader)) {
    log_blocked($clientIP, 'NO_ACCEPT_HEADER', $userAgent);
    serve_fake_response();
}

// ──────────────────────────────────────────────
// REGLA 3: Filtro de User-Agent en lista negra
// ──────────────────────────────────────────────
$uaLower = strtolower($userAgent);
foreach ($BLOCKED_USER_AGENTS as $pattern) {
    if (strpos($uaLower, strtolower($pattern)) !== false) {
        log_blocked($clientIP, 'BLOCKED_UA:' . $pattern, $userAgent);
        serve_fake_response();
    }
}

// ──────────────────────────────────────────────
// REGLA 4: Bloquear User-Agents sospechosamente cortos
//          (< 10 chars, casi siempre automatizaciones)
// ──────────────────────────────────────────────
if (strlen($userAgent) < 10) {
    log_blocked($clientIP, 'SHORT_UA', $userAgent);
    serve_fake_response();
}

// ══════════════════════════════════════════════
// FUNCIONES AUXILIARES
// ══════════════════════════════════════════════

/**
 * Obtiene la IP real del cliente respetando proxies confiables.
 */
function get_client_ip(): string {
    $headers = [
        'HTTP_CF_CONNECTING_IP',   // Cloudflare
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    ];
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = trim(explode(',', $_SERVER[$header])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return '0.0.0.0';
}

/**
 * Registra en el log las solicitudes bloqueadas.
 */
function log_blocked(string $ip, string $reason, string $ua): void {
    if (!ENABLE_LOG) return;

    $logDir = dirname(LOG_FILE);
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $uri       = $_SERVER['REQUEST_URI'] ?? '/';
    $method    = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $line      = "[{$timestamp}] IP={$ip} | REASON={$reason} | METHOD={$method} | URI={$uri} | UA={$ua}\n";

    @file_put_contents(LOG_FILE, $line, FILE_APPEND | LOCK_EX);
}

/**
 * Sirve una respuesta 404 genérica que no revela información del stack.
 * Los bots reciben una página de error estándar de Apache.
 */
function serve_fake_response(): void {
    // Limpiar cualquier buffer de salida
    if (ob_get_level()) ob_end_clean();

    // Si hay URL de redirección configurada, redirigir
    if (!empty(REDIRECT_ON_BLOCK)) {
        header('Location: ' . REDIRECT_ON_BLOCK, true, 302);
        exit();
    }

    // Respuesta 404 falsa — imita una página genérica de Apache
    http_response_code(404);
    header('Content-Type: text/html; charset=iso-8859-1');
    header('X-Powered-By: PHP/7.4.0'); // Versión falsa para confundir fingerprinting

    echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL was not found on this server.</p>
<hr>
<address>Apache/2.4.51 (Win64) Server at localhost Port 80</address>
</body></html>';
    exit();
}
