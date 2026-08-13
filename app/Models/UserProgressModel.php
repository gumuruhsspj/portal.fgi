<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProgressModel extends Model
{
    protected $table = 'table_user_progress';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'sub_chapter_id', 'completed', 'best_wpm', 'completed_at'];
    protected $useTimestamps = false;

    /**
     * Save or update progress after an attempt
     * 
     * @param int $userId
     * @param int $subChapterId
     * @param float $wpm
     * @return void
     */
    public function saveProgress(int $userId, int $subChapterId, float $wpm): void
    {
        $existing = $this->where('user_id', $userId)
            ->where('sub_chapter_id', $subChapterId)
            ->first();
        
        $now = date('Y-m-d H:i:s');
        
        if ($existing) {
            $newBest = ($wpm > $existing['best_wpm']) ? $wpm : $existing['best_wpm'];
            $this->update($existing['id'], [
                'completed'    => 1,
                'best_wpm'     => $newBest,
                'completed_at' => $now
            ]);
        } else {
            $this->insert([
                'user_id'          => $userId,
                'sub_chapter_id'   => $subChapterId,
                'completed'        => 1,
                'best_wpm'         => $wpm,
                'completed_at'     => $now
            ]);
        }
    }

    /**
     * Check if user has completed a subchapter
     * 
     * @param int $userId
     * @param int $subChapterId
     * @return bool
     */
    public function isCompleted(int $userId, int $subChapterId): bool
    {
        $progress = $this->where('user_id', $userId)
            ->where('sub_chapter_id', $subChapterId)
            ->where('completed', 1)
            ->first();
        return !empty($progress);
    }
}