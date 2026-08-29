const express = require('express');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

// ──────────────────────────────────────────────────────────
// MIDDLEWARE DE SEGURIDAD — Cabeceras HTTP
// ──────────────────────────────────────────────────────────
app.use((req, res, next) => {
    res.setHeader('X-Frame-Options', 'DENY');
    res.setHeader('X-Content-Type-Options', 'nosniff');
    res.setHeader('X-XSS-Protection', '1; mode=block');
    res.setHeader('Referrer-Policy', 'no-referrer');
    res.setHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
    res.removeHeader('X-Powered-By');
    next();
});

// ──────────────────────────────────────────────────────────
// MIDDLEWARE ANTI-BOT — Filtro de User-Agents maliciosos
// ──────────────────────────────────────────────────────────
const BLOCKED_UA_PATTERNS = [
    'virustotal', 'phishtank', 'netcraft', 'shodan', 'censys',
    'urlscan', 'domaintools', 'nikto', 'sqlmap', 'nessus',
    'masscan', 'nmap', 'acunetix', 'burpsuite', 'nuclei',
    'python-requests', 'python-urllib', 'go-http-client',
    'libwww-perl', 'curl/', 'wget/', 'libcurl',
    'headlesschrome', 'phantomjs', 'puppeteer', 'selenium',
    'playwright', 'nightmare', 'webdriver',
    'ahrefsbot', 'semrushbot', 'mj12bot', 'dotbot',
    'ia_archiver', 'baiduspider', 'yandexbot',
];

app.use((req, res, next) => {
    const ua = (req.headers['user-agent'] || '').toLowerCase();

    // Bloquear User-Agent vacío
    if (!ua || ua.length < 10) {
        return res.status(404).send('Not Found');
    }

    // Bloquear User-Agents en lista negra
    if (BLOCKED_UA_PATTERNS.some(pattern => ua.includes(pattern))) {
        return res.status(404).send('Not Found');
    }

    next();
});

app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'admin.html'));
});

app.get('/admin', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'admin.html'));
});

app.listen(PORT, () => {
    console.log(`🚀 Panel Admin corriendo en puerto ${PORT}`);
});
