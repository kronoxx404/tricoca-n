const fs = require('fs');

const src = 'c:/xampp/htdocs/Pruebas/jj/admin/sys_adm_v92.html';
const dest1 = 'c:/xampp/htdocs/Pruebas/jj/vercel_admin/sys_adm_v92.html';
const dest2 = 'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/sys_adm_v92.html';

if (fs.existsSync(src)) {
    fs.copyFileSync(src, dest1);
    fs.copyFileSync(src, dest2);
    console.log('✅ SYNCED PROBAR CONEXION BUTTON TO VERCEL ADMIN');
}
