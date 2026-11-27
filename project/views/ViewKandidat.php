<?php
# view class for rendering kandidat (candidate) data
require_once __DIR__ . '/KontrakView.php';

class ViewKandidat implements KontrakView {
    # path to skin template and form template
    private string $skinPath;
    private string $formPath;

    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/form.html';
    }

    # render table daftar (list) of kandidat with table rows and total count
    public function renderDaftar(string $tableRowsHtml, int $totalData): string {
        $template = file_get_contents($this->skinPath);

        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    # render form for adding or editing kandidat with title, action, and form data tokens
    public function renderForm(string $title, string $action, array $formData = []): string {
        $template = file_get_contents($this->formPath);

        $tokens = [
            '{{FORM_TITLE}}'       => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            '{{ACTION}}'           => htmlspecialchars($action, ENT_QUOTES, 'UTF-8'),
            '{{ID}}'               => htmlspecialchars((string)($formData['id'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{NAMA}}'             => htmlspecialchars($formData['nama'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{POSISI}}'           => htmlspecialchars($formData['posisi'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{DOMISILI}}'         => htmlspecialchars($formData['domisili'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{SKOR_TES}}'         => htmlspecialchars((string)($formData['skorTes'] ?? 0), ENT_QUOTES, 'UTF-8'),
            '{{PENGALAMAN_TAHUN}}' => htmlspecialchars((string)($formData['pengalamanTahun'] ?? 0), ENT_QUOTES, 'UTF-8'),
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
