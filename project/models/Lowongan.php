<?php
class Lowongan {
    # job posting data model representing a vacancy

    private ?int $id;
    private string $judul;
    private string $departemen;
    private string $lokasi;
    private string $tipeKerja;
    private int $kuota;
    private string $status;

    public function __construct(
        ?int $id,
        string $judul,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ) {
        $this->id = $id;
        $this->judul = $judul;
        $this->departemen = $departemen;
        $this->lokasi = $lokasi;
        $this->tipeKerja = $tipeKerja;
        $this->kuota = $kuota;
        $this->status = $status;
    }

    # get lowongan ID
    public function getId(): ?int { return $this->id; }

    # get judul (title)
    public function getJudul(): string { return $this->judul; }

    # get departemen
    public function getDepartemen(): string { return $this->departemen; }

    # get lokasi (location)
    public function getLokasi(): string { return $this->lokasi; }

    # get tipeKerja (work type)
    public function getTipeKerja(): string { return $this->tipeKerja; }

    # get kuota (quota/quota)
    public function getKuota(): int { return $this->kuota; }

    # get status
    public function getStatus(): string { return $this->status; }

    # set judul (title)
    public function setJudul(string $judul): void { $this->judul = $judul; }

    # set departemen
    public function setDepartemen(string $departemen): void { $this->departemen = $departemen; }

    # set lokasi (location)
    public function setLokasi(string $lokasi): void { $this->lokasi = $lokasi; }

    # set tipeKerja (work type)
    public function setTipeKerja(string $tipeKerja): void { $this->tipeKerja = $tipeKerja; }

    # set kuota (quota/quota)
    public function setKuota(int $kuota): void { $this->kuota = $kuota; }

    # set status
    public function setStatus(string $status): void { $this->status = $status; }
}
?>
