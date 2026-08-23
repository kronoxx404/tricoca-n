const fs = require('fs');

const src = 'c:/xampp/htdocs/Pruebas/jj/admin/admin.html';
const dest1 = 'c:/xampp/htdocs/Pruebas/jj/vercel_admin/admin.html';
const dest2 = 'c:/xampp/htdocs/Pruebas/jj/vercel_admin/public/admin.html';

if (fs.existsSync(src)) {
    fs.copyFileSync(src, dest1);
    fs.copyFileSync(src, dest2);
    console.log('✅ SYNCED PROBAR CONEXION BUTTON TO VERCEL ADMIN');
}
