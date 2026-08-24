# ⚡ Spinotek Dev Monitor Plugin for Laravel

<p align="center">
  <strong>Plugin mandiri untuk Laravel: Pemantauan Task Development & Pencatatan Riwayat Versi (Changelog JSON) dengan integrasi Auto-Tagging GitHub Actions.</strong>
</p>

---

## 🚀 Fitur Utama

- 📋 **CRUD Task Monitoring**: Pantau progres pengerjaan task, prioritas (*High, Medium, Low*), dan status (*Pending, In Progress, Completed*) secara interaktif.
- ✏️ **Modal-based Edit & Custom Delete**: Edit data task dan konfirmasi hapus langsung menggunakan dialog modal tanpa reload halaman.
- 🚀 **Version Logs (File-based JSON)**: Pencatatan riwayat rilis & changelog versi yang disimpan dalam format file `data/version_logs.json`.
- 🤖 **AI Agent & CI API Endpoint**: Endpoint REST API untuk memungkinkan AI Agent (seperti Antigravity, Cursor) atau script CI/CD mencatat versi secara otomatis.
- 🔔 **Custom Toast Notifications**: Notifikasi mengambang (*floating toast*) modern dengan animasi mulus dan auto-dismiss.
- 🏷️ **GitHub Actions Auto-Tag & Release**: Otomatis membuat Git Tag & GitHub Release resmi setiap kali ada commit/push ke branch `main`.

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
│       ├── layout.blade.php                         # Base Layout & Notification Engine
│       ├── tasks/
│       │   ├── index.blade.php                      # Dashboard Task, Filter, & Modals
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── version-logs/
│           └── index.blade.php                      # Timeline Changelog & Version Stats
├── routes/
│   ├── api.php                                      # Endpoint API REST
│   └── web.php                                      # Rute Antarmuka Web
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

Anda dapat menginstal plugin ini ke proyek Laravel menggunakan salah satu dari 2 metode di bawah ini:

### Opsi A: Instalasi via GitHub Repository (Remote Git)

1. Tambahkan repository ke `composer.json` proyek Laravel Anda:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:Spinotek-Organization/dev-monitor-plugin.git"
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
| **Task Monitoring** | `http://localhost:8000/monitoring/tasks` | Dashboard daftar task, metrik status, pencarian, filter, modal tambah & edit |
| **Version Logs** | `http://localhost:8000/monitoring/version-logs` | Visualisasi timeline riwayat versi & modal catat rilis baru |
| **API Get Logs** | `GET /api/monitoring/version-logs` | Mengambil seluruh log versi dalam format JSON |
| **API Store Log** | `POST /api/monitoring/version-logs` | Endpoint untuk AI Agent mencatat log rilis baru |

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
      "Menambahkan integrasi auto-release GitHub",
      "Perbaikan tampilan modal responsif"
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

> ⚠️ **Catatan Penting**: Pastikan pada repository GitHub Anda di menu **Settings** > **Actions** > **General** > **Workflow permissions**, Anda telah mengaktifkan opsi **"Read and write permissions"**.

---

## 📄 Lisensi
Plugin ini dilisensikan di bawah [MIT License](LICENSE).
Dikembangkan oleh **Spinotek Team**.
