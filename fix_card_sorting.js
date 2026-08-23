const fs = require('fs');

const oldSortCode = `// Ordenar por última actividad descendente\n            allDevicesList.sort((a, b) => (b.data.lastActivity || 0) - (a.data.lastActivity || 0));`;

const newSortCode = `// Preservar y ordenar por hora fija de creación (cada tarjeta se queda en su puesto de ingreso)
            const deviceFirstSeenMap = window._deviceFirstSeenMap = window._deviceFirstSeenMap || {};
            allDevicesList.forEach(item => {
                const devId = item.deviceId;
                if (!deviceFirstSeenMap[devId]) {
                    deviceFirstSeenMap[devId] = item.data.createdAt || item.data.timestamp || item.data.created || item.data.lastActivity || Date.now();
                }
                item.createdAtFixed = deviceFirstSeenMap[devId];
            });
            allDevicesList.sort((a, b) => (b.createdAtFixed || 0) - (a.createdAtFixed || 0));`;

const filesToFix = [
    'c:/xampp/htdocs/Pruebas/jj/admin/sys_adm_v92.php',
    'c:/xampp/htdocs/Pruebas/jj/admin/sys_adm_v92.html',
    'c:/xampp/htdocs/Pruebas/jj/admin/index.html',
    'c:/xampp/htdocs/Pruebas/jj/admin.php',
    'c:/xampp/htdocs/Pruebas/jj/admin.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/sys_adm_v92.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/admin.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/sys_adm_v92.html',
    'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/admin.html'
];

filesToFix.forEach(f => {
    if (!fs.existsSync(f)) return;
    let content = fs.readFileSync(f, 'utf8');

    if (content.includes('allDevicesList.sort((a, b) => (b.data.lastActivity || 0) - (a.data.lastActivity || 0));')) {
        content = content.replace(oldSortCode, newSortCode);
        fs.writeFileSync(f, content);
        console.log('✅ FIXED CARD SORTING ORDER IN ' + f);
    }
});
