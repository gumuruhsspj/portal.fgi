<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaPromosiModel extends Model
{
    protected $table = 'table_media_promosi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['id_kategori', 'nama', 'image', 'config'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllWithCategory($limit = null, $offset = 0, $search = '')
    {
        $this->select('table_media_promosi.*, table_media_category.nama as kategori_nama')
            ->join('table_media_category', 'table_media_promosi.id_kategori = table_media_category.id', 'left');
        if ($search) {
            $this->groupStart()
                ->like('table_media_promosi.nama', $search)
                ->orLike('table_media_category.nama', $search)
                ->groupEnd();
        }
        // PASTIKAN CAST KE INT jika tidak null
        if ($limit !== null) {
            $this->limit((int) $limit, (int) $offset);
        }
        return $this->findAll();
    }

    public function countAllWithCategory($search = '')
    {
        $this->join('table_media_category', 'table_media_promosi.id_kategori = table_media_category.id', 'left');
        if ($search) {
            $this->groupStart()
                ->like('table_media_promosi.nama', $search)
                ->orLike('table_media_category.nama', $search)
                ->groupEnd();
        }
        return $this->countAllResults();
    }

    // Ambil satu dengan kategori
    public function getWithCategory($id)
    {
        return $this->select('table_media_promosi.*, table_media_category.nama as kategori_nama')
            ->join('table_media_category', 'table_media_category.id = table_media_promosi.id_kategori')
            ->where('table_media_promosi.id', $id)
            ->first();
    }
}
