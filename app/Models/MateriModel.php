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
        'biaya_pokok',
        'biaya_belajar_sendiri',
        'biaya_kasus_custom',
        'rilis_sertifikat',
        'paket_belajar_sendiri',
        'paket_bimbingan',
        'paket_kasus_custom'
    ];   

    private $table_student_materi_name = "table_student_materi";
    private $table_kategori_name = "table_kategori_materi";
    private $table_detail_materi_name = "table_detail_materi";
    private $table_comments_rating_name = "table_comments_rating";
    private $table_bab_materi_name = "table_bab_materi";
    private $table_pembahasan_materi_name = "table_pembahasan_materi";
    

    public function insert_new_pembahasan_bab($data){

        $hasil = false; 

        if(!empty($data)){
            $hasil = $this->db->table($this->table_bab_materi_name)->insert($data);
        }

        return $hasil;

    }

    public function insert_new_pembahasan($data){

        $hasil = false; 

        if(!empty($data)){
            $hasil = $this->db->table($this->table_pembahasan_materi_name)->insert($data);
        }

        return $hasil;

    }

    public function get_all_bab_by_materi_id($id){

        $builder = $this->db->table($this->table_bab_materi_name);

        $filter = array(
            'id' => $id
        );

        $builder->where($filter);

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

     

    // single data
    public function get_by_student($username = null)
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
            return $query->getRow();
        }else {
            return false;
        }
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
        $builder = $this->db->table($this->table_detail_materi_name);

        $builder->select('*');
        $builder->join($this->table_student_materi_name, $this->table_detail_materi_name.'.id_materi=' . $this->table_student_materi_name . '.id_materi');

        $filter = array(
            $this->table_student_materi_name.'.username' => $username
        );

        $builder->where($filter);

        $query = $builder->get();
         $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult();

        }else {
            return false;
        }
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
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getRow();

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
