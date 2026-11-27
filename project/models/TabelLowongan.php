<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelLowongan.php';
require_once __DIR__ . '/Lowongan.php';

class TabelLowongan extends DB implements KontrakModelLowongan {
    # model handling lowongan data operations against database

    public function __construct(
        string $host = "localhost",
        string $db_name = "db_mvp",
        string $username = "root",
        string $password = ""
    ) {
        parent::__construct($host, $db_name, $username, $password);
    }

    # map a database row to a Lowongan object
    private function mapRowToLowongan(array $row): Lowongan {
        return new Lowongan(
            (int)$row['id'],
            $row['judul'],
            $row['departemen'],
            $row['lokasi'],
            $row['tipeKerja'],
            (int)$row['kuota'],
            $row['status']
        );
    }

    # retrieve all lowongan from database, ordered by id asc
    public function getAllLowongan(): array {
        $query = "SELECT * FROM lowongan ORDER BY id ASC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();

        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->mapRowToLowongan($row);
        }
        return $list;
    }

    # retrieve single lowongan by ID, return null if not found
    public function getLowonganById(int $id): ?Lowongan {
        $query = "SELECT * FROM lowongan WHERE id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();

        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToLowongan($rows[0]);
    }

    # insert new lowongan into database
    public function addLowongan(string $judul, string $departemen, string $lokasi, string $tipeKerja, int $kuota, string $status): void {
        $query = "INSERT INTO lowongan (judul, departemen, lokasi, tipeKerja, kuota, status)
                  VALUES (:judul, :departemen, :lokasi, :tipeKerja, :kuota, :status)";
        $this->executeQuery($query, [
            ':judul' => $judul,
            ':departemen' => $departemen,
            ':lokasi' => $lokasi,
            ':tipeKerja' => $tipeKerja,
            ':kuota' => $kuota,
            ':status' => $status,
        ]);
    }

    # update existing lowongan in database by ID
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, string $tipeKerja, int $kuota, string $status): void {
        $query = "UPDATE lowongan
                  SET judul = :judul, departemen = :departemen, lokasi = :lokasi,
                      tipeKerja = :tipeKerja, kuota = :kuota, status = :status
                  WHERE id = :id";
        $this->executeQuery($query, [
            ':id' => $id,
            ':judul' => $judul,
            ':departemen' => $departemen,
            ':lokasi' => $lokasi,
            ':tipeKerja' => $tipeKerja,
            ':kuota' => $kuota,
            ':status' => $status,
        ]);
    }

    # delete lowongan from database by ID
    public function deleteLowongan(int $id): void {
        $query = "DELETE FROM lowongan WHERE id = :id";
        $this->executeQuery($query, [':id' => $id]);
    }
}
?>
