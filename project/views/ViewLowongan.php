<?php
# view class for rendering lowongan (job posting) data
require_once __DIR__ . '/KontrakViewLowongan.php';

class ViewLowongan implements KontrakViewLowongan {
    # path to skin template and form template
    private string $skinPath;
    private string $formPath;

    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin_lowongan.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/form_lowongan.html';
    }

    # render table daftar (list) of lowongan with table rows and total count
    public function renderDaftar(string $tableRowsHtml, int $totalData): string {
        $template = file_get_contents($this->skinPath);

        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    # render form for adding or editing lowongan with title, action, and form data tokens
    public function renderForm(string $title, string $action, array $formData = []): string {
        $template = file_get_contents($this->formPath);

        $tokens = [
            '{{FORM_TITLE}}'  => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            '{{ACTION}}'      => htmlspecialchars($action, ENT_QUOTES, 'UTF-8'),
            '{{ID}}'          => htmlspecialchars((string)($formData['id'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{JUDUL}}'       => htmlspecialchars($formData['judul'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{DEPARTEMEN}}'  => htmlspecialchars($formData['departemen'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{LOKASI}}'      => htmlspecialchars($formData['lokasi'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{TIPE_KERJA}}'  => htmlspecialchars($formData['tipeKerja'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{KUOTA}}'       => htmlspecialchars((string)($formData['kuota'] ?? 1), ENT_QUOTES, 'UTF-8'),
            '{{STATUS}}'      => htmlspecialchars($formData['status'] ?? 'Buka', ENT_QUOTES, 'UTF-8'),
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
