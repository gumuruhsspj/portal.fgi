<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Typing Tutor - Pilih Bab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .chapter-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .chapter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-keyboard"></i> Latihan Mengetik</h1>
        <div class="d-flex gap-2">
            <!-- Tombol bahasa -->
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-translate"></i> <?= ($language == 'en') ? 'English' : 'Indonesia' ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item set-lang" href="#" data-lang="en">English</a></li>
                    <li><a class="dropdown-item set-lang" href="#" data-lang="id">Indonesia</a></li>
                </ul>
            </div>
            <a href="/exercise/typing/highscores" class="btn btn-warning"><i class="bi bi-trophy"></i> High Scores</a>
        </div>
    </div>

    <div class="row">
        <?php foreach ($chapters as $chapter): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card chapter-card h-100" onclick="location.href='/exercise/typing/chapter/<?= $chapter['id'] ?>'">
                <div class="card-body">
                    <h5 class="card-title">Bab <?= $chapter['chapter_number'] ?>: <?= $chapter['title'] ?></h5>
                    <p class="card-text text-muted"><?= $chapter['description'] ?? 'Latihan mengetik untuk meningkatkan kecepatan dan akurasi' ?></p>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Progress</span>
                            <span><?= $chapter['progress_percent'] ?>%</span>
                        </div>
                        <div class="progress progress-bar-custom">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $chapter['progress_percent'] ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
$(function() {
    $('.set-lang').click(function(e) {
        e.preventDefault();
        let lang = $(this).data('lang');
        $.post('/exercise/typing/set-language', { language: lang }, function() {
            location.reload();
        });
    });
});
</script>
</body>
</html>