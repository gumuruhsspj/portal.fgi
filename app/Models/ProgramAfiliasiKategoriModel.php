<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramAfiliasiKategoriModel extends Model
{
    protected $table            = 'table_program_afiliasi_kategori';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_program', 'id_kategori', 'komisi_persen'];
}
