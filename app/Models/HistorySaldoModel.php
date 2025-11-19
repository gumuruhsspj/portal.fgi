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

}