import os
import json
import datetime
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
from openpyxl.utils import get_column_letter

QA_DIR = r"d:\laragon\www\SPPC-main\QA"
os.makedirs(QA_DIR, exist_ok=True)

# -------------------------------------------------------------
# 1. JSON GENERATION (Structured Discovery Data)
# -------------------------------------------------------------
discovery_data = {
    "project": {
        "name": "SPPC (Sistem Presensi & Pengambilan Catering)",
        "organization": "Pesantren Walisongo",
        "discovery_date": "2026-11-24",
        "tech_stack": {
            "framework": "Laravel 13.x",
            "php_version": "^8.3",
            "database": "MySQL / MariaDB (sppc_walisongo)",
            "frontend": "Blade, Bootstrap, FontAwesome, html5-qrcode v2.3.8",
            "pdf_library": "barryvdh/laravel-dompdf ^3.1",
            "api_auth": "Laravel Sanctum ^4.0"
        },
        "target_users": ["Admin", "Petugas Catering", "Pengasuh"],
        "primary_process": "Pencatatan pengambilan jatah makan/lauk santri melalui scan QR per sesi makan (Pagi, Siang, Malam)."
    },
    "roles": [
        {"role": "admin", "redirect": "/dashboard", "description": "Akses menyeluruh: data santri, jadwal catering, scan QR, laporan."},
        {"role": "catering", "redirect": "/scan", "description": "Petugas dapur/catering: fokus verifikasi & scan QR santri saat pengambilan lauk."},
        {"role": "pengasuh", "redirect": "/laporan", "description": "Pengasuh pesantren: monitoring rekapitulasi presensi & cetak laporan PDF."}
    ],
    "pages": [
        {"id": "PAGE-01", "name": "Login", "route": "/login", "auth": "Guest", "features": ["Autentikasi email & password", "Redirect berbasis role"]},
        {"id": "PAGE-02", "name": "Dashboard Admin", "route": "/dashboard", "auth": "admin", "features": ["Statistik Total Santri", "Statistik Presensi Hari Ini", "Statistik QR Terdaftar"]},
        {"id": "PAGE-03", "name": "Dashboard Catering", "route": "/dashboard", "auth": "catering", "features": ["Tampilan ringkas untuk petugas catering"]},
        {"id": "PAGE-04", "name": "Dashboard Pengasuh", "route": "/dashboard", "auth": "pengasuh", "features": ["Statistik presensi hari ini"]},
        {"id": "PAGE-05", "name": "Manajemen Santri", "route": "/santri", "auth": "admin (seharusnya)", "features": ["List santri", "Tambah santri + auto QR", "Edit data", "Hapus santri (terproteksi riwayat)"]},
        {"id": "PAGE-06", "name": "Jadwal Catering", "route": "/catering", "auth": "admin (seharusnya)", "features": ["List jadwal", "Tambah jadwal (tanggal, sesi, menu)", "Edit jadwal", "Hapus jadwal (terproteksi riwayat)"]},
        {"id": "PAGE-07", "name": "Scan QR Pengambilan", "route": "/scan", "auth": "catering, admin", "features": ["Scanner kamera (html5-qrcode)", "Input manual kode QR", "Tabel riwayat realtime hari ini", "Validasi 1 kali per sesi"]},
        {"id": "PAGE-08", "name": "Laporan Rekapitulasi", "route": "/laporan", "auth": "pengasuh, admin", "features": ["Tabel rekapitulasi kehadiran", "Export PDF (DomPDF)"]}
    ],
    "api_endpoints": [
        {"endpoint": "/api/santri", "method": "GET, POST, PUT, DELETE", "auth": "None (Public - Unprotected)", "risk": "Critical"},
        {"endpoint": "/api/lauk", "method": "GET, POST, PUT, DELETE", "auth": "None (Public - Unprotected)", "risk": "Critical"},
        {"endpoint": "/api/pengambilan-lauk", "method": "GET, POST, PUT, DELETE", "auth": "None (Public - Unprotected)", "risk": "Critical"},
        {"endpoint": "/api/user", "method": "GET", "auth": "auth:sanctum", "risk": "Low"}
    ],
    "findings": [
        {"id": "F-001", "severity": "Critical", "title": "Schema Mismatch Database Migration vs Eloquent Model", "impact": "Migrate fresh gagal atau struktur kolom/tabel berbeda fatal."},
        {"id": "F-002", "severity": "Critical", "title": "API Endpoint Tidak Dilindungi Middleware Autentikasi", "impact": "Data santri dan transaksi dapat dimanipulasi tanpa login."},
        {"id": "F-003", "severity": "High", "title": "Tidak Ada Middleware Authorization (RBAC) pada Web Routes", "impact": "Role catering/pengasuh dapat mengakses CRUD santri dan jadwal catering."},
        {"id": "F-004", "severity": "High", "title": "PengambilanLaukApiController Memanggil Relasi Tidak Terdefinisi", "impact": "API crash dengan error Call to undefined relationship."},
        {"id": "F-005", "severity": "High", "title": "Model PesertaCatering Foreign Key Mismatch", "impact": "Relasi ke Santri selalu menghasilkan null."},
        {"id": "F-006", "severity": "High", "title": "LaporanController Query All Tanpa Pagination", "impact": "Risiko memory exhaustion & timeout saat data membesar."},
        {"id": "F-007", "severity": "Medium", "title": "Model & Tabel Laporan Merupakan Dead Code", "impact": "Tabel laporan tidak pernah diisi; controller membaca langsung dari presensi."},
        {"id": "F-008", "severity": "Medium", "title": "Tidak Ada Validasi Duplikasi Jadwal Catering", "impact": "Duplikat sesi pada tanggal yang sama menimbulkan inkonsistensi scan QR."},
        {"id": "F-009", "severity": "Medium", "title": "Tidak Ada Fitur Visualisasi / Print Kartu QR Santri", "impact": "Santri tidak memiliki kartu fisik untuk discan di scanner kamera."},
        {"id": "F-010", "severity": "Medium", "title": "Library html5-qrcode Bergantung pada CDN Eksternal", "impact": "Scanner kamera tidak berfungsi bila internet pesantren offline."}
    ],
    "recommendations": [
        {"id": "REC-001", "priority": "P0 (Immediate)", "target": "Database", "summary": "Sinkronkan migration dengan Model aktual melalui migration konsolidasi baru."},
        {"id": "REC-002", "priority": "P0 (Immediate)", "target": "Security", "summary": "Pasang middleware auth:sanctum pada semua API endpoint di routes/api.php."},
        {"id": "REC-003", "priority": "P1 (High)", "target": "Security", "summary": "Terapkan RoleMiddleware di routes/web.php untuk membatasi akses tiap role."},
        {"id": "REC-004", "priority": "P1 (High)", "target": "Backend", "summary": "Perbaiki relasi Eloquent di PengambilanLaukApiController & PesertaCatering."},
        {"id": "REC-005", "priority": "P1 (High)", "target": "Performance", "summary": "Tambahkan server-side pagination & filter tanggal/sesi di modul Laporan."},
        {"id": "REC-006", "priority": "P2 (Medium)", "target": "Feature", "summary": "Tambahkan fitur generator kartu QR (simplesoftwareio/simple-qrcode) untuk santri."},
        {"id": "REC-007", "priority": "P2 (Medium)", "target": "Reliability", "summary": "Bundel library html5-qrcode secara lokal via npm/Vite agar tahan offline."}
    ]
}

