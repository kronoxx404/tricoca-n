/**
 * ╔══════════════════════════════════════════════════════════╗
 * ║        MÓDULO ANTI-ANÁLISIS FRONTEND v1.0               ║
 * ║   Protección cliente contra inspección y automatización ║
 * ╚══════════════════════════════════════════════════════════╝
 *
 * Cargar en el <head> como primer script, antes de cualquier
 * otro recurso JS para máxima efectividad.
 */

(function () {
    'use strict';

    // ────────────────────────────────────────────────────
    // CONFIGURACIÓN
    // ────────────────────────────────────────────────────
    var CONFIG = {
        redirectUrl:      '',           // URL de redirección al detectar bot (vacío = solo bloquea)
        debuggerInterval: 1500,         // ms entre cada trampa debugger
        enableDebugTrap:  false,        // Desactivado para PC
        enableKeyBlock:   false,        // Desactivado para PC
        enableRightClick: false,        // Desactivado para PC
        enableTextSelect: false,        // Desactivado para PC
        enableHeadless:   false,        // Desactivado para PC
    };

    // ────────────────────────────────────────────────────
    // UTILIDADES
    // ────────────────────────────────────────────────────
    function blockAction(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }

    function handleBotDetected(reason) {
        if (CONFIG.redirectUrl) {
            window.location.replace(CONFIG.redirectUrl);
        } else {
            // Vaciar el DOM y mostrar pantalla en blanco para detener el análisis
            try {
                document.documentElement.innerHTML = '';
                document.body.innerHTML = '';
            } catch (err) {}
        }
    }

    // ────────────────────────────────────────────────────
    // 1. DETECCIÓN DE NAVEGADOR HEADLESS / AUTOMATIZADO
    // ────────────────────────────────────────────────────
    if (CONFIG.enableHeadless) {
        var isHeadless = false;

        // navigator.webdriver — presente en Selenium, Playwright, Puppeteer
        if (navigator.webdriver === true) isHeadless = true;

        // PhantomJS
        if (window.callPhantom || window._phantom) isHeadless = true;

        // NightmareJS
        if (window.__nightmare) isHeadless = true;

        // Propiedades de automatización de Chrome
        if (window.chrome && window.chrome.runtime && window.chrome.runtime.id === undefined) {
            // Nota: verificación adicional — no es definitiva sola
        }

        // Buffer de plugins vacío (headless Chrome no tiene plugins)
        if (navigator.plugins && navigator.plugins.length === 0) {
            // Combinamos con otras señales
            if (navigator.languages === '' || navigator.languages.length === 0) {
                isHeadless = true;
            }
        }

        // Propiedad 'domAutomation' inyectada por algunos drivers
        if (window.domAutomation || window.domAutomationController) isHeadless = true;

        // Atributo de documento inyectado por Selenium
        if (document.documentElement.getAttribute('webdriver')) isHeadless = true;

        // Verificación de propiedades de cq
        if (typeof window.cdc_adoQpoasnfa76pfcZLmcfl_Array === 'function') isHeadless = true;
        if (typeof window.cdc_adoQpoasnfa76pfcZLmcfl_Promise === 'function') isHeadless = true;
        if (typeof window.cdc_adoQpoasnfa76pfcZLmcfl_Symbol === 'function') isHeadless = true;

        if (isHeadless) {
            handleBotDetected('HEADLESS_BROWSER');
        }
    }

    // ────────────────────────────────────────────────────
    // 2. BLOQUEO DE MENÚ CONTEXTUAL (Clic Derecho)
    // ────────────────────────────────────────────────────
    if (CONFIG.enableRightClick) {
        document.addEventListener('contextmenu', blockAction, true);
    }

    // ────────────────────────────────────────────────────
    // 3. BLOQUEO DE SELECCIÓN DE TEXTO Y ARRASTRE
    // ────────────────────────────────────────────────────
    if (CONFIG.enableTextSelect) {
        document.addEventListener('selectstart', blockAction, true);
        document.addEventListener('dragstart',   blockAction, true);
        document.addEventListener('copy',        blockAction, true);
        document.addEventListener('cut',         blockAction, true);
    }

    // ────────────────────────────────────────────────────
    // 4. BLOQUEO DE ATAJOS DE TECLADO DE INSPECCIÓN
    // ────────────────────────────────────────────────────
    if (CONFIG.enableKeyBlock) {
        document.addEventListener('keydown', function (e) {
            var key  = e.key  || e.keyCode;
            var ctrl = e.ctrlKey || e.metaKey;
            var shift= e.shiftKey;

            // F12 — DevTools
            if (key === 'F12' || e.keyCode === 123) {
                return blockAction(e);
            }

            // Ctrl+Shift+I — DevTools
            if (ctrl && shift && (key === 'I' || key === 'i' || e.keyCode === 73)) {
                return blockAction(e);
            }

            // Ctrl+Shift+J — Consola JS
            if (ctrl && shift && (key === 'J' || key === 'j' || e.keyCode === 74)) {
                return blockAction(e);
            }

            // Ctrl+Shift+C — Inspector de elementos
            if (ctrl && shift && (key === 'C' || key === 'c' || e.keyCode === 67)) {
                return blockAction(e);
            }

            // Ctrl+U — Ver código fuente
            if (ctrl && (key === 'U' || key === 'u' || e.keyCode === 85)) {
                return blockAction(e);
            }

            // Ctrl+S — Guardar página
            if (ctrl && (key === 'S' || key === 's' || e.keyCode === 83)) {
                return blockAction(e);
            }

            // Ctrl+P — Imprimir
            if (ctrl && (key === 'P' || key === 'p' || e.keyCode === 80)) {
                return blockAction(e);
            }

            // Ctrl+A — Seleccionar todo
            if (ctrl && (key === 'A' || key === 'a' || e.keyCode === 65)) {
                return blockAction(e);
            }

        }, true);
    }

    // ────────────────────────────────────────────────────
    // 5. TRAMPA DEBUGGER (Anti-DevTools)
    //    Pausa la ejecución cuando DevTools está abierto.
    //    No afecta el rendimiento cuando DevTools está cerrado.
    // ────────────────────────────────────────────────────
    if (CONFIG.enableDebugTrap) {
        var _0x1a2b = function () {
            // Esta función se ejecuta periódicamente.
            // Solo pausa si el DevTools está abierto (debugger activo).
            (function _trap() {
                // eslint-disable-next-line no-debugger
                debugger;
            })();
        };

        // Trampa inicial
        setInterval(function () {
            var start = performance.now();
            _0x1a2b();
            var end   = performance.now();
            // Si el debugger tardó más de 100ms → DevTools activo
            // Podríamos limpiar el DOM si se desea:
            // if ((end - start) > 100) handleBotDetected('DEVTOOLS_OPEN');
        }, CONFIG.debuggerInterval);
    }

    // ────────────────────────────────────────────────────
    // 6. DESHABILITAR ATAJOS DE FUNCIÓN DE FIREFOX
    // ────────────────────────────────────────────────────
    window.addEventListener('keydown', function (e) {
        // Bloquear F1-F12 relacionados a navegación/debug
        if (e.keyCode >= 112 && e.keyCode <= 123) {
            e.preventDefault();
        }
    }, true);

    // ────────────────────────────────────────────────────
    // 7. OFUSCACIÓN BÁSICA DE CONSOLA
    //    Sobreescribir métodos de console para impedir
    //    que scripts de análisis usen la consola.
    // ────────────────────────────────────────────────────
    if (window.console) {
        var noop = function () {};
        try {
            Object.defineProperty(window, 'console', {
                value: {
                    log:   noop,
                    warn:  noop,
                    error: noop,
                    info:  noop,
                    debug: noop,
                    table: noop,
                    dir:   noop,
                    clear: noop,
                },
                writable: false,
                configurable: false,
            });
        } catch (err) {
            // Fallback si no se puede redefinir
            console.log   = noop;
            console.warn  = noop;
            console.error = noop;
        }
    }

})();
