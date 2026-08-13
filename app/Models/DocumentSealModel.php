<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentSealModel extends Model
{
    protected $table            = 'table_document_seals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'document_id',
        'user_id',
        'owner_name',
        'original_file',
        'sealed_file',
        'file_type',
        'file_size',
        'qr_path',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'document_id'   => 'required|max_length[50]|is_unique[table_document_seals.document_id]',
        'user_id'       => 'permit_empty|integer',
        'owner_name'    => 'required|max_length[100]',
        'original_file' => 'required|max_length[255]',
        'sealed_file'   => 'required|max_length[255]',
        'file_type'     => 'required|max_length[50]',
        'file_size'     => 'required|integer',
        'qr_path'       => 'required|max_length[255]',
        'status'        => 'required|max_length[50]',
    ];

    protected $validationMessages = [];

    // Callbacks
    protected $beforeInsert = ['generateDocumentId'];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];

    /**
     * Generate unique document ID if not provided
     */
    protected function generateDocumentId(array $data)
    {
        if (!isset($data['data']['document_id']) || empty($data['data']['document_id'])) {
            $data['data']['document_id'] = $this->generateUniqueId();
        }
        return $data;
    }

    /**
     * Generate a unique 16-character alphanumeric ID
     */
    private function generateUniqueId()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($characters) - 1;
        $id = '';
        for ($i = 0; $i < 16; $i++) {
            $id .= $characters[random_int(0, $max)];
        }
        // Ensure uniqueness
        while ($this->where('document_id', $id)->countAllResults() > 0) {
            $id = '';
            for ($i = 0; $i < 16; $i++) {
                $id .= $characters[random_int(0, $max)];
            }
        }
        return $id;
    }

    /**
     * Get record by document_id
     */
    public function getByDocumentId(string $documentId)
    {
        return $this->where('document_id', $documentId)->first();
    }

    /**
     * Update status
     */
    public function updateStatus(string $documentId, string $status)
    {
        return $this->where('document_id', $documentId)->set(['status' => $status])->update();
    }
}
