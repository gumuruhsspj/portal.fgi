<?php 
namespace App\Models;
use CodeIgniter\Model;

class MateriModel extends Model
{
    
	protected $table = "table_materi";

    protected $primaryKey = 'id';
    // fillable?
    protected $allowedFields = [
        'icon',
        'judul',
        'kategori',
        'deskripsi',
        'attachment',
        'username',
        'url',
        'status',
        'biaya_pokok',
        'biaya_belajar_sendiri',
        'biaya_kasus_custom',
        'rilis_sertifikat',
        'paket_belajar_sendiri',
        'paket_bimbingan',
        'paket_kasus_custom',
        'id_materi'
    ];   

    private $table_materi_custom_name = "table_materi_custom";
    private $table_student_materi_name = "table_student_materi";
    private $table_kategori_name = "table_kategori_materi";
    
    private $table_comments_rating_name = "table_comments_rating";
    private $table_bab_materi_name = "table_bab_materi";
    private $table_quiz_materi_name = "table_quiz_materi";
    private $table_pembahasan_materi_name = "table_pembahasan_materi";
    private $table_pembahasan_custom_name = "table_pembahasan_custom";

public function add_student_materi($data){

    $builder = $this->db->table($this->table_student_materi_name);
    return $builder->insert($data);

}

    public function get_highest_ordering_index($id_bab){

        $builder = $this->db->table($this->table_pembahasan_materi_name);

        $builder->selectMax('ordering_index');

        $filter = array(
            'id_bab' => $id_bab
        );

        $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getRow();

        }else {
            return false;
        }

    }

    public function insert_new_pembahasan_bab($data){

        $hasil = false; 

        if(!empty($data)){
            $hasil = $this->db->table($this->table_bab_materi_name)->insert($data);
        }

        return $hasil;

    }

    public function update_existing_pembahasan($data, $id)
    {
        $query = $this->db->table($this->table_pembahasan_materi_name)->update($data, array('id' => $id));
          if($query){
            return true;
        }

        return false;
    }

    public function get_navigasi_pembahasan($id_bab, $current_ordering_index) {
    $builder = $this->db->table($this->table_pembahasan_materi_name);

    // Cari yang Sebelumnya (Back)
    $prev = $this->db->table($this->table_pembahasan_materi_name)
        ->where('id_bab', $id_bab)
        ->where('ordering_index <', $current_ordering_index)
        ->orderBy('ordering_index', 'DESC')
        ->get()->getRow();

    // Cari yang Selanjutnya (Next)
    $next = $this->db->table($this->table_pembahasan_materi_name)
        ->where('id_bab', $id_bab)
        ->where('ordering_index >', $current_ordering_index)
        ->orderBy('ordering_index', 'ASC')
        ->get()->getRow();

    return [
        'prev_id' => $prev ? $prev->id : null,
        'next_id' => $next ? $next->id : null
    ];
}

    public function get_pembahasan_by($filter){
        $builder = $this->db->table($this->table_pembahasan_materi_name);

        $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getRow();

        }else {
            return false;
        }
    }

    public function insert_new_pembahasan($data){

        $hasil = false; 

        if(!empty($data)){
            $hasil = $this->db->table($this->table_pembahasan_materi_name)->insert($data);

        }

        if($hasil){
            return $this->db->insertID();
        }

        return $hasil;

    }

    public function delete_existing_pembahasan($id)
    {
        $query = $this->db->table($this->table_pembahasan_materi_name)->delete(array('id' => $id));
        return $query;
    }

    public function delete_existing_bab($id)
    {
        $query = $this->db->table($this->table_bab_materi_name)->delete(array('id' => $id));

        // delete jga dari table pembahasan
        $query = $this->db->table($this->table_pembahasan_materi_name)->delete(array('id_bab' => $id));

        return $query;
    }

    public function update_existing_bab($data, $id)
    {
        $query = $this->db->table($this->table_bab_materi_name)->update($data, array('id' => $id));
          if($query){
            return true;
        }

        return false;
    }

    public function get_all_quiz_by_materi_id($id){

        $builder = $this->db->table($this->table_quiz_materi_name);

        $filter = array(
            'id_materi' => $id
        );

        $builder->where($filter);
        //$builder->orderBy('ordering_index', 'ASC');

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }

    }

    public function get_all_pembahasan_by_bab_id($id){

        $builder = $this->db->table($this->table_pembahasan_materi_name);

        $filter = array(
            'id_bab' => $id
        );

        $builder->where($filter);
        $builder->orderBy('ordering_index', 'ASC');

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }

    }

    public function get_all_bab_by_materi_id($id){

        // returned value is object
        // id, id_materi, judul, deskripsi, and jumlah pembahasan only

        $builder = $this->db->table($this->table_bab_materi_name);

        // Join table_bab_materi with table_pembahasan
        $builder->select('table_bab_materi.id, table_bab_materi.id_materi, table_bab_materi.judul as judul, 
        table_bab_materi.deskripsi as deskripsi, 
        COUNT(table_pembahasan_materi.id) as jumlah_pembahasan');
        
        $builder->join($this->table_pembahasan_materi_name, 
        'table_pembahasan_materi.id_bab = table_bab_materi.id', 'left');

        $filter = array(
            'table_bab_materi.id_materi' => $id
        );

        $builder->where($filter);
        $builder->groupBy('table_bab_materi.id');

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }

    }

    public function insert_new_comments_rating($data){
        /* kirim ini ke table comments_rating */
        $hasil = false; 

        if(!empty($data)){
            $hasil = $this->db->table($this->table_comments_rating_name)->insert($data);
        }

        return $hasil;
    }

    public function get_student_materi_by($filter){

        $builder = $this->db->table($this->table_student_materi_name);

        $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getRow();

        }else {
            return false;
        }
    }

    public function get_all($username = null)
{
    $builder = $this->db->table($this->table);

    // 1. Tentukan SELECT, JOIN, dan WHERE (tanpa eksekusi)
    $builder->select('table_materi.id, table_materi.judul, table_materi.kategori, table_materi.icon, table_materi.deskripsi, table_materi.attachment, table_materi.username, table_materi.url, table_materi.date_created, table_materi.date_modified, COUNT(table_comments_rating.id) as total_comments');
    $builder->join($this->table_comments_rating_name, 'table_comments_rating.id_materi = table_materi.id', 'left');
    
    // Perbaikan: Tambahkan semua field non-agregat ke GROUP BY
    $builder->groupBy('table_materi.id, table_materi.judul, table_materi.kategori, table_materi.icon, table_materi.deskripsi, table_materi.attachment, table_materi.username, table_materi.url, table_materi.date_created, table_materi.date_modified');

    if ($username != null) {
        $builder->where('table_materi.username', $username);
    }

    // 2. Eksekusi query
    $query = $builder->get();

    // 3. Cek hasil dari objek $query yang sudah dieksekusi
    $results = $query->getResult();
    
    // Gunakan fungsi count() dari array hasil
    if (count($results) > 0) {
        return $results;
    } else {
        return false;
    }
}

