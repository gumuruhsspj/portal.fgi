<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latihan Mengetik - <?= $subChapter['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', system-ui; }
        .target-text {
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 3.5rem;
            background: white;
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            line-height: 1.5;
            text-align: center;
            letter-spacing: 0.5px;
            word-break: break-word;
        }
        .target-text .char {
            display: inline-block;
            transition: 0.05s linear;
        }
        .target-text .char.correct {
            background-color: #d4edda;
            color: #155724;
            border-radius: 4px;
        }
        .target-text .char.wrong {
            background-color: #f8d7da;
            color: #721c24;
            text-decoration: line-through;
            border-radius: 4px;
        }
        .target-text .char.current {
            border-bottom: 3px solid #ffc107;
            background-color: #fff3cd;
        }
        .typing-area textarea {
            font-family: 'Fira Code', monospace;
            font-size: 1.2rem;
            background: #fefefe;
            border-radius: 16px;
            resize: vertical;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            border-left: 5px solid;
            transition: 0.2s;
            padding: 0.8rem;
        }
        .stat-card:nth-child(1) { border-left-color: #0d6efd; }
        .stat-card:nth-child(2) { border-left-color: #198754; }
        .stat-card:nth-child(3) { border-left-color: #ffc107; }
        .stat-card:nth-child(4) { border-left-color: #dc3545; }
        .progress-indicator {
            background: #2c3e50;
            color: white;
            padding: 6px 18px;
            border-radius: 40px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .keyboard {
            background: #2d3e50;
            padding: 12px;
            border-radius: 20px;
            display: inline-block;
        }
        .key-row { display: flex; justify-content: center; gap: 4px; margin-bottom: 6px; }
        .key {
            background: #ecf0f1;
            color: #2c3e50;
            font-weight: bold;
            padding: 8px 12px;
            min-width: 52px;
            text-align: center;
            border-radius: 10px;
            font-family: monospace;
            transition: 0.05s linear;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .key.highlight { background: #f1c40f; color: black; transform: scale(1.03); box-shadow: 0 0 8px #f1c40f; }
        .key.pressed { background: #2ecc71; color: white; transform: scale(0.96); }
        .finger-pinky { border-bottom: 3px solid #e74c3c; }
        .finger-ring { border-bottom: 3px solid #f39c12; }
        .finger-middle { border-bottom: 3px solid #2ecc71; }
        .finger-index { border-bottom: 3px solid #3498db; }
        .finger-thumb { border-bottom: 3px solid #9b59b6; }
        
        .toast-success {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 500;
            z-index: 1060;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2><?= $subChapter['title'] ?> <span class="text-muted fs-6">(<?= $subChapter['sub_number'] ?>)</span></h2>
        <div>
            <span class="badge bg-secondary me-2">🌐 <?= $language == 'en' ? 'English' : 'Indonesia' ?></span>
            <button id="backToBab" class="btn btn-outline-secondary btn-sm">← Kembali ke Bab</button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
        <div class="progress-indicator" id="progressText">Memuat...</div>
        <button id="resetLessonBtn" class="btn btn-outline-danger btn-sm">↺ Mulai Ulang Bab Ini</button>
    </div>

    <!-- Teks target dengan highlight per huruf -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="target-text" id="targetText"></div>
        </div>
    </div>

    <!-- Area ketik -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body typing-area">
            <textarea id="userInput" class="form-control" rows="4" placeholder="Ketik di sini..."></textarea>
        </div>
    </div>

    <!-- Virtual Keyboard (sama seperti sebelumnya) -->
    <div class="text-center mt-2">
        <div class="keyboard">
            <!-- baris keyboard... (sama, tidak berubah) -->
            <div class="key-row">
                <div class="key finger-pinky">`</div><div class="key finger-ring">1</div><div class="key finger-middle">2</div><div class="key finger-index">3</div><div class="key finger-index">4</div><div class="key finger-index">5</div>
                <div class="key finger-index">6</div><div class="key finger-index">7</div><div class="key finger-middle">8</div><div class="key finger-ring">9</div><div class="key finger-pinky">0</div><div class="key finger-pinky">-</div><div class="key finger-pinky">=</div>
            </div>
            <div class="key-row">
                <div class="key finger-pinky">Tab</div><div class="key finger-ring">Q</div><div class="key finger-middle">W</div><div class="key finger-index">E</div><div class="key finger-index">R</div><div class="key finger-index">T</div>
                <div class="key finger-index">Y</div><div class="key finger-index">U</div><div class="key finger-middle">I</div><div class="key finger-ring">O</div><div class="key finger-pinky">P</div><div class="key finger-pinky">[</div><div class="key finger-pinky">]</div><div class="key finger-pinky">\</div>
            </div>
            <div class="key-row">
                <div class="key finger-pinky">Caps</div><div class="key finger-ring">A</div><div class="key finger-middle">S</div><div class="key finger-index">D</div><div class="key finger-index">F</div><div class="key finger-index">G</div>
                <div class="key finger-index">H</div><div class="key finger-index">J</div><div class="key finger-middle">K</div><div class="key finger-ring">L</div><div class="key finger-pinky">;</div><div class="key finger-pinky">'</div><div class="key finger-pinky">Enter</div>
            </div>
            <div class="key-row">
                <div class="key finger-pinky">Shift</div><div class="key finger-ring">Z</div><div class="key finger-middle">X</div><div class="key finger-index">C</div><div class="key finger-index">V</div><div class="key finger-index">B</div>
                <div class="key finger-index">N</div><div class="key finger-index">M</div><div class="key finger-middle">,</div><div class="key finger-ring">.</div><div class="key finger-pinky">/</div><div class="key finger-pinky">Shift</div>
            </div>
            <div class="key-row">
                <div class="key finger-thumb" style="min-width: 180px;">Spasi</div>
            </div>
        </div>
        <div class="mt-2 text-muted small">
            🖐️ Warna bawah = posisi jari: 
            <span style="border-bottom:3px solid #e74c3c;">Kelingking</span>
            <span style="border-bottom:3px solid #f39c12;">Manis</span>
            <span style="border-bottom:3px solid #2ecc71;">Tengah</span>
            <span style="border-bottom:3px solid #3498db;">Telunjuk</span>
            <span style="border-bottom:3px solid #9b59b6;">Ibu jari</span>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mt-4 g-3">
        <div class="col-md-3"><div class="stat-card"><div>⚡ WPM</div><h3 class="mb-0" id="statWpm">0</h3></div></div>
        <div class="col-md-3"><div class="stat-card"><div>🎯 Akurasi</div><h3 class="mb-0" id="statAcc">0%</h3></div></div>
        <div class="col-md-3"><div class="stat-card"><div>✅ Benar</div><h3 class="mb-0" id="statCorrect">0</h3></div></div>
        <div class="col-md-3"><div class="stat-card"><div>❌ Salah</div><h3 class="mb-0" id="statWrong">0</h3></div></div>
    </div>
</div>

<script>
// Data dari server
const allTexts = <?= json_encode($allTexts) ?>;
const subChapterId = <?= $subChapter['id'] ?>;
let currentIdx = 0;
let currentTarget = '';
let startTime = null;
let timerInterval = null;
let isActive = true;
let totalDurationSec = 0;

// DOM
const targetEl = $('#targetText');
const inputEl = $('#userInput');
const statWpm = $('#statWpm');
const statAcc = $('#statAcc');
const statCorrect = $('#statCorrect');
const statWrong = $('#statWrong');
const progressSpan = $('#progressText');

// Helper: render target text dengan highlight berdasarkan input user
function renderHighlightedTarget(inputText) {
    let html = '';
    for (let i = 0; i < currentTarget.length; i++) {
        let ch = currentTarget[i];
        let status = '';
        if (i < inputText.length) {
            if (inputText[i] === ch) status = 'correct';
            else status = 'wrong';
        } else if (i === inputText.length) {
            status = 'current';
        }
        html += `<span class="char ${status}">${escapeHtml(ch)}</span>`;
    }
    targetEl.html(html);
}

function escapeHtml(str) {
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function showToast(msg) {
    $('.toast-success').remove();
    let toast = $(`<div class="toast-success">${msg}</div>`);
    $('body').append(toast);
    setTimeout(() => toast.css('opacity', '1'), 10);
    setTimeout(() => {
        toast.css('opacity', '0');
        setTimeout(() => toast.remove(), 400);
    }, 1500);
}

function updateProgress() {
    progressSpan.text(`📄 Teks ${currentIdx+1} dari ${allTexts.length}`);
}

function loadText(index) {
    if (index >= allTexts.length) {
        finishAllTexts();
        return;
    }
    currentIdx = index;
    currentTarget = allTexts[currentIdx];
    renderHighlightedTarget('');
    updateProgress();
    inputEl.val('');
    inputEl.prop('disabled', false);
    inputEl.focus();
    startTime = Date.now();
    if (timerInterval) clearInterval(timerInterval);
    timerInterval = setInterval(updateRealtimeStats, 200);
    highlightNextKey();
}

function updateRealtimeStats() {
    if (!isActive) return;
    const input = inputEl.val();
    let correct = 0, wrong = 0;
    const minLen = Math.min(currentTarget.length, input.length);
    for (let i = 0; i < minLen; i++) {
        if (currentTarget[i] === input[i]) correct++;
        else wrong++;
    }
    wrong += Math.abs(input.length - currentTarget.length);
    const elapsed = (Date.now() - startTime) / 1000;
    const minutes = elapsed / 60;
    let wpm = (correct / 5) / minutes;
    wpm = isFinite(wpm) ? wpm : 0;
    const accuracy = input.length ? (correct / input.length * 100) : 0;
    statWpm.text(Math.round(wpm));
    statAcc.text(accuracy.toFixed(1) + '%');
    statCorrect.text(correct);
    statWrong.text(wrong);
    renderHighlightedTarget(input);
    highlightNextKey();
}

function checkCompletion() {
    const input = inputEl.val();
    if (input.length >= currentTarget.length) {
        finishCurrentText();
    }
}

function finishCurrentText() {
    if (!isActive) return;
    const duration = (Date.now() - startTime) / 1000;
    totalDurationSec += duration;
    const userInput = inputEl.val();
    
    $.ajax({
        url: '/exercise/typing/save-result',
        method: 'POST',
        data: JSON.stringify({
            sub_chapter_id: subChapterId,
            user_input: userInput,
            duration_seconds: duration,
            target_text: currentTarget
        }),
        contentType: 'application/json',
        error: () => console.log('Gagal simpan')
    });
    
    showToast(`✅ Teks ${currentIdx+1} selesai! Lanjut...`);
    
    const next = currentIdx + 1;
    if (next < allTexts.length) {
        loadText(next);
    } else {
        finishAllTexts();
    }
}

function finishAllTexts() {
    isActive = false;
    if (timerInterval) clearInterval(timerInterval);
    inputEl.prop('disabled', true);
    
    let modalHtml = `
        <div class="modal fade" id="completeModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">🎉 Selamat! Bab Selesai 🎉</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Kamu telah menyelesaikan <strong><?= $subChapter['title'] ?></strong>.</p>
                        <p>Total teks: ${allTexts.length}</p>
                        <p>Total waktu: ${Math.round(totalDurationSec)} detik</p>
                        <hr>
                        <p class="mb-0">Klik tombol di bawah untuk melanjutkan ke sub bab berikutnya.</p>
                    </div>
                    <div class="modal-footer">
                        <a href="/exercise/typing/chapter/<?= $subChapter['chapter_id'] ?>" class="btn btn-primary">Lanjut ke Sub Bab Lain</a>
                        <a href="/exercise/typing" class="btn btn-outline-secondary">Ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('body').append(modalHtml);
    $('#completeModal').modal('show');
    $('#completeModal').on('hidden.bs.modal', function() { $(this).remove(); });
}

function resetLesson() {
    if (confirm('Reset akan mengulang dari teks pertama. Progres yang sudah tersimpan tetap ada, tetapi kamu harus mengetik ulang semua teks di bab ini.')) {
        currentIdx = 0;
        totalDurationSec = 0;
        isActive = true;
        loadText(0);
    }
}

function highlightNextKey() {
    const input = inputEl.val();
    const nextChar = currentTarget[input.length];
    if (!nextChar) return;
    const upper = nextChar.toUpperCase();
    $('.key').removeClass('highlight');
    $('.key').each(function() {
        let txt = $(this).text().trim();
        if (txt.length === 1 && (txt === nextChar || txt === upper)) {
            $(this).addClass('highlight');
        }
        if (nextChar === ' ' && txt === 'Spasi') $(this).addClass('highlight');
    });
}

function animatePress(keyChar) {
    const upper = keyChar.toUpperCase();
    $('.key').each(function() {
        let txt = $(this).text().trim();
        if ((txt.length === 1 && (txt === keyChar || txt === upper)) || (keyChar === ' ' && txt === 'Spasi')) {
            $(this).addClass('pressed');
            setTimeout(() => $(this).removeClass('pressed'), 90);
        }
    });
}

$(function() {
    loadText(0);
    
    inputEl.on('input', function() {
        if (!isActive) return;
        updateRealtimeStats();
        checkCompletion();
    });
    
    inputEl.on('keydown', function(e) {
        if (!isActive) return;
        if (e.key.length === 1) animatePress(e.key);
    });
    
    $('#resetLessonBtn').click(resetLesson);
    $('#backToBab').click(() => window.location.href = '/exercise/typing');
});
</script>
</body>
</html>