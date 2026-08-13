<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferalModel extends Model
{
    protected $table            = 'table_referal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_member_afiliasi', 'id_user'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'date_created';
    protected $updatedField  = false;

    /**
     * Cek apakah user sudah memiliki referal (tidak boleh double)
     */
    public function hasReferal($id_user)
    {
        return $this->where('id_user', $id_user)->first() ? true : false;
    }

    public function get_referred_users($member_id)
    {
        $builder = $this->db->table($this->table . ' r');
        $builder->select('u.username, u.email, r.date_created');
        $builder->join('table_users u', 'u.id = r.id_user');
        $builder->where('r.id_member_afiliasi', $member_id);
        $builder->orderBy('r.date_created', 'DESC');
        return $builder->get()->getResult();
    }

    /**
     * Dapatkan afiliator dari user yang direferensikan
     */
    public function get_afiliator_by_user($id_user)
    {
        $builder = $this->db->table($this->table . ' r');
        $builder->select('m.id_user as afiliator_id, m.kode_referal, p.id as program_id, p.nama as program_nama');
        $builder->join('table_member_afiliasi m', 'm.id = r.id_member_afiliasi');
        $builder->join('table_program_afiliasi p', 'p.id = m.id_program');
        $builder->where('r.id_user', $id_user);
        return $builder->get()->getRowArray();
    }
}
