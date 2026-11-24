# QA Skills — SPPC Project

Folder ini berisi skill files untuk keperluan **Application Discovery** dan **QA Automation** pada proyek SPPC.

## Struktur Folder

```
QA/
├── README.md                                           ← Dokumen ini
├── skill-application-discovery-qa-documentation.md    ← Source skill (original)
├── skill-QA Engineer.md                               ← Source skill (original)
└── skills/
    ├── application-discovery-qa-documentation/
    │   └── SKILL.md                                   ← Skill: Discovery & QA Documentation
    └── website-qa-automation-architect/
        └── SKILL.md                                   ← Skill: QA Automation Architect
```

## Skills yang Tersedia

### 1. `application-discovery-qa-documentation`
**Deskripsi:** Adaptive Application Discovery, System Analysis, Reverse Engineering, dan QA Documentation Architect.

Mempelajari aplikasi web berdasarkan karakteristik proyek yang sebenarnya, mengidentifikasi halaman, fitur, modul, user journey, role, business rules, dan application flow secara kondisional.

**Output:**
- DOCX dokumentasi teknis
- XLSX inventaris fitur & matriks
- Diagram (Mermaid)
- Screenshot evidence
- JSON data discovery terstruktur

**Gunakan ketika:** Mendokumentasikan aplikasi yang sudah dibangun atau sebelum menjalankan QA Automation.

---

### 2. `website-qa-automation-architect`
**Deskripsi:** Adaptive Website QA Automation Architect untuk melakukan automated testing terhadap aplikasi web.

Menggunakan Claude AI, Playwright, dan Remote Browser untuk menguji fitur aktual, user journey, business process, role-based access, UI, integrasi, serta validasi data.

**Output:**
- `Website_QA_Testing_Report.docx`
- `Website_User_Tutorial.docx`
- Folder screenshots PNG
- Test results JSON
- Bug report

**Gunakan ketika:** Menjalankan QA Automation setelah Application Discovery selesai.

---

## Cara Penggunaan

Skill ini sudah didaftarkan di `.agents/skills/` sehingga dapat langsung diaktifkan oleh Antigravity Agent.

Untuk menggunakan skill, cukup minta agent:
> *"Gunakan skill application-discovery-qa-documentation untuk mendokumentasikan aplikasi SPPC"*

atau:
> *"Jalankan QA automation menggunakan skill website-qa-automation-architect"*

---

## Urutan Penggunaan yang Direkomendasikan

```
1. application-discovery-qa-documentation
   → Pelajari & dokumentasikan aplikasi
   → Hasilkan Feature Inventory, Functional Spec, QA Requirements

2. website-qa-automation-architect
   → Gunakan output dari tahap 1 sebagai baseline
   → Jalankan automated testing
   → Hasilkan QA Report & User Tutorial
```

---

*Dibuat otomatis oleh Antigravity Agent — 2026-11-24*
