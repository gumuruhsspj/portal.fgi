<?php

namespace App\Models;

use CodeIgniter\Model;

class ChapterModel extends Model
{
    protected $table = 'table_chapters';
    protected $primaryKey = 'id';
    protected $allowedFields = ['chapter_number', 'title', 'description'];
    protected $useTimestamps = false;

    /**
     * Get all chapters with progress percentage for a specific user
     * 
     * @param int $userId
     * @return array
     */
    public function getChaptersWithProgress(int $userId): array
    {
        $chapters = $this->orderBy('chapter_number', 'asc')->findAll();
        
        $subChapterModel = new SubChapterModel();
        $progressModel = new UserProgressModel();
        
        foreach ($chapters as &$chap) {
            $totalSub = $subChapterModel->where('chapter_id', $chap['id'])->countAllResults();
            // Count completed subchapters for this user in this chapter
            $completedSub = $progressModel->where('user_id', $userId)
                ->whereIn('sub_chapter_id', function($builder) use ($chap) {
                    return $builder->select('id')->from('table_sub_chapters')->where('chapter_id', $chap['id']);
                })
                ->where('completed', 1)
                ->countAllResults();
            $chap['progress_percent'] = $totalSub > 0 ? round(($completedSub / $totalSub) * 100) : 0;
        }
        
        return $chapters;
    }
}