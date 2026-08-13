<?php

namespace App\Models;

use CodeIgniter\Model;

class AttemptModel extends Model
{
    protected $table = 'table_attempts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'sub_chapter_id', 'wpm', 'accuracy', 'raw_speed',
        'correct_count', 'wrong_count', 'total_chars', 'duration_seconds', 'created_at'
    ];
    protected $useTimestamps = false;

    /**
     * Get high scores (best WPM per user)
     * 
     * @param int $limit
     * @return array
     */
    public function getHighScores(int $limit = 50): array
    {
        $db = \Config\Database::connect();
        $sql = "SELECT u.username, MAX(a.wpm) as best_wpm, MAX(a.accuracy) as best_accuracy
                FROM table_attempts a
                JOIN table_users u ON u.id = a.user_id
                GROUP BY a.user_id
                ORDER BY best_wpm DESC
                LIMIT :limit:";
        $query = $db->query($sql, ['limit' => $limit]);
        return $query->getResultArray();
    }

    /**
     * Save an attempt record
     * 
     * @param array $data
     * @return int insert ID
     */
    public function saveAttempt(array $data): int
    {
        return $this->insert($data);
    }
}