json_path = os.path.join(QA_DIR, "SPPC_Application_Discovery.json")
with open(json_path, "w", encoding="utf-8") as f:
    json.dump(discovery_data, f, indent=2, ensure_ascii=False)
print(f"Created: {json_path}")

# -------------------------------------------------------------
# 2. XLSX GENERATION (Openpyxl)
# -------------------------------------------------------------
wb = openpyxl.Workbook()

# Sheet 1: Feature Inventory
ws1 = wb.active
ws1.title = "Feature Inventory"

headers1 = ["Module ID", "Module Name", "Page ID", "Page Route", "Feature Name", "Actor / Role", "Input Elements", "Output / Feedback", "Business Rule", "Status Discovery"]
ws1.append(headers1)

features_data = [
    ["MOD-AUTH", "Autentikasi", "PAGE-01", "/login", "Login Akun", "Guest / User", "Email, Password", "Session token, redirect role", "Redirect dinamis: admin->/dashboard, catering->/scan, pengasuh->/laporan", "Verified"],
    ["MOD-AUTH", "Autentikasi", "PAGE-01", "/logout", "Logout Akun", "All Authenticated", "CSRF Token", "Redirect ke /login", "Invalidate session & regenerate CSRF", "Verified"],
    ["MOD-DASH", "Dashboard", "PAGE-02", "/dashboard", "Statistik Admin", "admin", "None", "Kartu total santri, presensi hari ini, qr", "Statistik dihitung real-time per query", "Verified"],
    ["MOD-DASH", "Dashboard", "PAGE-03", "/dashboard", "Tampilan Catering", "catering", "None", "Banner & navigasi cepat scan", "Akses cepat ke fungsi utama", "Verified"],
    ["MOD-DASH", "Dashboard", "PAGE-04", "/dashboard", "Tampilan Pengasuh", "pengasuh", "None", "Statistik presensi hari ini", "Monitoring konsumsi santri", "Verified"],
    ["MOD-SANTR", "Manajemen Santri", "PAGE-05", "/santri", "List Data Santri", "admin", "None", "Tabel santri & kode QR", "Urutan ID santri descending", "Verified"],
    ["MOD-SANTR", "Manajemen Santri", "PAGE-05", "/santri/create", "Tambah Santri", "admin", "Nama, Kelas, Asrama", "Flash message, redirect", "Otomatis generate UUID kode QR saat create", "Verified"],
    ["MOD-SANTR", "Manajemen Santri", "PAGE-05", "/santri/{id}/edit", "Ubah Data Santri", "admin", "Nama, Kelas, Asrama", "Flash message, update record", "Kode QR tetap dipertahankan", "Verified"],
    ["MOD-SANTR", "Manajemen Santri", "PAGE-05", "/santri/{id}", "Hapus Santri", "admin", "ID Santri", "Flash success / error", "Ditolak jika santri sudah memiliki riwayat presensi", "Verified"],
    ["MOD-CATR", "Jadwal Catering", "PAGE-06", "/catering", "List Jadwal", "admin", "None", "Tabel jadwal per tanggal & sesi", "Urut tanggal DESC, sesi ASC", "Verified"],
    ["MOD-CATR", "Jadwal Catering", "PAGE-06", "/catering/create", "Tambah Jadwal", "admin", "Tanggal, Sesi (Pagi/Siang/Malam), Menu", "Flash message, record catering", "Belum ada proteksi duplikat sesi+tanggal", "Verified"],
    ["MOD-CATR", "Jadwal Catering", "PAGE-06", "/catering/{id}/edit", "Ubah Jadwal", "admin", "Tanggal, Sesi, Menu", "Flash update", "Update metadata menu/sesi", "Verified"],
    ["MOD-CATR", "Jadwal Catering", "PAGE-06", "/catering/{id}", "Hapus Jadwal", "admin", "ID Catering", "Flash success / error", "Ditolak jika sudah ada presensi pada jadwal tsb", "Verified"],
    ["MOD-SCAN", "Scan QR & Presensi", "PAGE-07", "/scan", "Pemindaian Kamera", "catering, admin", "Stream Kamera, Pilihan Sesi", "Auto-submit form via JS", "html5-qrcode membaca string QR lalu submit", "Verified"],
    ["MOD-SCAN", "Scan QR & Presensi", "PAGE-07", "/scan", "Input Manual QR", "catering, admin", "Kode QR (teks), Pilihan Sesi", "Notifikasi & update riwayat", "1 santri hanya boleh 1 kali per sesi per hari", "Verified"],
    ["MOD-SCAN", "Scan QR & Presensi", "PAGE-07", "/scan", "Riwayat Hari Ini", "catering, admin", "None", "Tabel realtime pengambilan hari ini", "Menampilkan waktu, nama, asrama, badge sesi", "Verified"],
    ["MOD-REPO", "Laporan Rekap", "PAGE-08", "/laporan", "Tabel Presensi", "pengasuh, admin", "None", "Tabel seluruh riwayat presensi", "Query get() seluruh data tanpa limitasi", "Verified"],
    ["MOD-REPO", "Laporan Rekap", "PAGE-08", "/laporan/pdf", "Unduh PDF", "pengasuh, admin", "None", "File rekap-laporan-lauk.pdf", "Render view DomPDF secara direct download", "Verified"]
]

