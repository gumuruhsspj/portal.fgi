<?php 
namespace App\Models;


class HistorySaldoModel extends BaseModel
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

    public function recalc_saldo_chain(int $id_user): bool
{
    // Ambil semua history user yang statusnya 'approved' (hanya yang sudah disetujui)
    // Urutkan berdasarkan date_created ASC
    $histories = $this->where('id_user', $id_user)
        ->where('status', 'approved')
        ->orderBy('date_created', 'ASC')
        ->findAll();

    if (empty($histories)) {
        // Tidak ada transaksi approved, set balance user ke 0
        $this->db->table('table_users')->where('id', $id_user)->update(['balance' => 0]);
        return true;
    }

    $running_balance = 0;
    foreach ($histories as $idx => $row) {
        // Tentukan apakah nominal menambah atau mengurangi saldo
        $jenis = $row['jenis'];
        $nominal = (int) $row['nominal'];
        if (in_array($jenis, ['isi saldo', 'bonus'])) {
            $effect = +$nominal;
        } else {
            $effect = -$nominal;
        }

        $saldo_sebelum = $running_balance;
        $saldo_setelah = $running_balance + $effect;

        // Update baris history ini dengan nilai saldo_sebelum & saldo_setelah yang benar
        $this->update($row['id'], [
            'saldo_sebelum' => $saldo_sebelum,
            'saldo_setelah' => $saldo_setelah,
        ]);

        $running_balance = $saldo_setelah;
    }

    // Update balance di tabel users
    $this->db->table('table_users')->where('id', $id_user)->update(['balance' => $running_balance]);

    return true;
}

// Di dalam HistorySaldoModel.php
public function get_history_with_user($filter = [])
{

 $orderBy = 'date_created';
 $orderDir = 'DESC';

    $builder = $this->db->table($this->table);
    $builder->select('table_history_saldo.*, table_users.username');
    $builder->join('table_users', 'table_users.id = table_history_saldo.id_user', 'left');
    
    if (!empty($filter)) {
        $builder->where($filter);
    }
    
    $builder->orderBy($orderBy, $orderDir);
    $query = $builder->get();
    $result = $query->getResultArray(); // array of array
    
    if (empty($result)) {
        return false;
    }
    
    // Agar bisa diakses sebagai $row->username dan $row['username']
    return array_map(fn($row) => new \ArrayObject($row, \ArrayObject::ARRAY_AS_PROPS), $result);
}

    public function get_all_by($filter)
    {
        $builder = $this->db->table($this->table);

         $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

              $end_result = $query->getResult();
              return new \ArrayObject((array)$end_result, \ArrayObject::ARRAY_AS_PROPS);

        }else {
            return false;
        }
    }

    public function get_saldo_by($filter){

        // isi saldo dan bonus itu nambah duit
        // tapi slain itu maka ngurangin duit
        

        $builder = $this->db->table($this->table);

        $pemasukan = $builder->selectSum('nominal')
        ->where($filter)
        ->whereIn('jenis', ['isi saldo', 'bonus'])
        ->get()
        ->getRow()->nominal ?? 0;

        $pengeluaran = $builder->selectSum('nominal')
        ->where($filter)
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