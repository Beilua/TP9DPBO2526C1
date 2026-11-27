<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModel.php';
require_once __DIR__ . '/Kandidat.php';

class TabelKandidat extends DB implements KontrakModel {
    # model handling kandidat data operations against database

    public function __construct(
        string $host = "localhost",
        string $db_name = "db_mvp",
        string $username = "root",
        string $password = ""
    ) {
        parent::__construct($host, $db_name, $username, $password);
    }

    # map a database row to a Kandidat object
    private function mapRowToKandidat(array $row): Kandidat {
        return new Kandidat(
            (int)$row['id'],
            $row['nama'],
            $row['posisi'],
            $row['domisili'],
            (int)$row['skorTes'],
            (int)$row['pengalamanTahun']
        );
    }

    # retrieve all kandidat from database, ordered by skorTes desc, id asc
    public function getAllKandidat(): array {
        $query = "SELECT * FROM kandidat ORDER BY skorTes DESC, id ASC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();

        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->mapRowToKandidat($row);
        }
        return $list;
    }

    # retrieve single kandidat by ID, return null if not found
    public function getKandidatById(int $id): ?Kandidat {
        $query = "SELECT * FROM kandidat WHERE id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();

        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToKandidat($rows[0]);
    }

    # insert new kandidat into database
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        $query = "INSERT INTO kandidat (nama, posisi, domisili, skorTes, pengalamanTahun)
                  VALUES (:nama, :posisi, :domisili, :skorTes, :pengalamanTahun)";
        $this->executeQuery($query, [
            ':nama' => $nama,
            ':posisi' => $posisi,
            ':domisili' => $domisili,
            ':skorTes' => $skorTes,
            ':pengalamanTahun' => $pengalamanTahun,
        ]);
    }

    # update existing kandidat in database by ID
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        $query = "UPDATE kandidat
                  SET nama = :nama, posisi = :posisi, domisili = :domisili,
                      skorTes = :skorTes, pengalamanTahun = :pengalamanTahun
                  WHERE id = :id";
        $this->executeQuery($query, [
            ':id' => $id,
            ':nama' => $nama,
            ':posisi' => $posisi,
            ':domisili' => $domisili,
            ':skorTes' => $skorTes,
            ':pengalamanTahun' => $pengalamanTahun,
        ]);
    }

    # delete kandidat from database by ID
    public function deleteKandidat(int $id): void {
        $query = "DELETE FROM kandidat WHERE id = :id";
        $this->executeQuery($query, [':id' => $id]);
    }
}
?>
