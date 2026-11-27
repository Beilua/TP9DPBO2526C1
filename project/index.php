<?php
# handle form input retrieval
require_once __DIR__ . '/models/TabelKandidat.php';
# load kandidat model module
require_once __DIR__ . '/models/TabelLowongan.php';
# load lowongan model module
require_once __DIR__ . '/views/ViewKandidat.php';
# load kandidat view module
require_once __DIR__ . '/views/ViewLowongan.php';
# load lowongan view module
require_once __DIR__ . '/presenters/PresenterKandidat.php';
# load kandidat presenter module
require_once __DIR__ . '/presenters/PresenterLowongan.php';
# load lowongan presenter module

function postString(string $key): string {
    # retrieve and trim posted string value
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function postInt(string $key): ?int {
    # validate and convert posted integer value
    $value = $_POST[$key] ?? null;
    if (!is_string($value) && !is_int($value)) {
        return null;
    }
    $validated = filter_var($value, FILTER_VALIDATE_INT);
    return $validated === false ? null : (int)$validated;
}

function redirectTo(string $location): void {
    # redirect to specified location and terminate script
    header('Location: ' . $location);
    exit();
}

function renderDatabaseError(RuntimeException $e): void {
    # display database error page
    http_response_code(500);
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Kesalahan</title></head><body>';
    echo '<h1>Terjadi Kesalahan</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p><a href="index.php">Kembali ke daftar kandidat</a></p>';
    echo '</body></html>';
}

try {
    # determine module: default to kandidat, lowongan if specified
    $module = $_GET['module'] ?? 'kandidat';
    if ($module !== 'lowongan') {
        $module = 'kandidat';
    }

    # database connection configuration
    $dbHost = 'localhost';
    $dbName = 'db_mvp';
    $dbUser = 'root';
    $dbPass = '';

    if ($module === 'lowongan') {
        $model = new TabelLowongan($dbHost, $dbName, $dbUser, $dbPass);
        $view = new ViewLowongan();
        $presenter = new PresenterLowongan($model, $view);
    } else {
        $model = new TabelKandidat($dbHost, $dbName, $dbUser, $dbPass);
        $view = new ViewKandidat();
        $presenter = new PresenterKandidat($model, $view);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        # process posted form actions
        $action = postString('action');

        if ($module === 'lowongan') {
            # handle lowongan (job posting) actions
            if ($action === 'tambah') {
                # add new lowongan: validate and route to presenter
                $judul = postString('judul');
                $departemen = postString('departemen');
                $lokasi = postString('lokasi');
                $tipeKerja = postString('tipeKerja');
                $kuota = postInt('kuota');
                $status = postString('status');

                $valid = $judul !== '' && mb_strlen($judul) <= 100
                    && $departemen !== '' && mb_strlen($departemen) <= 100
                    && $lokasi !== '' && mb_strlen($lokasi) <= 100
                    && $tipeKerja !== '' && mb_strlen($tipeKerja) <= 50
                    && $kuota !== null && $kuota >= 1
                    && in_array($status, ['Buka', 'Tutup'], true);

                if (!$valid) {
                    redirectTo('index.php?module=lowongan&screen=add');
                }

                $presenter->tambahLowongan($judul, $departemen, $lokasi, $tipeKerja, $kuota, $status);
            } elseif ($action === 'ubah') {
                # update existing lowongan: validate and route to presenter
                $id = postInt('id');
                $judul = postString('judul');
                $departemen = postString('departemen');
                $lokasi = postString('lokasi');
                $tipeKerja = postString('tipeKerja');
                $kuota = postInt('kuota');
                $status = postString('status');

                if ($id === null || $id < 1) {
                    redirectTo('index.php?module=lowongan');
                }

                $valid = $judul !== '' && mb_strlen($judul) <= 100
                    && $departemen !== '' && mb_strlen($departemen) <= 100
                    && $lokasi !== '' && mb_strlen($lokasi) <= 100
                    && $tipeKerja !== '' && mb_strlen($tipeKerja) <= 50
                    && $kuota !== null && $kuota >= 1
                    && in_array($status, ['Buka', 'Tutup'], true);

                if (!$valid) {
                    redirectTo('index.php?module=lowongan&screen=edit&id=' . $id);
                }

                $presenter->ubahLowongan($id, $judul, $departemen, $lokasi, $tipeKerja, $kuota, $status);
            } elseif ($action === 'hapus') {
                # delete lowongan: validate ID and route to presenter
                $id = postInt('id');
                if ($id === null || $id < 1) {
                    redirectTo('index.php?module=lowongan');
                }
                $presenter->hapusLowongan($id);
            }

            # complete lowongan operation - redirect to list
            redirectTo('index.php?module=lowongan');
        }

        if ($action === 'tambah') {
            # add new kandidat: validate and route to presenter
            $nama = postString('nama');
            $posisi = postString('posisi');
            $domisili = postString('domisili');
            $skorTes = postInt('skorTes');
            $pengalamanTahun = postInt('pengalamanTahun');

            $valid = $nama !== '' && mb_strlen($nama) <= 100
                && $posisi !== '' && mb_strlen($posisi) <= 100
                && $domisili !== '' && mb_strlen($domisili) <= 100
                && $skorTes !== null && $skorTes >= 0 && $skorTes <= 100
                && $pengalamanTahun !== null && $pengalamanTahun >= 0;

            if (!$valid) {
                redirectTo('index.php?screen=add');
            }

            $presenter->tambahKandidat($nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        } elseif ($action === 'ubah') {
            # update existing kandidat: validate and route to presenter
            $id = postInt('id');
            $nama = postString('nama');
            $posisi = postString('posisi');
            $domisili = postString('domisili');
            $skorTes = postInt('skorTes');
            $pengalamanTahun = postInt('pengalamanTahun');

            if ($id === null || $id < 1) {
                redirectTo('index.php');
            }

            $valid = $nama !== '' && mb_strlen($nama) <= 100
                && $posisi !== '' && mb_strlen($posisi) <= 100
                && $domisili !== '' && mb_strlen($domisili) <= 100
                && $skorTes !== null && $skorTes >= 0 && $skorTes <= 100
                && $pengalamanTahun !== null && $pengalamanTahun >= 0;

            if (!$valid) {
                redirectTo('index.php?screen=edit&id=' . $id);
            }

            $presenter->ubahKandidat($id, $nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        } elseif ($action === 'hapus') {
            # delete kandidat: validate ID and route to presenter
            $id = postInt('id');
            if ($id === null || $id < 1) {
                redirectTo('index.php');
            }
            $presenter->hapusKandidat($id);
        }

        # complete kandidat operation - redirect to home
        redirectTo('index.php');
    }

    $screen = $_GET['screen'] ?? 'list';

    # render appropriate screen based on module and screen parameter
    if ($module === 'lowongan') {
        if ($screen === 'add') {
            echo $presenter->tampilkanFormLowongan();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormLowongan((int)$_GET['id']);
        } else {
            echo $presenter->tampilkanLowongan();
        }
    } else {
        if ($screen === 'add') {
            echo $presenter->tampilkanFormKandidat();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormKandidat((int)$_GET['id']);
        } else {
            echo $presenter->tampilkanKandidat();
        }
    }
} catch (RuntimeException $e) {
    # render database error page on exception
    renderDatabaseError($e);
}
?>
