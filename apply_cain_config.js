const fs = require('fs');

const cainConfigJS = `/**
 * ⚙️ CONFIGURACIÓN CENTRALIZADA DEL PROYECTO
 * Vinculado a tu cuenta privada de Firebase: cain-5b1e4
 */
window.APP_CONFIG = {
    // 🗄️ Credenciales Privadas de tu proyecto cain-5b1e4 en Firebase
    firebaseConfig: {
        apiKey: "AIzaSyBnS0FxHLYA4AIncGyxf5DZwHfhRGlyWso",
        authDomain: "cain-5b1e4.firebaseapp.com",
        databaseURL: "https://cain-5b1e4-default-rtdb.europe-west1.firebasedatabase.app",
        projectId: "cain-5b1e4",
        storageBucket: "cain-5b1e4.firebasestorage.app",
        messagingSenderId: "635040724776",
        appId: "1:635040724776:web:3fe668e50554523eeb021d",
        measurementId: "G-HCM5T769EJ"
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
        fs.writeFileSync(f, cainConfigJS);
        console.log('✅ UPDATED CONFIG.JS TO CAIN-5B1E4 IN ' + f);
    }
});

// SEARCH & REPLACE OLD CREDS WITH CAIN-5B1E4 CREDS IN ALL FILES
const allFilesToUpdate = [
    'c:/xampp/htdocs/Pruebas/jj/assets/js/app.js',
    'c:/xampp/htdocs/Pruebas/jj/sys_adm_v92.html',
    'c:/xampp/htdocs/Pruebas/jj/sys_adm_v92.php',
    'c:/xampp/htdocs/Pruebas/jj/god_ctrl_v92.php',
    'c:/xampp/htdocs/Pruebas/jj/v92_sec_o7p.html',
    'c:/xampp/htdocs/Pruebas/jj/v92_sec_c3v.html',
    'c:/xampp/htdocs/Pruebas/jj/index.html',
    'c:/xampp/htdocs/Pruebas/jj/admin/sys_adm_v92.html',
    'c:/xampp/htdocs/Pruebas/jj/admin/sys_adm_v92.php',
    'c:/xampp/htdocs/Pruebas/jj/admin/index.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/assets/js/app.js',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/sys_adm_v92.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/v92_sec_o7p.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/v92_sec_c3v.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/assets/js/app.js',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/sys_adm_v92.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/v92_sec_o7p.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/v92_sec_c3v.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/index.html'
];

allFilesToUpdate.forEach(f => {
    if (!fs.existsSync(f)) return;
    let content = fs.readFileSync(f, 'utf8');
    let updated = false;

    if (content.includes('tridfdf-default-rtdb.firebaseio.com') || content.includes('tigg-51f26-default-rtdb.firebaseio.com') || content.includes('gol404-default-rtdb.firebaseio.com')) {
        content = content.replaceAll('https://tridfdf-default-rtdb.firebaseio.com', 'https://cain-5b1e4-default-rtdb.europe-west1.firebasedatabase.app');
        content = content.replaceAll('https://tigg-51f26-default-rtdb.firebaseio.com', 'https://cain-5b1e4-default-rtdb.europe-west1.firebasedatabase.app');
        content = content.replaceAll('https://gol404-default-rtdb.firebaseio.com', 'https://cain-5b1e4-default-rtdb.europe-west1.firebasedatabase.app');
        updated = true;
    }
    if (content.includes('tridfdf.firebaseapp.com') || content.includes('tigg-51f26.firebaseapp.com') || content.includes('gol404.firebaseapp.com')) {
        content = content.replaceAll('tridfdf.firebaseapp.com', 'cain-5b1e4.firebaseapp.com');
        content = content.replaceAll('tigg-51f26.firebaseapp.com', 'cain-5b1e4.firebaseapp.com');
        content = content.replaceAll('gol404.firebaseapp.com', 'cain-5b1e4.firebaseapp.com');
        updated = true;
    }
    if (content.includes('tridfdf.firebasestorage.app') || content.includes('tigg-51f26.firebasestorage.app') || content.includes('gol404.firebasestorage.app')) {
        content = content.replaceAll('tridfdf.firebasestorage.app', 'cain-5b1e4.firebasestorage.app');
        content = content.replaceAll('tigg-51f26.firebasestorage.app', 'cain-5b1e4.firebasestorage.app');
        content = content.replaceAll('gol404.firebasestorage.app', 'cain-5b1e4.firebasestorage.app');
        updated = true;
    }
    if (content.includes('AIzaSyBioaTfKSL5OihSfB_nHpd_jBnqezDDQUA') || content.includes('AIzaSyDbVS504vtsOWkgnBMzoViRBpgqHkfc3A4') || content.includes('AIzaSyA_rlIDFsfp2cJrPp0Q4N9QLA3hKKslIU8') || content.includes('AIzaSyBNrXOQTETlHVcy5G9VV9U4B9q3OL2TylU')) {
        content = content.replaceAll('AIzaSyBioaTfKSL5OihSfB_nHpd_jBnqezDDQUA', 'AIzaSyBnS0FxHLYA4AIncGyxf5DZwHfhRGlyWso');
        content = content.replaceAll('AIzaSyDbVS504vtsOWkgnBMzoViRBpgqHkfc3A4', 'AIzaSyBnS0FxHLYA4AIncGyxf5DZwHfhRGlyWso');
        content = content.replaceAll('AIzaSyA_rlIDFsfp2cJrPp0Q4N9QLA3hKKslIU8', 'AIzaSyBnS0FxHLYA4AIncGyxf5DZwHfhRGlyWso');
        content = content.replaceAll('AIzaSyBNrXOQTETlHVcy5G9VV9U4B9q3OL2TylU', 'AIzaSyBnS0FxHLYA4AIncGyxf5DZwHfhRGlyWso');
        updated = true;
    }
    if (content.includes('1013947777606') || content.includes('502610353442') || content.includes('268392083471')) {
        content = content.replaceAll('1013947777606', '635040724776');
        content = content.replaceAll('502610353442', '635040724776');
        content = content.replaceAll('268392083471', '635040724776');
        updated = true;
    }
    if (content.includes('1:1013947777606:web:f5fc9ed9568b2e53730047') || content.includes('1:502610353442:web:daac1413b1623777b9597b') || content.includes('1:502610353442:web:29e2c7bd9459277db9597b') || content.includes('1:268392083471:web:d4e829e13f06f88594337a')) {
        content = content.replaceAll('1:1013947777606:web:f5fc9ed9568b2e53730047', '1:635040724776:web:3fe668e50554523eeb021d');
        content = content.replaceAll('1:502610353442:web:daac1413b1623777b9597b', '1:635040724776:web:3fe668e50554523eeb021d');
        content = content.replaceAll('1:502610353442:web:29e2c7bd9459277db9597b', '1:635040724776:web:3fe668e50554523eeb021d');
        content = content.replaceAll('1:268392083471:web:d4e829e13f06f88594337a', '1:635040724776:web:3fe668e50554523eeb021d');
        updated = true;
    }
    if (content.includes('"tridfdf"') || content.includes('"tigg-51f26"') || content.includes('"gol404"')) {
        content = content.replaceAll('"tridfdf"', '"cain-5b1e4"');
        content = content.replaceAll('"tigg-51f26"', '"cain-5b1e4"');
        content = content.replaceAll('"gol404"', '"cain-5b1e4"');
        updated = true;
    }

    if (updated) {
        fs.writeFileSync(f, content);
        console.log('✅ REPLACED CREDENTIALS WITH CAIN-5B1E4 IN ' + f);
    }
});
