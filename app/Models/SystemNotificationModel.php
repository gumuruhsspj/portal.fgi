<?php 
namespace App\Models;
use CodeIgniter\Model;

class SystemNotificationModel extends Model
{
    
	private $table_name = "table_system_notification";

    protected $primaryKey = 'id'; 

    // Tipe data yang dikembalikan oleh metode find(), dll.
    //protected $returnType = 'array'; // Atau 'object' jika diinginkan

    // 💡 CI4 menggunakan $allowedFields sebagai pengganti $fillable
    // Ini adalah daftar kolom yang BISA diisi melalui metode save() atau insert()
    protected $allowedFields = [
        'messages', 
        'username', 
        'status'
    ];

    public function get_all()
    {
        $builder = $this->db->table($this->table_name);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

        	return $query->getResult();

        }else {
        	return false;
        }
    }

     public function get_all_by_username($username)
    {
        $builder = $this->db->table($this->table_name);

        $data = array(
            'username' => $username
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

     public function get_by($dataFilter)
    {
        $builder = $this->db->table($this->table_name);

        $builder->where($dataFilter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult()[0];

        }else {
            return false;
        }

    }

    public function valid($data){
        $builder = $this->db->table($this->table_name);

        $builder->where($data);

        $manyData = $builder->countAllResults();

        if($manyData > 0){
            return true;
        }else {
            return false;
        }
    }

    public function insert_new($data){
        $query = $this->db->table($this->table_name)->insert($data);
         
         if($query){
            return true;
        }

        return false;
    }

    public function update_existing($data, $id)
    {
        $query = $this->db->table($this->table_name)->update($data, array('id' => $id));
        
        if($query){
            return true;
        }

        return false;

    }

    public function delete_existing($id)
    {
        $query = $this->db->table($this->table_name)->delete(array('id' => $id));
       
       if($query){
            return true;
        }

        return false;
    } 

  
}
