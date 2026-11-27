# TP9DPBO2526C1 — Tugas Praktikum DPBO Modul 10

## Janji

Saya Nabila Attaya Putri Cahyadi dengan NIM 2508355 mengerjakan Tugas Praktikum 9 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

---

## 1. Deskripsi Program

Program ini adalah aplikasi rekrutmen sederhana berbasis PHP dan MySQL yang menerapkan arsitektur **Model-View-Presenter (MVP)** dengan pendekatan **Strict Passive View** sesuai Modul 10. Aplikasi memiliki dua modul MVP lengkap:

1. **Kandidat** — mengelola data pelamar: nama, posisi yang dilamar, domisili, skor tes, dan pengalaman kerja.
2. **Lowongan** — mengelola data lowongan kerja: judul lowongan, departemen, lokasi, tipe kerja, kuota, dan status.

Kedua modul mendukung **Create, Read, Update, dan Delete (CRUD)**. Tidak ada pagination maupun search karena tidak diminta pada tugas. Arsitektur Strict Passive View memastikan View tidak memiliki koneksi langsung ke Model/Entity — seluruh komunikasi melalui kontrak interface.

---

## 2. Struktur Repository

```text
TP9DPBO2526C1/
├── project/
│   ├── index.php
│   ├── models/
│   │   ├── DB.php
│   │   ├── Kandidat.php
│   │   ├── KontrakModel.php
│   │   ├── TabelKandidat.php
│   │   ├── Lowongan.php
│   │   ├── KontrakModelLowongan.php
│   │   └── TabelLowongan.php
│   ├── presenters/
│   │   ├── KontrakPresenter.php
│   │   ├── PresenterKandidat.php
│   │   ├── KontrakPresenterLowongan.php
│   │   └── PresenterLowongan.php
│   ├── views/
│   │   ├── KontrakView.php
│   │   ├── KontrakViewLowongan.php
│   │   ├── ViewKandidat.php
│   │   └── ViewLowongan.php
│   └── template/
│       ├── skin.html
│       ├── form.html
│       ├── skin_lowongan.html
│       └── form_lowongan.html
├── dokumentasi/
│   └── README.md
├── mvp_db.sql
└── README.md
```

*Structur folder digenerate dari `ls -R` proyek asli. Termasuk `README.md`, folder `dokumentasi/`, file `mvp_db.sql`, dan folder `template/`.*

---

## 3. Desain dan Arsitektur

### 3.1 Pembagian Tanggung Jawab

| Komponen | Tanggung jawab | File utama |
|---|---|---|
| **Model** | Mengakses database, menjalankan query prepared statement, memetakan baris database menjadi objek entity. | `models/TabelKandidat.php`, `models/TabelLowongan.php` |
| **View** | Membaca file template HTML dan mengganti placeholder seperti `{{TABLE_ROWS}}` dan `{{TOTAL_DATA}}`. Tidak mengenal Model/Entity. | `views/ViewKandidat.php`, `views/ViewLowongan.php` |
| **Presenter** | Mengambil data dari Model, menghitung total, merakit baris `<tr>`, mengisi data form, dan melakukan redirect setelah operasi CRUD. | `presenters/PresenterKandidat.php`, `presenters/PresenterLowongan.php` |
| **Entity** | Menyimpan data satu record dan menyediakan getter/setter. | `models/Kandidat.php`, `models/Lowongan.php` |
| **Front Controller** | Membaca request GET/POST, memvalidasi input, memilih modul, dan memanggil Presenter. | `index.php` |

### 3.2 Aturan Strict Passive View yang Diterapkan

| Aturan | Penerapan |
|---|---|
| View tidak boleh mengakses Model/Entity | `views/` hanya me-`require_once` file kontrak view (`KontrakView.php` / `KontrakViewLowongan.php`). Tidak ada import Model/Entity. |
| View tidak boleh memakai `foreach` | Seluruh perulangan data dilakukan di Presenter. |
| View tidak boleh memakai `count()` | Total data dihitung di Presenter, lalu dikirim sebagai `int $totalData`. |
| View tidak memproses array objek Entity | View hanya menerima string baris HTML dan array asosiatif datar untuk form. |
| Presenter merakit baris tabel | `buildRowHtml()` ada di `PresenterKandidat` dan `PresenterLowongan`. |
| View hanya mengganti placeholder | `renderDaftar()` dan `renderForm()` memakai `str_replace`. |
| Komunikasi lewat interface | Presenter bergantung pada `KontrakModel`/`KontrakModelLowongan` dan `KontrakView`/`KontrakViewLowongan`, bukan class konkret. |

### 3.3 Kontrak / Interface

| Interface | Diimplementasikan oleh | Keterangan |
|---|---|---|
| `KontrakModel` | `TabelKandidat` | Operasi data kandidat. |
| `KontrakModelLowongan` | `TabelLowongan` | Operasi data lowongan. |
| `KontrakView` | `ViewKandidat` | Kontrak view generik: render daftar dan render form. |
| `KontrakViewLowongan` | `ViewLowongan` | Kontrak view modul Lowongan; mewarisi method `KontrakView` karena bentuk tampilannya sama. |
| `KontrakPresenter` | `PresenterKandidat` | Aksi presentasi kandidat. |
| `KontrakPresenterLowongan` | `PresenterLowongan` | Aksi presentasi lowongan. |

`KontrakViewLowongan` sengaja dibuat sebagai turunan `KontrakView` agar modul Lowongan tetap punya kontrak view sendiri tanpa menduplikasi signature method. Dengan begitu tidak ada duplikasi signature, dan kontrak tetap terpisah per modul.

