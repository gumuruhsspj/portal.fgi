<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $chapter['title'] ?> - Typing Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .sub-card {
            transition: all 0.2s;
            border-left: 4px solid #0d6efd;
        }
        .sub-card.completed {
            border-left-color: #198754;
            background: #f8fff8;
        }
        .sub-card:hover {
            background: #f1f5f9;
        }
        .badge-best {
            background-color: #ffc107;
            color: #000;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="/exercise/typing" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Bab</a>
            <h2 class="mt-2">Bab <?= $chapter['chapter_number'] ?>: <?= $chapter['title'] ?></h2>
        </div>
        <div>
            <span class="badge bg-info">Bahasa: <?= $language == 'en' ? 'English' : 'Indonesia' ?></span>
        </div>
    </div>

    <div class="list-group mt-3">
        <?php foreach ($subChapters as $sub): ?>
        <a href="/exercise/typing/lesson/<?= $sub['id'] ?>" class="list-group-item list-group-item-action sub-card <?= $sub['completed'] ? 'completed' : '' ?>">
            <div class="d-flex w-100 justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><?= $sub['sub_number'] ?>. <?= $sub['title'] ?></h5>
                    <small class="text-muted"><?= $sub['completed'] ? '✓ Sudah diselesaikan' : 'Belum dikerjakan' ?></small>
                </div>
                <div class="text-end">
                    <?php if ($sub['best_wpm']): ?>
                        <span class="badge badge-best"><i class="bi bi-trophy"></i> Best WPM: <?= $sub['best_wpm'] ?></span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Belum ada nilai</span>
                    <?php endif; ?>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Motivasi / video lucu (bebas diisi sendiri nanti) -->
    <div class="mt-5 text-center">
        <div class="card">
            <div class="card-body">
                <i class="bi bi-emoji-smile fs-1"></i>
                <p class="mt-2">Selamat berlatih! Jangan lupa istirahat sebentar ya 😊</p>
                <!-- Tempat untuk thumbnail video lucu (nanti diisi manual) -->
                <div id="funnyVideoPlaceholder" class="mt-2">
                    <!-- Contoh embed youtube lucu (optional) -->
                    <!-- <iframe width="280" height="158" src="https://www.youtube.com/embed/..."></iframe> -->
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>