<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>High Scores - Typing Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-trophy-fill text-warning"></i> High Scores</h1>
        <a href="/exercise/typing" class="btn btn-outline-primary"><i class="bi bi-house"></i> Kembali ke Bab</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Top 50 Kecepatan Mengetik (WPM)
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Best WPM</th>
                        <th>Best Accuracy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($scores)): ?>
                        <tr><td colspan="4" class="text-center">Belum ada data latihan.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($scores as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><i class="bi bi-person-circle"></i> <?= esc($row['username']) ?></td>
                            <td><strong><?= round($row['best_wpm'], 2) ?></strong> WPM</td>
                            <td><?= isset($row['best_accuracy']) ? round($row['best_accuracy'], 1) . '%' : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info tambahan -->
    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle"></i> WPM = Words Per Minute (1 kata = 5 karakter). Hanya attempt terbaik per user yang ditampilkan.
    </div>
</div>
</body>
</html>