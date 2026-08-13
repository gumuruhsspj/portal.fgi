<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ChapterModel;
use App\Models\SubChapterModel;
use App\Models\AttemptModel;
use App\Models\UserProgressModel;

class Typing extends BaseController
{
    protected $session;
    protected $currentUser;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        // TODO: ganti dengan user_id dari session login
        $this->currentUser = 1;
    }

    public function index()
    {
        $chapterModel = new ChapterModel();
        $chapters = $chapterModel->getChaptersWithProgress($this->currentUser);
        
        $language = $this->session->get('typing_lang') ?? 'en';
        
        $this->is_logged_in();
        
        $data = $this->get_user_data();
        $data['menu_dashboard_active'] = 'active';

        $data['chapters'] = $chapters;
        $data['language'] = $language;
        $data['data'] = $data;

        return view('typing/home', $data);
    }

    public function chapter($chapterId)
    {
        $chapterModel = new ChapterModel();
        $chapter = $chapterModel->find($chapterId);
        if (!$chapter) {
            return redirect()->to('/exercise/typing')->with('error', 'Bab tidak ditemukan');
        }
        
        $subChapterModel = new SubChapterModel();
        $subChapters = $subChapterModel->getSubChaptersWithProgress($chapterId, $this->currentUser);
        
        $language = $this->session->get('typing_lang') ?? 'en';
        
        return view('typing/chapter', [
            'chapter'     => $chapter,
            'subChapters' => $subChapters,
            'language'    => $language
        ]);
    }

    // Di dalam Typing.php, method lesson()
public function lesson($subChapterId)
{
    $subChapterModel = new SubChapterModel();
    $language = $this->session->get('typing_lang') ?? 'en';
    $sub = $subChapterModel->getSubChapterWithContent($subChapterId, $language);
    
    if (!$sub) {
        return redirect()->to('/exercise/typing')->with('error', 'Latihan tidak ditemukan');
    }
    
    $progressModel = new UserProgressModel();
    $completed = $progressModel->isCompleted($this->currentUser, $subChapterId);
    
    // Kirim semua teks ke view (JSON)
    return view('typing/lesson', [
        'subChapter'      => $sub,
        'targetText'      => $sub['target_text'],      // teks pertama (default)
        'allTexts'        => $sub['all_texts'],        // array semua teks
        'language'        => $language,
        'completedBefore' => $completed
    ]);
}

    public function saveResult()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $data = $this->request->getJSON();
        $subChapterId = (int)$data->sub_chapter_id;
        $userInput = $data->user_input;
        $durationSec = (float)$data->duration_seconds;
        $targetText = $data->target_text;
        
        // Hitung statistik
        $correct = 0;
        $wrong = 0;
        $minLen = min(strlen($targetText), strlen($userInput));
        for ($i = 0; $i < $minLen; $i++) {
            if ($targetText[$i] === $userInput[$i]) {
                $correct++;
            } else {
                $wrong++;
            }
        }
        $wrong += abs(strlen($userInput) - strlen($targetText));
        $totalTyped = strlen($userInput);
        $accuracy = ($totalTyped > 0) ? ($correct / $totalTyped) * 100 : 0;
        
        $minutes = $durationSec / 60;
        $wpm = ($correct / 5) / $minutes;
        $rawSpeed = $totalTyped / $minutes;
        
        // Simpan attempt
        $attemptModel = new AttemptModel();
        $attemptId = $attemptModel->saveAttempt([
            'user_id'          => $this->currentUser,
            'sub_chapter_id'   => $subChapterId,
            'wpm'              => $wpm,
            'accuracy'         => $accuracy,
            'raw_speed'        => $rawSpeed,
            'correct_count'    => $correct,
            'wrong_count'      => $wrong,
            'total_chars'      => $totalTyped,
            'duration_seconds' => $durationSec,
            'created_at'       => date('Y-m-d H:i:s')
        ]);
        
        // Update progress
        $progressModel = new UserProgressModel();
        $progressModel->saveProgress($this->currentUser, $subChapterId, $wpm);
        
        return $this->response->setJSON([
            'success' => true,
            'stats' => [
                'wpm'      => round($wpm, 2),
                'accuracy' => round($accuracy, 2),
                'correct'  => $correct,
                'wrong'    => $wrong,
                'speed'    => round($rawSpeed, 2)
            ],
            'attempt_id' => $attemptId
        ]);
    }

    public function highscores()
    {
        $attemptModel = new AttemptModel();
        $scores = $attemptModel->getHighScores(50);
        
        return view('typing/highscores', ['scores' => $scores]);
    }

    public function setLanguage()
    {
        $lang = $this->request->getPost('language');
        if (in_array($lang, ['en', 'id'])) {
            $this->session->set('typing_lang', $lang);
        }
        return $this->response->setJSON(['status' => 'ok']);
    }
}