for row in features_data:
    ws1.append(row)

# Sheet 2: Findings & Risk
ws2 = wb.create_sheet(title="Findings & Issues")
headers2 = ["Finding ID", "Severity", "Category", "Affected Component", "Issue Title & Description", "Impact", "Recommended Solution", "Status"]
ws2.append(headers2)

findings_data = [
    ["F-001", "CRITICAL", "Database Schema", "database/migrations vs app/Models", "Inkonsistensi Nama Tabel & Kolom antara Migration dan Model Eloquent", "Migrate fresh akan merusak keselarasan aplikasi; query gagal.", "Buat migration konsolidasi baru yang selaras dengan Model.", "Open"],
    ["F-002", "CRITICAL", "Security", "routes/api.php", "Endpoint API Santri, Lauk, dan PengambilanLauk Terbuka Tanpa Autentikasi", "Siapapun dapat melihat, mengubah, atau menghapus data tanpa login.", "Tambahkan middleware('auth:sanctum') pada seluruh resource API.", "Open"],
    ["F-003", "HIGH", "Authorization", "routes/web.php", "Seluruh Web Route Hanya Dilindungi 'auth' Tanpa Pemeriksaan Role", "Role catering/pengasuh dapat membuka menu CRUD data santri & jadwal.", "Terapkan RoleMiddleware di web.php untuk isolasi role.", "Open"],
    ["F-004", "HIGH", "Code Defect", "PengambilanLaukApiController", "Pemanggilan relasi with(['santri','catering','user']) yang tidak terdefinisi", "API pengambilan-lauk crash fatal dengan 500 server error.", "Ubah relasi menjadi with(['presensi.santri','presensi.catering']).", "Open"],
    ["F-005", "HIGH", "Data Integrity", "app/Models/PesertaCatering.php", "Foreign key mismatch ('idSantri' vs 'id_santri') pada relasi santri()", "Relasi peserta->santri selalu mengembalikan null.", "Koreksi foreign key dan local key di method santri().", "Open"],
    ["F-006", "HIGH", "Performance", "app/Http/Controllers/LaporanController.php", "Query All Data Presensi Tanpa Pagination atau Filter Rentang Waktu", "Konsumsi memori server melonjak dan potensi timeout saat ribuan data.", "Gunakan paginate(50) dan form filter tanggal/sesi/asrama.", "Open"],
    ["F-007", "MEDIUM", "Architecture", "app/Models/Laporan.php", "Model dan Tabel Laporan Tidak Pernah Digunakan (Dead Code)", "Tabel kosong tidak terpakai, logika pelaporan langsung baca presensi.", "Refactor atau dokumentasikan bahwa tabel laporan adalah deprecated.", "Open"],
    ["F-008", "MEDIUM", "Business Logic", "app/Http/Controllers/CateringController.php", "Ketiadaan Validasi Unik untuk Kombinasi Tanggal dan Sesi Catering", "Bisa ada dua menu 'Pagi' di hari yang sama, scan QR membaca menu pertama.", "Tambahkan Rule::unique('catering')->where(...) di validasi store.", "Open"],
    ["F-009", "MEDIUM", "User Experience", "resources/views/santri/", "Tidak Tersedia Fitur Tampilan / Cetak Fisik Kartu QR Santri", "Santri tidak memiliki media QR fisik untuk dibawa ke tempat catering.", "Tambahkan library simple-qrcode dan buat halaman kartu santri printable.", "Open"],
    ["F-010", "MEDIUM", "Reliability", "resources/views/scan/index.blade.php", "Library html5-qrcode Dimuat Menggunakan CDN Eksternal", "Jika jaringan internet pesantren terputus, kamera scanner tidak bisa jalan.", "Pasang library html5-qrcode via npm atau asset lokal.", "Open"],
    ["F-011", "LOW", "Environment", ".env.example", "APP_DEBUG Diset True pada Template Environment", "Risiko kebocoran stack trace error di production jika lupa diganti.", "Set APP_DEBUG=false secara default di .env.example.", "Open"],
    ["F-012", "LOW", "Testing", "database/seeders/", "Ketiadaan Seeder Akun Default dan Data Sampel", "Setup awal sistem mengharuskan registrasi/insert manual ke DB.", "Buat DatabaseSeeder dengan akun admin, catering, dan pengasuh.", "Open"]
]

