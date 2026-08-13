<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaCategoryModel extends Model
{
    protected $table = 'table_media_category';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['nama'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = false;
}