### 3.4 Dependency Injection

Presenter menerima dependensi lewat constructor:

```php
public function __construct(KontrakModel $model, KontrakView $view)
```

```php
public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view)
```

Dengan demikian Presenter tidak terikat pada `TabelKandidat`, `TabelLowongan`, atau view konkret tertentu.

---

## 4. Alur Program

```text
Browser
   │
   ▼
index.php  ──►  Presenter  ──►  Model  ──►  MySQL
                    │
                    ▼
                  View  ──►  template/*.html  ──►  HTML  ──►  Browser
```

### 4.1 Request GET

| URL | Fungsi |
|---|---|
| `index.php` | Menampilkan daftar kandidat. |
| `index.php?screen=add` | Menampilkan form tambah kandidat. |
| `index.php?screen=edit&id=1` | Menampilkan form ubah kandidat dengan data terisi. |
| `index.php?module=lowongan` | Menampilkan daftar lowongan. |
| `index.php?module=lowongan&screen=add` | Menampilkan form tambah lowongan. |
| `index.php?module=lowongan&screen=edit&id=1` | Menampilkan form ubah lowongan dengan data terisi. |

Jika `id` tidak ditemukan, Presenter melakukan redirect kembali ke halaman daftar.

### 4.2 Request POST

Form mengirim `action` bernilai `tambah`, `ubah`, atau `hapus`.

1. `index.php` memvalidasi input (field wajib, tipe angka, rentang nilai, status lowongan).
2. Jika tidak valid, request diarahkan kembali ke form yang sesuai.
3. Jika valid, Presenter memanggil method Model.
4. Setelah operasi berhasil, Presenter melakukan redirect ke halaman daftar.

Modul dipilih dari parameter `module`:
- tanpa `module` (atau nilai selain `lowongan`) → modul Kandidat;
- `module=lowongan` → modul Lowongan.

---

## 5. Modul Kandidat

### 5.1 Atribut

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `int` | Primary key, auto increment. |
| `nama` | `varchar(100)` | Nama kandidat. |
| `posisi` | `varchar(100)` | Posisi yang dilamar. |
| `domisili` | `varchar(100)` | Domisili kandidat. |
| `skorTes` | `int` | Skor tes 0–100. |
| `pengalamanTahun` | `int` | Pengalaman kerja dalam tahun. |

### 5.2 Operasi

- **Read**: daftar kandidat diurutkan dari `skorTes` tertinggi, lalu `id` terkecil.
- **Create**: form tambah kandidat.
- **Update**: form ubah dengan data terisi otomatis (prefill).
- **Delete**: tombol hapus pada setiap baris dengan konfirmasi.

### 5.3 Komponen

| Lapisan | File |
|---|---|
| Entity | `models/Kandidat.php` |
| Model contract | `models/KontrakModel.php` |
| Model | `models/TabelKandidat.php` |
| View contract | `views/KontrakView.php` |
| View | `views/ViewKandidat.php` |
| Presenter contract | `presenters/KontrakPresenter.php` |
| Presenter | `presenters/PresenterKandidat.php` |
| Template | `template/skin.html`, `template/form.html` |

---

## 6. Modul Lowongan

Tema yang dipilih adalah **Lowongan Kerja** karena masih berkaitan langsung dengan rekrutmen, memiliki atribut yang bermakna, dan tidak memerlukan relasi tambahan.

### 6.1 Atribut

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `int` | Primary key, auto increment. |
| `judul` | `varchar(100)` | Judul lowongan. |
| `departemen` | `varchar(100)` | Departemen pemilik lowongan. |
| `lokasi` | `varchar(100)` | Lokasi kerja. |
| `tipeKerja` | `varchar(50)` | Contoh: Full-time, Kontrak, Magang, Paruh Waktu. |
| `kuota` | `int` | Jumlah kebutuhan, minimal 1. |
| `status` | `varchar(20)` | `Buka` atau `Tutup`. |

### 6.2 Operasi

- **Read**: tabel seluruh lowongan beserta badge status.
- **Create**: form tambah lowongan.
- **Update**: form ubah dengan data terisi otomatis (prefill).
- **Delete**: tombol hapus pada setiap baris dengan konfirmasi.

### 6.3 Komponen

| Lapisan | File |
|---|---|
| Entity | `models/Lowongan.php` |
| Model contract | `models/KontrakModelLowongan.php` |
| Model | `models/TabelLowongan.php` |
| View contract | `views/KontrakViewLowongan.php` (turunan `KontrakView`) |
| View | `views/ViewLowongan.php` |
| Presenter contract | `presenters/KontrakPresenterLowongan.php` |
| Presenter | `presenters/PresenterLowongan.php` |
| Template | `template/skin_lowongan.html`, `template/form_lowongan.html` |

---

## 7. Struktur Database

Database: `db_mvp`

### 7.1 Tabel `kandidat`

```sql
CREATE TABLE `kandidat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(100) NOT NULL,
  `domisili` varchar(100) NOT NULL,
  `skorTes` int(11) NOT NULL DEFAULT 0,
  `pengalamanTahun` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 7.2 Tabel `lowongan`

```sql
CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `tipeKerja` varchar(50) NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 1,
  `status` varchar(20) NOT NULL DEFAULT 'Buka',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Data awal: 5 kandidat dan 4 lowongan. File `mvp_db.sql` menghapus kedua tabel terlebih dahulu agar import ulang selalu menghasilkan data yang konsisten.

---

## 8. Dokumentasi 

https://github.com/user-attachments/assets/141968c5-b3f3-453b-98a6-2dfd753ba811

