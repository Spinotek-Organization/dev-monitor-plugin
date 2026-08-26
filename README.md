# ⚡ Spinotek Dev Monitor Plugin for Laravel (Vue 3 SPA Edition)

<p align="center">
  <strong>Plugin mandiri untuk Laravel berbasis Vue 3 Single Page Application (SPA): Pemantauan Task Development & Pencatatan Riwayat Versi (Changelog JSON) yang super responsif tanpa reload halaman, dilengkapi integrasi Auto-Tagging GitHub Actions.</strong>
</p>

---

## 🚀 Fitur Unggulan

- ⚡ **Vue 3 SPA (Single Page Application)**: Navigasi antar halaman (*Task Monitoring* ↔ *Version Logs*) berjalan instan tanpa refresh/reload halaman sama sekali.
- 📋 **CRUD Task Monitoring Interaktif**:
  - Filter pencarian, status, dan prioritas bekerja secara realtime (*instant filtering*).
  - Penghitungan kartu metrik (*Total, Pending, In Progress, Completed*) terupdate secara otomatis dan reaktif.
  - Pengubahan status inline langsung memicu background API sync + notifikasi toast.
- ✏️ **Modal-based Form**: Form tambah task, edit task, hapus task (konfirmasi kustom), dan catat versi baru tampil dalam dialog modal yang cepat dan halus.
- 🚀 **Version Logs (File-based JSON)**: Pencatatan riwayat rilis & changelog versi yang disimpan dalam format file `data/version_logs.json`.
- 🤖 **AI Agent & CI REST API**: Menyediakan endpoint API lengkap (`/api/monitoring/tasks` & `/api/monitoring/version-logs`) untuk memungkinkan AI Agent (seperti Antigravity, Cursor) atau script CI/CD berinteraksi secara otomatis.
- 🔔 **Custom Toast Notifications**: Notifikasi mengambang (*floating toast*) modern dengan animasi mulus dan auto-dismiss.
- 🏷️ **GitHub Actions Auto-Tag & Release**: Otomatis membuat Git Tag & GitHub Release resmi setiap kali ada commit/push ke branch `main`.
- 📦 **Zero-Config Build**: Berjalan langsung secara mandiri tanpa memerlukan setup Vite/npm tambahan di proyek Laravel utama.

---

## 📁 Struktur Plugin

```text
dev-monitor-plugin/
├── .github/
│   └── workflows/
│       └── auto-tag.yml                             # Auto Tagging & Release Workflow
├── data/
│   └── version_logs.json                            # Database JSON Riwayat Versi
├── database/
│   └── migrations/
│       └── 2026_08_24_000000_create_monitoring_tasks_table.php
├── resources/
│   └── views/
│       └── app.blade.php                            # Vue 3 SPA Dashboard & Components
├── routes/
│   ├── api.php                                      # Endpoint API REST JSON
│   └── web.php                                      # Rute Web SPA
├── src/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── TaskMonitoringController.php
│   │       └── VersionLogController.php
│   ├── Models/
│   │   └── MonitoringTask.php
│   ├── Services/
│   │   └── VersionLogService.php
│   └── MonitoringServiceProvider.php                # Service Provider (Auto-Discovery)
├── composer.json
├── .gitignore
└── README.md
```

---

## 📦 Panduan Instalasi ke Proyek Laravel

### Opsi A: Instalasi via GitHub Repository (Remote Git)

1. Tambahkan repository ke `composer.json` proyek Laravel Anda:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Spinotek-Organization/dev-monitor-plugin.git"
    }
],
"require": {
    "spinotek/dev-monitor-plugin": "dev-main"
}
```

2. Jalankan perintah:
```bash
composer update spinotek/dev-monitor-plugin
php artisan migrate
```

---

### Opsi B: Instalasi Lokal (*Local Path Repository*)

1. Simpan folder plugin di dalam direktori `packages/spinotek/task-monitoring`.
2. Tambahkan konfigurasi path repository ke `composer.json` proyek Laravel utama:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/spinotek/task-monitoring",
        "options": {
            "symlink": true
        }
    }
],
"require": {
    "spinotek/dev-monitor-plugin": "@dev"
}
```

3. Jalankan:
```bash
composer update spinotek/dev-monitor-plugin
php artisan migrate
```

---

## 🌐 Rute & Akses Halaman

Setelah instalasi dan server dijalankan (`php artisan serve`):

| Halaman / Fitur | URL | Deskripsi |
| :--- | :--- | :--- |
| **Dashboard (SPA)** | `http://localhost:8000/monitoring/tasks` | Tampilan SPA Vue 3 untuk Task Monitoring |
| **Version Logs (SPA)** | `http://localhost:8000/monitoring/version-logs` | Tampilan SPA Vue 3 untuk Version Changelog |
| **API Tasks** | `GET /api/monitoring/tasks` | Mengambil data tasks & stats realtime |
| **API Version Logs** | `GET /api/monitoring/version-logs` | Mengambil riwayat versi JSON |

---

## 🤖 Integrasi AI Agent & Script Otomasi

AI Agent atau CI script dapat menambahkan catatan changelog secara otomatis melalui HTTP POST:

```bash
curl -X POST http://localhost:8000/api/monitoring/version-logs \
  -H "Content-Type: application/json" \
  -d '{
    "version": "v1.2.0",
    "author": "Antigravity AI Agent",
    "type": "feature",
    "changes": [
      "Migrasi antarmuka view ke Vue 3 SPA",
      "Navigasi instan tanpa refresh halaman"
    ]
  }'
```

---

## 🏷️ GitHub Actions Auto-Tag & Release

Workflow telah terkonfigurasi di `.github/workflows/auto-tag.yml`.

- Setiap `git push` atau merge ke branch `main`, GitHub Action akan otomatis membuat **Git Tag** dan **GitHub Release** baru.
- **Konvensi Pesan Commit**:
  - `git commit -m "perbaikan bug task"` ➡️ Menghasilkan rilis **Patch** (`v1.0.0` ➡️ `v1.0.1`)
  - `git commit -m "fitur baru monitoring #minor"` ➡️ Menghasilkan rilis **Minor** (`v1.0.1` ➡️ `v1.1.0`)
  - `git commit -m "perubahan arsitektur besar #major"` ➡️ Menghasilkan rilis **Major** (`v1.1.0` ➡️ `v2.0.0`)

---

## 📄 Lisensi
Plugin ini dilisensikan di bawah [MIT License](LICENSE).
Dikembangkan oleh **Spinotek Team**.
