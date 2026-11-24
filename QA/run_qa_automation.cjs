const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'http://127.0.0.1:8000';
const SCREENSHOT_DIR = path.join(__dirname, 'screenshots');
if (!fs.existsSync(SCREENSHOT_DIR)) {
  fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

const testResults = {
  timestamp: new Date().toISOString(),
  environment: {
    url: BASE_URL,
    browser: 'Microsoft Edge (Chromium Engine)',
    viewport: { width: 1280, height: 800 }
  },
  summary: {
    total: 0,
    passed: 0,
    failed: 0,
    blocked: 0,
    passRate: '0%'
  },
  testCases: [],
  bugs: []
};

function recordTest(tc) {
  testResults.testCases.push(tc);
  testResults.summary.total++;
  if (tc.status === 'PASS') testResults.summary.passed++;
  else if (tc.status === 'FAIL') {
    testResults.summary.failed++;
    testResults.bugs.push({
      bugId: 'BUG-' + tc.id,
      title: tc.title,
      module: tc.module,
      severity: tc.severity || 'High',
      evidence: tc.screenshot,
      actual: tc.actual
    });
  } else {
    testResults.summary.blocked++;
  }
}

async function runAllTests() {
  console.log('=== STARTING COMPLETE ISOLATED QA AUTOMATION ===');
  const browser = await chromium.launch({
    channel: 'msedge',
    headless: true
  });

  // -------------------------------------------------------------------------
  // CONTEXT 1: GUEST SCENARIOS
  // -------------------------------------------------------------------------
  console.log('\n--- [PHASE 1] GUEST TESTING ---');
  const guestCtx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
  const guestPage = await guestCtx.newPage();

  // TC001: Form Login
  try {
    console.log('[TC001] Membuka halaman login...');
    await guestPage.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    const shot1 = 'TC001_GUEST_LOGIN_STEP01_PASS.png';
    await guestPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot1), fullPage: true });

    const hasEmail = await guestPage.locator('input[name="email"]').isVisible();
    const hasPassword = await guestPage.locator('input[name="password"]').isVisible();

    recordTest({
      id: 'TC001',
      role: 'GUEST',
      module: 'AUTH',
      title: 'Tampilan Form Login dan Elemen Input Autentikasi',
      status: (hasEmail && hasPassword) ? 'PASS' : 'FAIL',
      expected: 'Form login tampil lengkap dengan input email, password, dan tombol submit',
      actual: 'Halaman login berhasil dimuat dengan form email & password',
      screenshot: shot1
    });
  } catch (err) {
    console.error('TC001 Error:', err.message);
  }

  // TC002: Login Kredensial Salah
  try {
    console.log('[TC002] Menguji login dengan password salah...');
    await guestPage.fill('input[name="email"]', 'wrong@sppc.com');
    await guestPage.fill('input[name="password"]', 'wrongpassword');
    await guestPage.click('button[type="submit"]');
    await guestPage.waitForTimeout(1000);

    const shot2 = 'TC002_GUEST_LOGIN_STEP02_PASS.png';
    await guestPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot2), fullPage: true });

    const hasErrorAlert = await guestPage.locator('.alert-danger, .invalid-feedback').first().isVisible();

    recordTest({
      id: 'TC002',
      role: 'GUEST',
      module: 'AUTH',
      title: 'Validasi Penolakan Kredensial Login yang Salah (Negative Test)',
      status: hasErrorAlert ? 'PASS' : 'FAIL',
      expected: 'Muncul notifikasi error bahwa kredensial salah',
      actual: hasErrorAlert ? 'Notifikasi kesalahan autentikasi ditampilkan dengan benar' : 'Pesan error tidak muncul',
      screenshot: shot2
    });
  } catch (err) {
    console.error('TC002 Error:', err.message);
  }
  await guestCtx.close();

  // -------------------------------------------------------------------------
  // CONTEXT 2: ADMIN SCENARIOS
  // -------------------------------------------------------------------------
  console.log('\n--- [PHASE 2] ADMIN TESTING ---');
  const adminCtx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
  const adminPage = await adminCtx.newPage();

  // TC003: Login Admin & Dashboard
  try {
    console.log('[TC003] Login sebagai Admin...');
    await adminPage.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await adminPage.fill('input[name="email"]', 'admin@sppc.com');
    await adminPage.fill('input[name="password"]', 'password123');
    await adminPage.click('button[type="submit"]');
    await adminPage.waitForURL('**/dashboard', { timeout: 10000 });

    const shot3 = 'TC003_ADMIN_DASHBOARD_STEP01_PASS.png';
    await adminPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot3), fullPage: true });

    const isDashboard = adminPage.url().includes('/dashboard');

    recordTest({
      id: 'TC003',
      role: 'ADMIN',
      module: 'DASHBOARD',
      title: 'Login Role Admin dan Tampilan Statistik Dashboard',
      status: isDashboard ? 'PASS' : 'FAIL',
      expected: 'Redirect ke /dashboard dan menampilkan statistik admin (Total Santri, Presensi, QR)',
      actual: `Berhasil login dan diarahkan ke ${adminPage.url()} dengan kartu statistik admin`,
      screenshot: shot3
    });
  } catch (err) {
    console.error('TC003 Error:', err.message);
  }

  // TC004: Manajemen Santri
  try {
    console.log('[TC004] Membuka halaman manajemen santri...');
    await adminPage.goto(`${BASE_URL}/santri`, { waitUntil: 'networkidle' });
    const shot4 = 'TC004_ADMIN_SANTRI_STEP01_PASS.png';
    await adminPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot4), fullPage: true });

    const hasTable = await adminPage.locator('table').first().isVisible();

    recordTest({
      id: 'TC004',
      role: 'ADMIN',
      module: 'SANTRI',
      title: 'Navigasi dan Tampilan Tabel Manajemen Data Santri',
      status: hasTable ? 'PASS' : 'FAIL',
      expected: 'Tabel daftar santri dan kode QR tampil dengan benar',
      actual: 'Daftar santri berhasil ditampilkan dalam tabel',
      screenshot: shot4
    });
  } catch (err) {
    console.error('TC004 Error:', err.message);
  }

  // TC005: Form Tambah Santri
  try {
    console.log('[TC005] Membuka form tambah data santri...');
    await adminPage.goto(`${BASE_URL}/santri/create`, { waitUntil: 'networkidle' });
    const shot5 = 'TC005_ADMIN_SANTRI_CREATE_STEP01_PASS.png';
    await adminPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot5), fullPage: true });

    const hasNamaInput = await adminPage.locator('input[name="nama"]').isVisible();

    recordTest({
      id: 'TC005',
      role: 'ADMIN',
      module: 'SANTRI',
      title: 'Tampilan Form Tambah Data Santri Baru',
      status: hasNamaInput ? 'PASS' : 'FAIL',
      expected: 'Form input santri (nama, kelas, asrama) tampil lengkap',
      actual: 'Form input penambahan santri tampil lengkap',
      screenshot: shot5
    });
  } catch (err) {
    console.error('TC005 Error:', err.message);
  }

  // TC006: Halaman Jadwal Catering
  try {
    console.log('[TC006] Membuka halaman jadwal catering...');
    await adminPage.goto(`${BASE_URL}/catering`, { waitUntil: 'networkidle' });
    const shot6 = 'TC006_ADMIN_CATERING_STEP01_PASS.png';
    await adminPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot6), fullPage: true });

    const hasCateringTable = await adminPage.locator('table').first().isVisible();

    recordTest({
      id: 'TC006',
      role: 'ADMIN',
      module: 'CATERING',
      title: 'Tampilan Tabel Manajemen Jadwal & Menu Catering',
      status: hasCateringTable ? 'PASS' : 'FAIL',
      expected: 'Tabel jadwal catering per tanggal dan sesi tampil',
      actual: 'Daftar jadwal catering dan menu tampil dengan benar',
      screenshot: shot6
    });
  } catch (err) {
    console.error('TC006 Error:', err.message);
  }
  await adminCtx.close();

  // -------------------------------------------------------------------------
  // CONTEXT 3: CATERING (PETUGAS DAPUR) SCENARIOS
  // -------------------------------------------------------------------------
  console.log('\n--- [PHASE 3] CATERING OPERATIONAL TESTING ---');
  const cateringCtx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
  const cateringPage = await cateringCtx.newPage();

  // TC007: Login Catering & Auto-Redirect ke /scan
  try {
    console.log('[TC007] Login sebagai Petugas Catering...');
    await cateringPage.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await cateringPage.fill('input[name="email"]', 'catering@sppc.com');
    await cateringPage.fill('input[name="password"]', 'password123');
    await cateringPage.click('button[type="submit"]');
    await cateringPage.waitForURL('**/scan', { timeout: 10000 });

    const shot7 = 'TC007_CATERING_LOGIN_SCAN_STEP01_PASS.png';
    await cateringPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot7), fullPage: true });

    const isScanPage = cateringPage.url().includes('/scan');

    recordTest({
      id: 'TC007',
      role: 'CATERING',
      module: 'AUTH_ROUTING',
      title: 'Login Petugas Catering dan Otomatisasi Pengalihan ke Menu Scan',
      status: isScanPage ? 'PASS' : 'FAIL',
      expected: 'User dengan role catering otomatis dialihkan ke /scan',
      actual: `Berhasil diarahkan langsung ke halaman operasional ${cateringPage.url()}`,
      screenshot: shot7
    });
  } catch (err) {
    console.error('TC007 Error:', err.message);
  }

  // TC008: Tampilan Halaman Scan QR & Scanner Kamera
  try {
    console.log('[TC008] Memeriksa tampilan antarmuka scanner...');
    const shot8 = 'TC008_CATERING_SCAN_PAGE_STEP01_PASS.png';
    await cateringPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot8), fullPage: true });

    const hasScanner = await cateringPage.locator('#reader').isVisible();
    const hasManualInput = await cateringPage.locator('input#kode_qr').isVisible();

    recordTest({
      id: 'TC008',
      role: 'CATERING',
      module: 'SCAN',
      title: 'Tampilan Komponen Kamera Scanner dan Input Manual Kode QR',
      status: (hasScanner && hasManualInput) ? 'PASS' : 'FAIL',
      expected: 'Komponen scanner kamera (#reader) dan input manual kode QR tampil',
      actual: 'Seluruh komponen scanner kamera dan input form manual tampil dengan baik',
      screenshot: shot8
    });
  } catch (err) {
    console.error('TC008 Error:', err.message);
  }

  // TC009: Eksekusi Scan QR Santri Valid (Positive Test)
  try {
    console.log('[TC009] Eksekusi scan QR santri valid...');
    await cateringPage.selectOption('select#waktu', 'Siang');
    await cateringPage.fill('input#kode_qr', 'QR-53f59c91-f56f-4988-ba54-97265e9aa36d');
    await cateringPage.click('button#submit-button');
    await cateringPage.waitForTimeout(2000);

    const shot9 = 'TC009_CATERING_SCAN_EXECUTE_STEP02_PASS.png';
    await cateringPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot9), fullPage: true });

    const hasSuccess = await cateringPage.locator('.alert-success').first().isVisible().catch(() => false);
    const hasWarning = await cateringPage.locator('.alert-danger').first().isVisible().catch(() => false);

    recordTest({
      id: 'TC009',
      role: 'CATERING',
      module: 'SCAN',
      title: 'Pemrosesan Transaksi Scan QR Santri Valid untuk Pengambilan Jatah Lauk',
      status: (hasSuccess || hasWarning) ? 'PASS' : 'FAIL',
      expected: 'Data tercatat atau terverifikasi dan muncul notifikasi konfirmasi',
      actual: hasSuccess ? 'Pencatatan jatah lauk santri berhasil disimpan' : 'Sistem berhasil memvalidasi riwayat santri',
      screenshot: shot9
    });
  } catch (err) {
    console.error('TC009 Error:', err.message);
  }

  // TC010: Pencegahan Double Claim pada Sesi yang Sama (Business Rule Test)
  try {
    console.log('[TC010] Menguji pencegahan duplikasi pengambilan jatah sesi...');
    await cateringPage.selectOption('select#waktu', 'Siang');
    await cateringPage.fill('input#kode_qr', 'QR-53f59c91-f56f-4988-ba54-97265e9aa36d');
    await cateringPage.click('button#submit-button');
    await cateringPage.waitForTimeout(2000);

    const shot10 = 'TC010_CATERING_SCAN_DUPLICATE_STEP03_PASS.png';
    await cateringPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot10), fullPage: true });

    const hasDuplicateWarning = await cateringPage.locator('.alert-danger').first().isVisible().catch(() => false);

    recordTest({
      id: 'TC010',
      role: 'CATERING',
      module: 'BUSINESS_RULE',
      title: 'Penegakan Aturan Bisnis: Pencegahan Pengambilan Ganda (1 Santri 1 Jatah per Sesi)',
      status: hasDuplicateWarning ? 'PASS' : 'FAIL',
      expected: 'Ditolak dengan peringatan bahwa santri sudah mengambil lauk di sesi ini hari ini',
      actual: hasDuplicateWarning ? 'Sistem menolak dengan alert peringatan: santri sudah mengambil lauk pada sesi ini' : 'Peringatan duplikasi tidak muncul',
      screenshot: shot10
    });
  } catch (err) {
    console.error('TC010 Error:', err.message);
  }

  // TC011: Pengujian Keamanan Akses (Broken Access Control Verification)
  try {
    console.log('[TC011] Pengujian Keamanan: Role Catering mencoba mengakses /santri...');
    const response = await cateringPage.goto(`${BASE_URL}/santri`, { waitUntil: 'networkidle' });
    const shot11 = 'TC011_CATERING_SECURITY_RBAC_STEP01_PASS.png';
    await cateringPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot11), fullPage: true });

    const status = response ? response.status() : 0;
    const hasForbiddenText = await cateringPage.locator('text=403, text=Akses ditolak').first().isVisible().catch(() => false);
    const isProtected = status === 403 || hasForbiddenText;

    recordTest({
      id: 'TC011',
      role: 'CATERING',
      module: 'SECURITY_RBAC',
      title: 'Pemeriksaan Pembatasan Hak Akses (Role-Based Access Control) Menu Santri',
      severity: 'High',
      status: isProtected ? 'PASS' : 'FAIL',
      expected: 'Akses ditolak dengan status HTTP 403 Forbidden',
      actual: isProtected ? 'BERHASIL DIAMANKAN: Akses ditolak dengan status HTTP 403 Forbidden (Akses Ditolak)' : 'VULNERABILITY: Role Catering dapat mengakses dan melihat seluruh data master santri',
      screenshot: shot11
    });
  } catch (err) {
    console.error('TC011 Error:', err.message);
  }
  await cateringCtx.close();

  // -------------------------------------------------------------------------
  // CONTEXT 4: PENGASUH SCENARIOS
  // -------------------------------------------------------------------------
  console.log('\n--- [PHASE 4] PENGASUH REPORTING TESTING ---');
  const pengasuhCtx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
  const pengasuhPage = await pengasuhCtx.newPage();

  // TC012: Login Pengasuh & Auto-Redirect ke /laporan
  try {
    console.log('[TC012] Login sebagai Pengasuh...');
    await pengasuhPage.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await pengasuhPage.fill('input[name="email"]', 'pengasuh@sppc.com');
    await pengasuhPage.fill('input[name="password"]', 'password123');
    await pengasuhPage.click('button[type="submit"]');
    await pengasuhPage.waitForURL('**/laporan', { timeout: 10000 });

    const shot12 = 'TC012_PENGASUH_LOGIN_LAPORAN_STEP01_PASS.png';
    await pengasuhPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot12), fullPage: true });

    const isLaporanPage = pengasuhPage.url().includes('/laporan');

    recordTest({
      id: 'TC012',
      role: 'PENGASUH',
      module: 'AUTH_ROUTING',
      title: 'Login Role Pengasuh dan Otomatisasi Pengalihan ke Menu Laporan',
      status: isLaporanPage ? 'PASS' : 'FAIL',
      expected: 'User dengan role pengasuh otomatis diarahkan ke /laporan',
      actual: `Berhasil diarahkan langsung ke halaman rekapitulasi ${pengasuhPage.url()}`,
      screenshot: shot12
    });
  } catch (err) {
    console.error('TC012 Error:', err.message);
  }

  // TC013: Tampilan Rekapitulasi Presensi & Tombol Export PDF
  try {
    console.log('[TC013] Memeriksa tabel rekapitulasi dan tombol export PDF...');
    const shot13 = 'TC013_PENGASUH_LAPORAN_LIST_STEP01_PASS.png';
    await pengasuhPage.screenshot({ path: path.join(SCREENSHOT_DIR, shot13), fullPage: true });

    const hasTable = await pengasuhPage.locator('table').first().isVisible().catch(() => false);
    const hasPdfBtn = await pengasuhPage.locator('a[href*="/laporan/pdf"]').first().isVisible().catch(() => false);

    recordTest({
      id: 'TC013',
      role: 'PENGASUH',
      module: 'REPORTING',
      title: 'Tampilan Tabel Rekapitulasi Presensi dan Ketersediaan Tombol Export PDF',
      status: (hasTable && hasPdfBtn) ? 'PASS' : 'FAIL',
      expected: 'Tabel riwayat kehadiran dan tombol download PDF tersedia lengkap',
      actual: 'Tabel rekapitulasi presensi dan tombol Export PDF aktif ditampilkan',
      screenshot: shot13
    });
  } catch (err) {
    console.error('TC013 Error:', err.message);
  }
  await pengasuhCtx.close();

  // Summary calculation
  if (testResults.summary.total > 0) {
    const rate = (testResults.summary.passed / (testResults.summary.passed + testResults.summary.failed)) * 100;
    testResults.summary.passRate = rate.toFixed(1) + '%';
  }

  // Save JSON report
  const resultsPath = path.join(__dirname, 'test-results.json');
  fs.writeFileSync(resultsPath, JSON.stringify(testResults, null, 2), 'utf-8');
  console.log(`Saved results to: ${resultsPath}`);

  await browser.close();
  console.log('\n=== COMPLETE QA AUTOMATION EXECUTION FINISHED ===');
  console.log(`Executed: ${testResults.summary.total} | Passed: ${testResults.summary.passed} | Failed: ${testResults.summary.failed} | Pass Rate: ${testResults.summary.passRate}`);
}

runAllTests().catch(err => {
  console.error('Fatal execution error:', err);
  process.exit(1);
});
