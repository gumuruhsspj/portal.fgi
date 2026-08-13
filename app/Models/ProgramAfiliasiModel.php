<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramAfiliasiModel extends BaseModel
{
    protected $table            = 'table_program_afiliasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'deskripsi', 'total_member', 'icon', 'status', 'created_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_modified';

    public function get_all()
    {
        return $this->findAll();
    }

    /**
     * Ambil semua program afiliasi beserta kategori dan komisi
     */
    public function get_all_with_categori()
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('p.*, GROUP_CONCAT(pak.id_kategori) as kategori_ids, GROUP_CONCAT(pak.komisi_persen) as komisi_persens, GROUP_CONCAT(mc.nama) as kategori_namas');
        $builder->join('table_program_afiliasi_kategori pak', 'pak.id_program = p.id', 'left');
        $builder->join('table_media_category mc', 'mc.id = pak.id_kategori', 'left');
        $builder->where('p.status', 'active');
        $builder->groupBy('p.id');
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Ambil detail program dengan kategorinya
     */
    public function get_detail_with_categori($id_program)
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('p.*, pak.id_kategori, pak.komisi_persen, mc.nama as kategori_nama');
        $builder->join('table_program_afiliasi_kategori pak', 'pak.id_program = p.id', 'left');
        $builder->join('table_media_category mc', 'mc.id = pak.id_kategori', 'left');
        $builder->where('p.id', $id_program);
        $builder->where('p.status', 'active');
        $query = $builder->get();
        return $query->getResultArray();
    }
}
