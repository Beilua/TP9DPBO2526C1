<?php
class Kandidat {
    # candidate data model representing a job applicant

    private ?int $id;
    private string $nama;
    private string $posisi;
    private string $domisili;
    private int $skorTes;
    private int $pengalamanTahun;

    public function __construct(
        ?int $id,
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ) {
        $this->id = $id;
        $this->nama = $nama;
        $this->posisi = $posisi;
        $this->domisili = $domisili;
        $this->skorTes = $skorTes;
        $this->pengalamanTahun = $pengalamanTahun;
    }

    # get kandidat ID
    public function getId(): ?int { return $this->id; }

    # get candidate name
    public function getNama(): string { return $this->nama; }

    # get applied position
    public function getPosisi(): string { return $this->posisi; }

    # get domicile
    public function getDomisili(): string { return $this->domisili; }

    # get test score
    public function getSkorTes(): int { return $this->skorTes; }

    # get years of experience
    public function getPengalamanTahun(): int { return $this->pengalamanTahun; }

    # set candidate name
    public function setNama(string $nama): void { $this->nama = $nama; }

    # set applied position
    public function setPosisi(string $posisi): void { $this->posisi = $posisi; }

    # set domicile
    public function setDomisili(string $domisili): void { $this->domisili = $domisili; }

    # set test score
    public function setSkorTes(int $skorTes): void { $this->skorTes = $skorTes; }

    # set years of experience
    public function setPengalamanTahun(int $pengalamanTahun): void { $this->pengalamanTahun = $pengalamanTahun; }
}
?>
