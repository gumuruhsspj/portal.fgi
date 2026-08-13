<?php

namespace App\Models;

use CodeIgniter\Model;

class SubChapterModel extends Model
{
    protected $table = 'table_sub_chapters';
    protected $primaryKey = 'id';
    protected $allowedFields = ['chapter_id', 'sub_number', 'title', 'content_en', 'content_id', 'sort_order'];
    protected $useTimestamps = false;

    /**
     * Get subchapters of a chapter with user progress (completed, best_wpm)
     * 
     * @param int $chapterId
     * @param int $userId
     * @return array
     */
    public function getSubChaptersWithProgress(int $chapterId, int $userId): array
    {
        $subChapters = $this->where('chapter_id', $chapterId)
            ->orderBy('sort_order', 'asc')
            ->findAll();
        
        $progressModel = new UserProgressModel();
        
        foreach ($subChapters as &$sub) {
            $progress = $progressModel->where('user_id', $userId)
                ->where('sub_chapter_id', $sub['id'])
                ->first();
            $sub['completed'] = ($progress && $progress['completed']) ? true : false;
            $sub['best_wpm'] = $progress['best_wpm'] ?? null;
        }
        
        return $subChapters;
    }

    /**
     * Get a subchapter by id with content decoded based on language
     * 
     * @param int $subChapterId
     * @param string $language 'en' or 'id'
     * @return array|null
     */
    public function getSubChapterWithContent(int $subChapterId, string $language): ?array
    {
        $sub = $this->find($subChapterId);
        if (!$sub) {
            return null;
        }
        
        $contentJson = ($language == 'en') ? $sub['content_en'] : $sub['content_id'];
        $texts = json_decode($contentJson, true);
        $sub['target_text'] = $texts[0] ?? 'Contoh teks tidak tersedia';
        $sub['all_texts'] = $texts; // optional for random selection later
        
        return $sub;
    }
}