public function get_all_custom($username = null, $id_materi = null)
{
    $builder = $this->db->table($this->table . ' as tm');

    $tmc = $this->table_materi_custom_name . ' as tmc';

    // 1. Tentukan SELECT, JOIN, dan WHERE (tanpa eksekusi)
    $builder->select('tmc.id, tm.judul, tm.kategori, tmc.nama_template, tmc.date_created');
    $builder->join($tmc, 'tmc.id_materi = tm.id', 'inner');
    
    if ($username != null) {
        $builder->where('tm.username', $username);
    }

    if($id_materi != null){
        $builder->where('tm.id', $id_materi);
    }

    // 2. Eksekusi query
    $query = $builder->get();

    // 3. Cek hasil dari objek $query yang sudah dieksekusi
    $results = $query->getResult();
    
    // Gunakan fungsi count() dari array hasil
    if (count($results) > 0) {
        return $results;
    } else {
        return false;
    }
}

    
     public function get_all_comments_rating($id_materi)
    {
        $builder = $this->db->table($this->table_comments_rating_name);

        if($id_materi != null){
            $builder->where('id_materi', $id_materi);
        }
        
      
        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }
    }

    public function update_status($id_materi, $status){

         $builder = $this->db->table($this->table_student_materi_name); 

            $data = [
                'status' => $status
            ];

            $filter = [
                'id_materi' => $id_materi
            ];
  
        $query = $builder->update($data, $filter);
          if($query){
            return true;
        }

        return false;

    } 

    // single data
   public function get_subscribed_materi($id_materi, $username) {
    // 1. Mulai dari tabel materi (sebagai tabel utama A)
    $builder = $this->db->table($this->table); // Ini table_materi

    // 2. Pilih kolom yang mau diambil
    // Ambil semua dari materi, dan beberapa dari student_materi (misal: paket, status, tgl_beli)
    $builder->select($this->table . '.*, ' . $this->table_student_materi_name . '.status, ' .
    $this->table_student_materi_name . '.paket, ' .
    $this->table_student_materi_name . '.url_alive as custom_url_alive');

    // 3. Join ke table_student_materi (Tabel B)
    // Relasi: table_materi.id = table_student_materi.id_materi
    $builder->join($this->table_student_materi_name, 
                   $this->table . '.id = ' . $this->table_student_materi_name . '.id_materi');
    

    // 4. Filter spesifik untuk user dan materi tersebut
    $filter = [
        $this->table_student_materi_name . '.id_materi' => $id_materi,
        $this->table_student_materi_name . '.username'  => $username
    ];

    $builder->where($filter);

    // 5. Eksekusi
    $query = $builder->get();

    if ($query->getNumRows() > 0) {
        $end_result = $query->getRow();
        
        // Tetap pakai ArrayObject supaya legacy code -> vs [] aman
        return new \ArrayObject((array)$end_result, \ArrayObject::ARRAY_AS_PROPS);
    }

    return false;
}

     public function get_all_by_student($username = null)
    {
        $builder = $this->db->table($this->table);

        $builder->select('*');
        $builder->join($this->table_student_materi_name, $this->table.'.id=' . $this->table_student_materi_name . '.id_materi');

        $data = array(
            $this->table_student_materi_name . '.username' => $username
        );

        $builder->where($data);

        $query = $builder->get();
         $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }
    }

   public function get_all_detail_by_username($username)
{
    // Definisi nama tabel agar dinamis
    $tbl_pm = $this->table_pembahasan_materi_name; // table_pembahasan_materi
    $tbl_sm = $this->table_student_materi_name;    // table_student_materi
    $tbl_m  = $this->table;                        // table_materi

    $builder = $this->db->table($tbl_pm);

    // 1. Pilih kolom: Semua dari pembahasan, ambil judul dari materi
    $builder->select($tbl_pm . '.judul, ' . $tbl_pm . '.id as id_pembahasan, ' . $tbl_m . '.judul as nama_materi, ' . $tbl_m . '.deskripsi as deskripsi_utama, '  . $tbl_m . '.attachment, ' . $tbl_sm . '.status');
    
    // 2. Join Pertama: Pembahasan ke Materi (untuk dapetin detail materi)
    $builder->join($tbl_m, $tbl_m . '.id = ' . $tbl_pm . '.id_materi');

    // 3. Join Kedua: Materi ke Student Materi (untuk filter berdasarkan hak akses student)
    $builder->join($tbl_sm, $tbl_sm . '.id_materi = ' . $tbl_m . '.id');

    // 4. Filter berdasarkan username yang ada di table_student_materi
    $filter = [
        $tbl_sm . '.username' => $username
    ];

    $builder->where($filter);

    // Urutkan berdasarkan ordering_index biar rapi (opsional)
    $builder->orderBy($tbl_pm . '.ordering_index', 'ASC');

    $query = $builder->get();

    // Cek data pakai getNumRows (aman dari reset builder)
    if ($query->getNumRows() > 0) {
        $results = $query->getResult();
        
        // Bungkus ke ArrayObject biar legacy code $row['field'] dan $row->field aman
        $final_data = [];
        foreach ($results as $row) {
            $final_data[] = new \ArrayObject((array)$row, \ArrayObject::ARRAY_AS_PROPS);
        }

        return $final_data;
    }

    return false;
}

      public function get_all_kategori($username = null)
    {
        $builder = $this->db->table($this->table_kategori_name);

        if($username != null){
            $builder->where('username', $username);
        }
        
        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }
    }

  public function get_by($dataFilter)
{
    $builder = $this->db->table($this->table);
    $builder->where($dataFilter);
    $query = $builder->get();
    $row = $query->getRow();

    if ($row === null) {
        return false;
    }

    return new \ArrayObject((array) $row, \ArrayObject::ARRAY_AS_PROPS);
}

        public function get_all_by($dataFilter)
    {
        $builder = $this->db->table($this->table);

        $builder->where($dataFilter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }

    }

    public function insert_new($data){
        $query = $this->db->table($this->table)->insert($data);
        return $query;
    }

    public function insert_new_kategori($data){
        $query = $this->db->table($this->table_kategori_name)->insert($data);
        return $query;
    }

    public function update_existing($data, $id)
    {
        $query = $this->db->table($this->table)->update($data, array('id' => $id));
          if($query){
            return true;
        }

        return false;
    }

    public function delete_existing($id)
    {
        $query = $this->db->table($this->table)->delete(array('id' => $id));
        return $query;
    } 

     public function delete_existing_where_kategori($filter)
    {
        $query = $this->db->table($this->table_kategori_name)->delete($filter);
        return $query;
    } 

  
}
