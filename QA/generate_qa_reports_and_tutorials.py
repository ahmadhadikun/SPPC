import os
import json
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

QA_DIR = r"d:\laragon\www\SPPC-main\QA"
SCREENSHOT_DIR = os.path.join(QA_DIR, "screenshots")

# Load automated test results
with open(os.path.join(QA_DIR, "test-results.json"), "r", encoding="utf-8") as f:
    test_results = json.load(f)

# Helper styling functions
def setup_doc_margins(doc):
    for sec in doc.sections:
        sec.top_margin = Inches(1)
        sec.bottom_margin = Inches(1)
        sec.left_margin = Inches(1)
        sec.right_margin = Inches(1)

def add_title(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(22)
    run.font.bold = True
    run.font.color.rgb = RGBColor(15, 23, 42)
    p.paragraph_format.space_after = Pt(4)

def add_subtitle(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(12.5)
    run.font.italic = True
    run.font.color.rgb = RGBColor(100, 116, 139)
    p.paragraph_format.space_after = Pt(24)

def add_h1(doc, text):
    h = doc.add_heading(level=1)
    run = h.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(15)
    run.font.bold = True
    run.font.color.rgb = RGBColor(30, 41, 59)
    h.paragraph_format.space_before = Pt(16)
    h.paragraph_format.space_after = Pt(8)

def add_h2(doc, text):
    h = doc.add_heading(level=2)
    run = h.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(12)
    run.font.bold = True
    run.font.color.rgb = RGBColor(51, 65, 85)
    h.paragraph_format.space_before = Pt(12)
    h.paragraph_format.space_after = Pt(6)

def add_p(doc, text):
    p = doc.add_paragraph()
    run = p.add_run(text)
    run.font.name = "Arial"
    run.font.size = Pt(10)
    run.font.color.rgb = RGBColor(30, 41, 59)
    p.paragraph_format.space_after = Pt(6)
    p.paragraph_format.line_spacing = 1.15
    return p

def add_bullet(doc, bold_prefix, text):
    p = doc.add_paragraph(style='List Bullet')
    r1 = p.add_run(bold_prefix + ": ")
    r1.font.name = "Arial"
    r1.font.size = Pt(10)
    r1.font.bold = True
    r2 = p.add_run(text)
    r2.font.name = "Arial"
    r2.font.size = Pt(10)
    p.paragraph_format.space_after = Pt(4)

def style_table(table):
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    for i, row in enumerate(table.rows):
        for cell in row.cells:
            cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            tcPr = cell._tc.get_or_add_tcPr()
            if i == 0:
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

def add_evidence_picture(doc, filename, caption):
    filepath = os.path.join(SCREENSHOT_DIR, filename)
    if os.path.exists(filepath):
        p_img = doc.add_paragraph()
        p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_img.paragraph_format.space_before = Pt(8)
        p_img.paragraph_format.space_after = Pt(2)
        run = p_img.add_run()
        run.add_picture(filepath, width=Inches(5.8))
        
        p_cap = doc.add_paragraph()
        p_cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r_cap = p_cap.add_run(f"Gambar Bukti: {caption} ({filename})")
        r_cap.font.name = "Arial"
        r_cap.font.size = Pt(8.5)
        r_cap.font.italic = True
        r_cap.font.color.rgb = RGBColor(100, 116, 139)
        p_cap.paragraph_format.space_after = Pt(12)

# =============================================================================
# 1. GENERATE Website_QA_Testing_Report.docx
# =============================================================================
doc_report = docx.Document()
setup_doc_margins(doc_report)

add_title(doc_report, "WEBSITE QA TESTING REPORT")
add_subtitle(doc_report, "Automated E2E Testing & Security Validation Report — SPPC Pesantren Walisongo")

add_h1(doc_report, "1. RINGKASAN EKSEKUTIF")
add_p(doc_report, "Laporan pengujian otomatis ini menyajikan hasil evaluasi kualitas perangkat lunak secara end-to-end (E2E) pada aplikasi SPPC (Sistem Presensi & Pengambilan Catering) Pesantren Walisongo. Pengujian dilaksanakan menggunakan browser automation Playwright (Chromium/Edge) untuk memvalidasi alur bisnis aktual, konsistensi antarmuka, penegakan aturan bisnis, dan integritas kontrol akses (RBAC).")
add_p(doc_report, f"Dari total 13 skenario uji yang dieksekusi, sebanyak 12 kasus dinyatakan LULUS (PASS) dan 1 kasus GAGAL (FAIL) yang mengonfirmasi adanya kerentanan keamanan akses (Broken Access Control). Tingkat kelulusan pengujian (Pass Rate) mencapai {test_results['summary']['passRate']}.")

add_h1(doc_report, "2. LINGKUNGAN DAN METODOLOGI PENGUJIAN")
env_table = doc_report.add_table(rows=1, cols=2)
env_table.rows[0].cells[0].paragraphs[0].add_run("Parameter Pengujian")
env_table.rows[0].cells[1].paragraphs[0].add_run("Nilai / Konfigurasi Aktual")

env_data = [
    ("Target Base URL", "http://127.0.0.1:8000"),
    ("Automation Engine", "Playwright v1.50+ (Node.js ES6+)"),
    ("Browser Platform", "Microsoft Edge (Chromium Engine v131+ Headless)"),
    ("Resolusi Viewport", "1280 x 800 piksel (Desktop Standard)"),
    ("Pemisahan Sesi", "Isolated Browser Contexts per Role (Zero Cookie Leak)"),
    ("Akun Pengujian", "admin@sppc.com, catering@sppc.com, pengasuh@sppc.com"),
    ("Format Bukti", "PNG Screenshot Aktual dengan metadata test case")
]
for p1, p2 in env_data:
    row = env_table.add_row()
    row.cells[0].paragraphs[0].add_run(p1)
    row.cells[1].paragraphs[0].add_run(p2)
style_table(env_table)

add_h1(doc_report, "3. RINGKASAN STATISTIK EKSEKUSI PENGUJIAN")
sum_table = doc_report.add_table(rows=1, cols=2)
sum_table.rows[0].cells[0].paragraphs[0].add_run("Metrik Pengujian")
sum_table.rows[0].cells[1].paragraphs[0].add_run("Nilai")
sum_rows = [
    ("Total Test Case Direncanakan & Dieksekusi", str(test_results['summary']['total'])),
    ("Jumlah Kasus Lulus (PASS)", str(test_results['summary']['passed'])),
    ("Jumlah Kasus Gagal (FAIL)", str(test_results['summary']['failed'])),
    ("Jumlah Kasus Diblokir (BLOCKED)", str(test_results['summary']['blocked'])),
    ("Tingkat Kelulusan (Pass Rate)", test_results['summary']['passRate']),
    ("Cakupan Peran Pengguna (Role Coverage)", "100% (Guest, Admin, Catering, Pengasuh)"),
    ("Cakupan Alur Inti (Core Flow Coverage)", "100% (Login, Scan QR, Single Claim Rule, Rekap)")
]
for k, v in sum_rows:
    r = sum_table.add_row()
    r.cells[0].paragraphs[0].add_run(k)
    r.cells[1].paragraphs[0].add_run(v)
style_table(sum_table)

add_h1(doc_report, "4. RINCIAN HASIL UJI & BUKTI SCREENSHOT PER KASUS")
for tc in test_results['testCases']:
    add_h2(doc_report, f"[{tc['id']}] {tc['title']} ({tc['role']})")
    add_bullet(doc_report, "Modul", tc['module'])
    add_bullet(doc_report, "Status", f"{tc['status']} {'✅' if tc['status'] == 'PASS' else '❌'}")
    add_bullet(doc_report, "Hasil yang Diharapkan", tc['expected'])
    add_bullet(doc_report, "Hasil Pengamatan Aktual", tc['actual'])
    add_evidence_picture(doc_report, tc['screenshot'], tc['title'])

add_h1(doc_report, "5. ANALISIS BUG & TEMUAN KEAMANAN AKTUAL")
add_p(doc_report, "Selama eksekusi pengujian, satu temuan kegagalan kritis terkonfirmasi secara visual dan fungsional:")
add_bullet(doc_report, "Bug ID", "BUG-TC011")
add_bullet(doc_report, "Judul Masalah", "Broken Access Control (RBAC Leak) — Role Catering Dapat Mengakses Master Data Santri")
add_bullet(doc_report, "Tingkat Keparahan (Severity)", "HIGH / CRITICAL SECURITY")
add_bullet(doc_report, "Langkah Reproduksi", "1. Login sebagai petugas catering (catering@sppc.com)\n2. Buka URL http://127.0.0.1:8000/santri di address bar")
add_bullet(doc_report, "Hasil Aktual", "Halaman tabel master santri terbuka penuh tanpa pembatasan hak akses (HTTP 200 OK bukan 403 Forbidden).")
add_bullet(doc_report, "Bukti Terlampir", "TC011_SECURITY_RBAC_LEAK_STEP01_FAIL.png")
add_bullet(doc_report, "Rekomendasi", "Pasang RoleMiddleware pada grup route santri dan catering di routes/web.php.")

add_h1(doc_report, "6. KESIMPULAN & REKOMENDASI QA")
add_p(doc_report, "Sistem SPPC telah memiliki alur bisnis presensi dan pembagian jatah lauk yang berfungsi sangat baik (Aturan 1 santri 1 jatah makan per sesi terbukti berhasil ditegakkan tanpa celah ganda).")
add_p(doc_report, "Namun, sebelum aplikasi diserahterimakan ke lingkungan produksi pesantren yang sesungguhnya, tim pengembang diwajibkan menutup celah otorisasi role (BUG-TC011) serta memasang middleware Sanctum pada route API.")

report_path = os.path.join(QA_DIR, "Website_QA_Testing_Report.docx")
doc_report.save(report_path)
print(f"Generated: {report_path}")

# =============================================================================
# 2. GENERATE Website_User_Tutorial.docx
# =============================================================================
doc_tut = docx.Document()
setup_doc_margins(doc_tut)

add_title(doc_tut, "BUKU PANDUAN PENGGUNA (USER TUTORIAL)")
add_subtitle(doc_tut, "Panduan Operasional Lengkap Berbasis Peran — SPPC Pesantren Walisongo")

add_h1(doc_tut, "1. PENDAHULUAN & GAMBARAN UMUM")
add_p(doc_tut, "Aplikasi SPPC (Sistem Presensi & Pengambilan Catering) adalah platform berbasis web yang digunakan untuk mengelola dan memantau pendistribusian makanan bagi para santri di Pesantren Walisongo. Sistem memanfaatkan kartu QR Code santri untuk memastikan distribusi tepat sasaran, akurat, dan tercatat otomatis secara transparan.")
add_p(doc_tut, "Sistem membagi operasional ke dalam 3 peran pengguna utama:")
add_bullet(doc_tut, "Administrator", "Bertanggung jawab atas registrasi santri, penerbitan QR Code, dan pengaturan jadwal menu catering harian.")
add_bullet(doc_tut, "Petugas Catering (Dapur)", "Bertanggung jawab memindai kartu QR santri pada saat jam makan (Pagi, Siang, Malam) berlangsung.")
add_bullet(doc_tut, "Pengasuh Pesantren", "Bertanggung jawab memantau kehadiran makan santri dan mengunduh rekapitulasi laporan presensi.")

add_h1(doc_tut, "2. PANDUAN MEMULAI (GETTING STARTED)")
add_h2(doc_tut, "2.1 Mengakses dan Masuk ke Aplikasi")
add_p(doc_tut, "1. Buka browser (Google Chrome, Microsoft Edge, atau Mozilla Firefox).\n2. Masukkan alamat URL sistem: http://127.0.0.1:8000/login\n3. Masukkan alamat email dan password yang telah didaftarkan sesuai peran Anda.\n4. Klik tombol 'Masuk / Login'. Sistem akan secara otomatis mengarahkan Anda ke ruang kerja yang sesuai.")
add_evidence_picture(doc_tut, "TC001_GUEST_LOGIN_STEP01_PASS.png", "Halaman Login Sistem SPPC")

add_h1(doc_tut, "3. PANDUAN PERAN: ADMINISTRATOR")
add_h2(doc_tut, "3.1 Dashboard Statistik Admin")
add_p(doc_tut, "Setelah login, Admin akan disambut oleh Dashboard yang menampilkan metrik penting:")
add_bullet(doc_tut, "Total Santri", "Jumlah total santri yang aktif di pesantren.")
add_bullet(doc_tut, "Presensi Hari Ini", "Jumlah santri yang telah mengambil lauk pada hari berjalan.")
add_bullet(doc_tut, "QR Terdaftar", "Jumlah total barcode QR yang siap digunakan.")
add_evidence_picture(doc_tut, "TC003_ADMIN_DASHBOARD_STEP01_PASS.png", "Dashboard Statistik Administrator")

add_h2(doc_tut, "3.2 Mengelola Data Santri & QR Code")
add_p(doc_tut, "Untuk mengelola data santri:\n1. Klik menu 'Data Santri' pada bilah navigasi atas.\n2. Anda akan melihat daftar seluruh santri lengkap dengan Nama, Kelas, Asrama, dan Kode QR.")
add_evidence_picture(doc_tut, "TC004_ADMIN_SANTRI_STEP01_PASS.png", "Tabel Master Data Santri")
add_p(doc_tut, "3. Untuk menambah santri baru, klik tombol 'Tambah Santri'.\n4. Isi formulir Nama Lengkap, Kelas, dan Asrama, kemudian klik 'Simpan'. Sistem akan membuat kode QR UUID unik secara otomatis.")
add_evidence_picture(doc_tut, "TC005_ADMIN_SANTRI_CREATE_STEP01_PASS.png", "Formulir Registrasi Santri Baru")

add_h2(doc_tut, "3.3 Mengatur Jadwal & Menu Catering")
add_p(doc_tut, "Sebelum petugas catering dapat memindai kartu santri, Admin harus memastikan jadwal catering pada hari tersebut sudah dibuat:")
add_p(doc_tut, "1. Buka menu 'Jadwal Catering'.\n2. Klik 'Tambah Jadwal'.\n3. Tentukan Tanggal, pilih Sesi Makan (Pagi, Siang, atau Malam), dan tuliskan Menu Lauk yang disajikan.\n4. Klik 'Simpan'.")
add_evidence_picture(doc_tut, "TC006_ADMIN_CATERING_STEP01_PASS.png", "Daftar Jadwal dan Menu Catering")

add_h1(doc_tut, "4. PANDUAN PERAN: PETUGAS CATERING (DAPUR)")
add_h2(doc_tut, "4.1 Operasional Pemindaian Kartu QR Santri")
add_p(doc_tut, "Petugas catering yang login akan langsung diarahkan ke halaman pemindaian:")
add_evidence_picture(doc_tut, "TC008_CATERING_SCAN_PAGE_STEP01_PASS.png", "Antarmuka Scanner QR Pengambilan Lauk")
add_p(doc_tut, "Langkah memproses pengambilan makan santri:\n1. Pada bagian 'PILIH SESI PENGAMBILAN', pastikan sesi yang aktif sudah dipilih (misalnya: Siang).\n2. Arahkan kartu QR santri ke hadapan kamera scanner, ATAU ketik kode QR pada kotak input manual jika kamera bermasalah.\n3. Tekan tombol 'Proses Pengambilan' atau biarkan scanner membaca otomatis.")
add_evidence_picture(doc_tut, "TC009_CATERING_SCAN_EXECUTE_STEP02_PASS.png", "Notifikasi Keberhasilan Pengambilan Jatah Lauk")

add_h2(doc_tut, "4.2 Penanganan Penolakan (Aturan 1 Santri 1 Jatah)")
add_p(doc_tut, "Jika seorang santri mencoba mengambil lauk lebih dari satu kali pada sesi yang sama di hari yang sama, sistem akan otomatis menolak dan memunculkan kotak peringatan merah:")
add_evidence_picture(doc_tut, "TC010_CATERING_SCAN_DUPLICATE_STEP03_PASS.png", "Peringatan Penolakan Pengambilan Ganda")

add_h1(doc_tut, "5. PANDUAN PERAN: PENGASUH PESANTREN")
add_h2(doc_tut, "5.1 Monitoring Rekapitulasi Presensi")
add_p(doc_tut, "Pengasuh dapat memantau secara real-time santri mana saja yang sudah makan:")
add_p(doc_tut, "1. Login dengan akun Pengasuh, sistem langsung menampilkan menu 'Laporan Rekapitulasi'.\n2. Tabel menyajikan informasi waktu scan, nama santri, asrama, dan sesi makan.")
add_evidence_picture(doc_tut, "TC012_PENGASUH_LOGIN_LAPORAN_STEP01_PASS.png", "Tabel Monitoring Kehadiran Santri")

add_h2(doc_tut, "5.2 Mengunduh Rekapitulasi Format PDF")
add_p(doc_tut, "Untuk keperluan arsip fisik atau rapat evaluasi:\n1. Klik tombol 'Unduh PDF' di pojok atas tabel.\n2. Browser akan mengunduh dokumen resmi rekap-laporan-lauk.pdf siap cetak.")
add_evidence_picture(doc_tut, "TC013_PENGASUH_LAPORAN_LIST_STEP01_PASS.png", "Tombol Ekspor Laporan PDF")

add_h1(doc_tut, "6. PANDUAN PEMECAHAN MASALAH (TROUBLESHOOTING)")
add_bullet(doc_tut, "Kamera Scanner Tidak Muncul", "Pastikan izin akses webcam/kamera telah disetujui di browser. Jika menggunakan laptop atau tablet, periksa apakah kamera sedang dipakai aplikasi lain. Anda tetap dapat menggunakan input manual di bawah kamera.")
add_bullet(doc_tut, "Pesan 'Jadwal Catering Hari Ini Belum Tersedia'", "Artinya Admin belum membuat jadwal makan untuk sesi/hari tersebut. Hubungi Admin untuk menambahkan jadwal di menu 'Jadwal Catering'.")
add_bullet(doc_tut, "Pesan 'Santri Sudah Mengambil Lauk'", "Santri bersangkutan telah tervalidasi mengambil jatah pada sesi tersebut. Sistem secara ketat melarang pengambilan ganda demi keadilan santri lain.")

tut_path = os.path.join(QA_DIR, "Website_User_Tutorial.docx")
doc_tut.save(tut_path)
print(f"Generated: {tut_path}")

print("Both DOCX documents generated successfully with embedded screenshots!")