for row in findings_data:
    ws2.append(row)

# Sheet 3: QA Test Scenarios
ws3 = wb.create_sheet(title="QA Test Scenarios")
headers3 = ["Test Case ID", "Module", "Scenario Description", "Test Type", "Preconditions", "Test Steps", "Expected Result", "Priority"]
ws3.append(headers3)

scenarios_data = [
    ["TC-AUTH-01", "Auth", "Login dengan kredensial valid role Admin", "Positive", "Akun admin aktif di DB", "1. Input email & password admin\n2. Klik Login", "Berhasil login, redirect ke /dashboard", "Critical"],
    ["TC-AUTH-02", "Auth", "Login dengan kredensial valid role Catering", "Positive", "Akun catering aktif", "1. Input kredensial catering\n2. Klik Login", "Berhasil login, redirect langsung ke /scan", "Critical"],
    ["TC-AUTH-03", "Auth", "Login dengan password salah", "Negative", "User terdaftar", "1. Input email valid & password salah\n2. Klik Login", "Gagal login, muncul alert error validasi", "High"],
    ["TC-AUTH-04", "Auth", "Logout sesi aktif", "Positive", "User sudah login", "1. Klik tombol Logout", "Sesi hancur, redirect ke /login", "High"],
    ["TC-SANTR-01", "Santri", "Tambah santri baru lengkap", "Positive", "Login sebagai Admin", "1. Buka /santri/create\n2. Isi form lengkap\n3. Simpan", "Data santri tersimpan, QR UUID otomatis dibuat", "High"],
    ["TC-SANTR-02", "Santri", "Tambah santri form kosong", "Negative", "Login sebagai Admin", "1. Submit form kosong di /santri/create", "Validasi error pada nama, kelas, asrama", "Medium"],
    ["TC-SANTR-03", "Santri", "Hapus santri yang memiliki riwayat presensi", "Negative / Boundary", "Santri sudah pernah scan QR", "1. Klik hapus pada santri yang berelasi presensi", "Penghapusan ditolak dengan pesan peringatan", "High"],
    ["TC-CATR-01", "Catering", "Tambah jadwal catering sesi valid", "Positive", "Login sebagai Admin", "1. Buka /catering/create\n2. Pilih tanggal, sesi Siang, menu Soto", "Jadwal tersimpan di tabel", "High"],
    ["TC-CATR-02", "Catering", "Hapus jadwal catering yang sudah dipakai scan", "Negative", "Jadwal sudah memiliki relasi presensi", "1. Klik hapus pada jadwal terkait", "Penghapusan dicegah dengan alert error", "High"],
    ["TC-SCAN-01", "Scan QR", "Scan QR santri valid pada sesi catering yang sesuai", "Positive", "Jadwal hari ini ada, santri belum ambil", "1. Pilih sesi\n2. Masukkan QR terdaftar\n3. Submit", "Presensi & Pengambilan dicatat, muncul notifikasi sukses", "Critical"],
    ["TC-SCAN-02", "Scan QR", "Scan QR santri dua kali pada sesi yang sama", "Negative / Business Rule", "Santri sudah mengambil pada sesi tsb hari ini", "1. Masukkan QR yang sama pada sesi yang sama\n2. Submit", "Ditolak: 'Santri sudah mengambil lauk pada sesi ini hari ini!'", "Critical"],
    ["TC-SCAN-03", "Scan QR", "Scan QR yang tidak terdaftar di sistem", "Negative", "Katering hari ini tersedia", "1. Masukkan kode 'QR-INVALID-999'\n2. Submit", "Ditolak: 'Kode QR tidak terdaftar di dalam sistem!'", "High"],
    ["TC-SCAN-04", "Scan QR", "Scan QR saat jadwal catering hari ini belum dibuat", "Negative", "Belum ada jadwal sesi terkait hari ini", "1. Scan QR santri valid", "Ditolak: 'Data catering untuk sesi X hari ini belum tersedia!'", "High"],
    ["TC-REPO-01", "Laporan", "Buka halaman rekapitulasi presensi", "Positive", "Login sebagai Pengasuh/Admin", "1. Akses menu /laporan", "Tabel rekapitulasi data tampil lengkap", "Medium"],
    ["TC-REPO-02", "Laporan", "Export rekapitulasi ke file PDF", "Positive", "Terdapat data presensi", "1. Klik tombol Unduh PDF", "File rekap-laporan-lauk.pdf terdownload tanpa error", "High"],
    ["TC-SEC-01", "Security", "Akses /santri menggunakan akun role Catering", "Security / RBAC", "Login sebagai role Catering", "1. Akses langsung URL /santri di browser", "Seharusnya 403 Forbidden (saat ini masih terbuka)", "Critical"],
    ["TC-SEC-02", "Security", "Panggilan POST ke /api/santri tanpa auth header", "Security / API", "Unauthenticated request", "1. Kirim curl POST ke /api/santri", "Seharusnya 401 Unauthorized (saat ini masih 201 Created)", "Critical"]
]

