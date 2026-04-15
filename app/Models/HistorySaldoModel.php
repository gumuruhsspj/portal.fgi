<?php 
namespace App\Models;
use CodeIgniter\Model;

class HistorySaldoModel extends Model
{
    
	protected $table = "table_history_saldo";

    protected $primaryKey = 'id';
    // fillable?
    protected $allowedFields = [
        'id_user',
        'saldo_sebelum',
        'nominal',
        'saldo_setelah',
        'jenis',
        'keterangan',
        'status'
    ];   

    public function get_all_by($filter)
    {
        $builder = $this->db->table($this->table);

         $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }
    }

    public function get_saldo_by($id_user){

        // isi saldo dan bonus itu nambah duit
        // tapi slain itu maka ngurangin duit
        

        $builder = $this->db->table($this->table);

        $pemasukan = $builder->selectSum('nominal')
        ->where('id_user', $id_user)
        ->where('status', 'approved')
        ->whereIn('jenis', ['isi saldo', 'bonus'])
        ->get()
        ->getRow()->nominal ?? 0;

        $pengeluaran = $builder->selectSum('nominal')
        ->where('id_user', $id_user)
        ->where('status', 'approved')
        ->whereNotIn('jenis', ['isi saldo', 'bonus'])
        ->get()
        ->getRow()->nominal ?? 0;

        return $pemasukan - $pengeluaran;

    }

    public function update_where($data, $id){

            $idna = array('id' => $id);

            $builder = $this->db->table($this->table);
            return $builder->where($idna)->update($data);

    }

     public function get_all()
    {
        $builder = $this->db->table($this->table);
		
		$builder->select('table_history_saldo.*, table_users.nama_lengkap');
		$builder->join('table_users', 'table_users.id = table_history_saldo.id_user', 'left');
		
        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }
    }

}