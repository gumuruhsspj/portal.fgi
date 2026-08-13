<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberAfiliasiModel extends Model
{
    protected $table            = 'table_member_afiliasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_program', 'id_user', 'kode_referal', 'status', 'user_count_total', 'user_count_confirmed_total', 'user_cash_paid', 'code'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_modified';

// Tambahkan method berikut di dalam class MemberAfiliasiModel

    /**
     * Update data rekening member afiliasi
     */
    public function update_rekening($member_id, $data)
    {
        return $this->update($member_id, $data);
    }

    /**
     * Ambil data rekening member berdasarkan user_id dan program_id (opsional)
     */
    public function get_rekening_by_user($user_id)
    {
        return $this->where('id_user', $user_id)
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Ambil status rekening untuk ditampilkan
     */
    public function get_rekening_status($user_id)
    {
        $data = $this->get_rekening_by_user($user_id);
        if ($data) {
            return [
                'status' => $data['status_rekening'] ?? 'pending',
                'nama_bank' => $data['nama_bank'] ?? '',
                'nama_pemilik' => $data['nama_pemilik'] ?? '',
                'nomor_rekening' => $data['nomor_rekening'] ?? '',
                'foto_ktp' => $data['foto_ktp'] ?? '',
                'foto_selfie' => $data['foto_selfie'] ?? '',
                'member_id' => $data['id']
            ];
        }
        return null;
    }

    public function get_all_by($dataFilter)
    {
        $builder = $this->db->table($this->table);

        $builder->where($dataFilter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            return $query->getResult();
        } else {
            return false;
        }
    }

    /**
     * Generate kode referal unik (8 karakter alfanumerik)
     */
    public function generate_kode_referal($id_user, $id_program)
    {
        $base = substr(md5($id_user . $id_program . rand(1000, 9999)), 0, 8);
        // Pastikan unik
        while ($this->where('kode_referal', $base)->first()) {
            $base = substr(md5($id_user . $id_program . rand(1000, 9999)), 0, 8);
        }
        return $base;
    }

    /**
     * Cek apakah user sudah join program tertentu
     */
    public function isJoin($id_user, $id_program)
    {
        return $this->where(['id_user' => $id_user, 'id_program' => $id_program, 'status' => 'active'])->first() ? true : false;
    }

    /**
     * Ambil keanggotaan user beserta program
     */
    public function get_member_with_program($id_user)
    {
        $builder = $this->db->table($this->table . ' m');
        $builder->select('m.*, p.nama as program_nama, p.deskripsi as program_deskripsi');
        $builder->join('table_program_afiliasi p', 'p.id = m.id_program_afiliasi', 'left');
        $builder->where('m.id_user', $id_user);
        $builder->where('m.status', 'active');
        return $builder->get()->getResultArray();
    }
}