for row in scenarios_data:
    ws3.append(row)

# Styling Helper
header_fill = PatternFill(start_color="1E293B", end_color="1E293B", fill_type="solid")
header_font = Font(name="Calibri", size=11, bold=True, color="FFFFFF")
border_thin = Border(left=Side(style='thin', color='CBD5E1'),
                     right=Side(style='thin', color='CBD5E1'),
                     top=Side(style='thin', color='CBD5E1'),
                     bottom=Side(style='thin', color='CBD5E1'))

for sheet in [ws1, ws2, ws3]:
    sheet.views.sheetView[0].showGridLines = True
    sheet.freeze_panes = "A2"
    # Format header
    for cell in sheet[1]:
        cell.fill = header_fill
        cell.font = header_font
        cell.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)
    # Set borders and widths
    for row in sheet.iter_rows(min_row=2):
        for cell in row:
            cell.border = border_thin
            cell.alignment = Alignment(vertical="center", wrap_text=True)
            if cell.value == "CRITICAL":
                cell.fill = PatternFill(start_color="FEE2E2", end_color="FEE2E2", fill_type="solid")
                cell.font = Font(name="Calibri", size=10, bold=True, color="991B1B")
            elif cell.value == "HIGH":
                cell.fill = PatternFill(start_color="FFEDD5", end_color="FFEDD5", fill_type="solid")
                cell.font = Font(name="Calibri", size=10, bold=True, color="9A3412")
            elif cell.value == "MEDIUM":
                cell.fill = PatternFill(start_color="FEF9C3", end_color="FEF9C3", fill_type="solid")
                cell.font = Font(name="Calibri", size=10, bold=True, color="854D0E")

    for col in sheet.columns:
        max_len = max(len(str(cell.value or '')) for cell in col)
        col_letter = get_column_letter(col[0].column)
        sheet.column_dimensions[col_letter].width = min(max(max_len + 4, 14), 45)

