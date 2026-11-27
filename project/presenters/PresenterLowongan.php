<?php
require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../models/Lowongan.php';
require_once __DIR__ . '/../views/KontrakViewLowongan.php';

class PresenterLowongan implements KontrakPresenterLowongan {
    # presenter handling lowongan (job posting) data operations
    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view) {
        $this->model = $model;
        $this->view = $view;
    }

    # helper to build 1 HTML table row for lowongan
    # view tetap pasil: seluruh perulangan dan markup baris ada di Presenter
    private function buildRowHtml(int $no, Lowongan $lowongan): string {
        $id = (int)$lowongan->getId();
        $judul = htmlspecialchars($lowongan->getJudul(), ENT_QUOTES, 'UTF-8');
        $departemen = htmlspecialchars($lowongan->getDepartemen(), ENT_QUOTES, 'UTF-8');
        $lokasi = htmlspecialchars($lowongan->getLokasi(), ENT_QUOTES, 'UTF-8');
        $tipeKerja = htmlspecialchars($lowongan->getTipeKerja(), ENT_QUOTES, 'UTF-8');
        $kuota = number_format($lowongan->getKuota()) . ' orang';
        $status = htmlspecialchars($lowongan->getStatus(), ENT_QUOTES, 'UTF-8');
        $statusClass = strtolower(trim($lowongan->getStatus())) === 'buka' ? 'badge-open' : 'badge-closed';

        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$judul}</strong></td>
            <td>{$departemen}</td>
            <td>{$lokasi}</td>
            <td>{$tipeKerja}</td>
            <td>{$kuota}</td>
            <td><span class='badge {$statusClass}'>{$status}</span></td>
            <td class='col-actions'>
                <a href='index.php?module=lowongan&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php?module=lowongan' style='display:inline;' onsubmit=\"return confirm('Hapus lowongan ini?')\">
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    # redirect to lowongan list page
    private function redirectToList(): void {
        header("Location: index.php?module=lowongan");
        exit();
    }

    # display daftar (list) of all lowongan
    public function tampilkanLowongan(): string {
        $listLowongan = $this->model->getAllLowongan();
        $totalLowongan = count($listLowongan);
        $rowsHtml = '';

        if (empty($listLowongan)) {
            $rowsHtml = '<tr><td colspan="8" style="text-align:center;color:#6b7280;padding:24px;">Belum ada data lowongan.</td></tr>';
        } else {
            $no = 1;
            foreach ($listLowongan as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no, $lowongan);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalLowongan);
    }

    # display form for adding or editing lowongan
    public function tampilkanFormLowongan(?int $id = null): string {
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan === null) {
                $this->redirectToList();
            }

            $formData = [
                'id' => $lowongan->getId(),
                'judul' => $lowongan->getJudul(),
                'departemen' => $lowongan->getDepartemen(),
                'lokasi' => $lowongan->getLokasi(),
                'tipeKerja' => $lowongan->getTipeKerja(),
                'kuota' => $lowongan->getKuota(),
                'status' => $lowongan->getStatus(),
            ];
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
        }

        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", []);
    }

    # add new lowongan via model then redirect to list
    public function tambahLowongan(string $judul, string $departemen, string $lokasi, string $tipeKerja, int $kuota, string $status): void {
        $this->model->addLowongan($judul, $departemen, $lokasi, $tipeKerja, $kuota, $status);
        $this->redirectToList();
    }

    # update existing lowongan via model then redirect to list
    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, string $tipeKerja, int $kuota, string $status): void {
        $this->model->updateLowongan($id, $judul, $departemen, $lokasi, $tipeKerja, $kuota, $status);
        $this->redirectToList();
    }

    # delete lowongan via model then redirect to list
    public function hapusLowongan(int $id): void {
        $this->model->deleteLowongan($id);
        $this->redirectToList();
    }
}
?>