xlsx_path = os.path.join(QA_DIR, "SPPC_QA_Inventory_and_Test_Matrix.xlsx")
wb.save(xlsx_path)
print(f"Created: {xlsx_path}")

# -------------------------------------------------------------
# 3. DOCX GENERATION (Python-docx)
# -------------------------------------------------------------
doc = docx.Document()

# Set standard margins (1 inch)
for section in doc.sections:
    section.top_margin = Inches(1)
    section.bottom_margin = Inches(1)
    section.left_margin = Inches(1)
    section.right_margin = Inches(1)

# Helper functions for styling
def add_title(text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(22)
    run.font.bold = True
    run.font.color.rgb = RGBColor(15, 23, 42)
    p.paragraph_format.space_after = Pt(4)

def add_subtitle(text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(13)
    run.font.italic = True
    run.font.color.rgb = RGBColor(100, 116, 139)
    p.paragraph_format.space_after = Pt(24)

def add_heading_1(text):
    h = doc.add_heading(level=1)
    run = h.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(16)
    run.font.bold = True
    run.font.color.rgb = RGBColor(30, 41, 59)
    h.paragraph_format.space_before = Pt(16)
    h.paragraph_format.space_after = Pt(8)

def add_heading_2(text):
    h = doc.add_heading(level=2)
    run = h.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(13)
    run.font.bold = True
    run.font.color.rgb = RGBColor(51, 65, 85)
    h.paragraph_format.space_before = Pt(12)
    h.paragraph_format.space_after = Pt(6)

def add_p(text):
    p = doc.add_paragraph()
    run = p.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(10.5)
    run.font.color.rgb = RGBColor(30, 41, 59)
    p.paragraph_format.space_after = Pt(6)
    p.paragraph_format.line_spacing = 1.15
    return p

def add_bullet(bold_prefix, text):
    p = doc.add_paragraph(style='List Bullet')
    r1 = p.add_run(bold_prefix + ": ")
    r1.font.name = "Arial"
    r1.font.size = Pt(10.5)
    r1.font.bold = True
    r2 = p.add_run(text)
    r2.font.name = "Arial"
    r2.font.size = Pt(10.5)
    p.paragraph_format.space_after = Pt(4)

def style_table(table):
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    for i, row in enumerate(table.rows):
        for cell in row.cells:
            cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            tcPr = cell._tc.get_or_add_tcPr()
            if i == 0:
                # Header formatting
                shd = OxmlElement('w:shd')
                shd.set(qn('w:val'), 'clear')
                shd.set(qn('w:color'), 'auto')
                shd.set(qn('w:fill'), '1E293B')
                tcPr.append(shd)
                for p in cell.paragraphs:
                    for r in p.runs:
                        r.font.name = "Arial"
                        r.font.size = Pt(9.5)
                        r.font.bold = True
                        r.font.color.rgb = RGBColor(255, 255, 255)
            else:
                for p in cell.paragraphs:
                    for r in p.runs:
                        r.font.name = "Arial"
                        r.font.size = Pt(9)
                        r.font.color.rgb = RGBColor(30, 41, 59)

# Build Document Content
add_title("LAPORAN APPLICATION DISCOVERY & QA AUDIT")
add_subtitle("Sistem Presensi & Pengambilan Catering (SPPC) — Pesantren Walisongo")

# Ringkasan Eksekutif
add_heading_1("1. RINGKASAN EKSEKUTIF")
add_p("Dokumen ini memuat hasil analisis arsitektur teknis, audit kode, dan inventarisasi fitur pada aplikasi SPPC (Sistem Presensi & Pengambilan Catering) Pesantren Walisongo. Analisis dilaksanakan menggunakan metodologi Application Discovery and QA Documentation Architecture secara komprehensif terhadap seluruh file source code (Model, Controller, Migration, Views, Routes).")
add_p("Aplikasi dirancang dengan tujuan utama mengotomatisasi pencatatan distribusi makanan santri melalui pemindaian QR code unik per santri, membatasi hak ambil sebanyak satu kali per sesi (Pagi, Siang, Malam), serta menyajikan rekapitulasi kehadiran santri untuk pengasuh.")
add_p("Dari hasil audit, ditemukan total 12 temuan masalah, di mana 2 di antaranya berkategori CRITICAL (Inkonsistensi Skema Database Migration vs Model, dan API Endpoints publik tanpa otentikasi) yang memerlukan penanganan prioritas sebelum sistem dideploy ke lingkungan produksi.")

# Profil Sistem
add_heading_1("2. PROFIL SISTEM & TEKNOLOGI")
tech_table = doc.add_table(rows=1, cols=2)
tech_table.rows[0].cells[0].paragraphs[0].add_run("Komponen / Atribut")
tech_table.rows[0].cells[1].paragraphs[0].add_run("Spesifikasi Aktual")

tech_rows = [
    ("Nama Aplikasi", "SPPC (Sistem Presensi & Pengambilan Catering)"),
    ("Target Organisasi", "Pesantren Walisongo"),
    ("Framework Backend", "Laravel 13.x (PHP ^8.3)"),
    ("Database RDBMS", "MySQL / MariaDB (Database: sppc_walisongo)"),
    ("Template Engine", "Blade + Bootstrap CSS + FontAwesome Icons"),
    ("QR Code Scanner", "html5-qrcode v2.3.8 (Browser WebCam Scanning)"),
    ("PDF Engine", "barryvdh/laravel-dompdf ^3.1"),
    ("Autentikasi API", "Laravel Sanctum ^4.0"),
    ("Role Pengguna", "Admin, Catering (Dapur), Pengasuh")
]

for col1, col2 in tech_rows:
    row = tech_table.add_row()
    row.cells[0].paragraphs[0].add_run(col1)
    row.cells[1].paragraphs[0].add_run(col2)
style_table(tech_table)

# Alur Proses & Modul
add_heading_1("3. ARSITEKTUR MODUL & PROSES BISNIS")
add_p("Aplikasi SPPC memiliki 5 modul utama yang saling berinteraksi:")
add_bullet("Modul Autentikasi", "Menangani login dan logout pengguna dengan pengalihan otomatis berdasarkan role (Admin ke Dashboard, Catering ke Scan, Pengasuh ke Laporan).")
add_bullet("Modul Manajemen Santri", "Menyediakan operasi CRUD santri. Setiap penambahan santri secara otomatis memicu pembentukan string QR Code unik berbasis UUID.")
add_bullet("Modul Jadwal Catering", "Mengatur menu dan jadwal makanan per tanggal serta per sesi (Pagi, Siang, Malam).")
add_bullet("Modul Pemindaian QR (Scan)", "Inti operasional sistem: membaca QR santri lewat kamera atau input manual, memvalidasi jatah makan per sesi, dan menyimpan presensi serta pengambilan lauk.")
add_bullet("Modul Rekapitulasi & Laporan", "Menampilkan tabel riwayat konsumsi santri dan fasilitas download file laporan dalam format PDF.")

# Temuan Masalah
add_heading_1("4. AUDIT CODEBASE & TEMUAN MASALAH (FINDINGS)")
add_p("Berikut adalah rincian temuan yang diklasifikasikan berdasarkan tingkat keparahan risiko:")

findings_table = doc.add_table(rows=1, cols=4)
findings_table.rows[0].cells[0].paragraphs[0].add_run("ID")
findings_table.rows[0].cells[1].paragraphs[0].add_run("Tingkat")
findings_table.rows[0].cells[2].paragraphs[0].add_run("Komponen")
findings_table.rows[0].cells[3].paragraphs[0].add_run("Ringkasan Masalah & Dampak")

f_summary = [
    ("F-001", "CRITICAL", "Database Migrations", "Inkonsistensi Skema: Tabel 'santris' vs 'santri', 'caterings' vs 'catering', dan kolom berbeda antara migration dengan Model Eloquent."),
    ("F-002", "CRITICAL", "API Routes", "API /api/santri, /api/lauk, dan /api/pengambilan-lauk terbuka tanpa middleware auth; siapa saja bisa manipulasi data."),
    ("F-003", "HIGH", "Web Routes", "Ketiadaan Role-Based Access Control (RBAC); role catering dapat mengakses menu CRUD santri dan jadwal catering."),
    ("F-004", "HIGH", "API Controller", "PengambilanLaukApiController memanggil relasi 'santri', 'catering', 'user' yang tidak ada di Model PengambilanLauk."),
    ("F-005", "HIGH", "Model Peserta", "Foreign key mismatch pada model PesertaCatering menyebabkan relasi ke Santri selalu null."),
    ("F-006", "HIGH", "Laporan Module", "LaporanController memuat semua record tanpa pagination; berisiko crash memory saat data ribuan."),
    ("F-007", "MEDIUM", "Model Laporan", "Model Laporan (tabel laporan) tidak pernah digunakan oleh controller manapun (Dead Code)."),
    ("F-008", "MEDIUM", "Catering Module", "Tidak ada validasi duplikat sesi+tanggal pada jadwal catering."),
    ("F-009", "MEDIUM", "Santri Module", "Belum ada antarmuka untuk mencetak kartu fisik QR code bagi santri."),
    ("F-010", "MEDIUM", "Scan Module", "Library html5-qrcode bergantung pada CDN eksternal sehingga tidak tahan kondisi offline.")
]

for fid, sev, comp, desc in f_summary:
    row = findings_table.add_row()
    row.cells[0].paragraphs[0].add_run(fid)
    row.cells[1].paragraphs[0].add_run(sev)
    row.cells[2].paragraphs[0].add_run(comp)
    row.cells[3].paragraphs[0].add_run(desc)
style_table(findings_table)

# Rekomendasi Perbaikan
add_heading_1("5. REKOMENDASI PERBAIKAN & ROADMAP IMPLEMENTASI")
add_bullet("Sinkronisasi Skema Database (P0)", "Buat migration konsolidasi baru yang mencocokkan skema database fisik dengan Model Eloquent saat ini, termasuk index foreign key.")
add_bullet("Proteksi Endpoint API (P0)", "Bungkus resource routes di routes/api.php dengan middleware('auth:sanctum') dan siapkan endpoint auth token.")
add_bullet("Penerapan Role Middleware (P1)", "Tambahkan middleware verifikasi role pada routes/web.php untuk memastikan role catering hanya bisa mengakses scan dan role pengasuh hanya laporan.")
add_bullet("Pagination & Filter Laporan (P1)", "Gunakan server-side pagination (paginate(50)) dan tambahkan form filter rentang tanggal, sesi makan, dan asrama.")
add_bullet("Fitur Cetak Kartu QR Santri (P2)", "Integrasikan package QR generator lokal (seperti simplesoftwareio/simple-qrcode) dan rancang template kartu santri A4 siap cetak.")
add_bullet("Penyimpanan Asset Scanner Lokal (P2)", "Unduh script html5-qrcode ke public/js/ atau bundle melalui Vite agar sistem presensi dapur tetap berjalan saat internet padam.")

# Rencana Pengujian QA
add_heading_1("6. STRATEGI & KESIAPAN PENGUJIAN (QA READINESS)")
add_p("Seluruh skenario pengujian telah dipetakan ke dalam file spreadsheet SPPC_QA_Inventory_and_Test_Matrix.xlsx yang mencakup skenario fungsional positif, skenario batas (boundary), penanganan kesalahan (negative), dan pengujian keamanan akses.")
add_p("Sebelum melangkah ke eksekusi QA Automation penuh menggunakan skill website-qa-automation-architect, tim pengembang disarankan untuk menuntaskan perbaikan P0 dan P1 agar seluruh test script Playwright dapat dieksekusi tanpa gangguan broken access control maupun ketidakkonsistenan database.")

docx_path = os.path.join(QA_DIR, "SPPC_Application_Discovery_Report.docx")
doc.save(docx_path)
print(f"Created: {docx_path}")

print("All documentation generated successfully!")
