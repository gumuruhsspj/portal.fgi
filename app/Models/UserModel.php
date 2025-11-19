<?php 
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    
	protected $table = "table_users";

    protected $primaryKey = 'id';
    // fillable?
    protected $allowedFields = [
        'nama_lengkap',
        'username',
        'email',
        'pass',
        'gender',
        'whatsapp',
        'subscription_id',
        'usertype',
        'propic'
    ];   

    public function get_all()
    {
        $builder = $this->db->table($this->table);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if($manyData > 0){

        	return $query->getResult();

        }else {
        	return false;
        }
    }

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

     public function get_by_username($username)
    {
        $builder = $this->db->table($this->table);

        $data = array(
            'username' => $username
        );

        $builder->where($data);

        $query = $builder->get();
         $manyData = $builder->countAllResults();

        if($manyData > 0){

            return $query->getResult()[0];

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

            return $query->getResult()[0];

        }else {
            return false;
        }

    }

    public function valid($data){
        $builder = $this->db->table($this->table);

        $builder->where($data);

        $manyData = $builder->countAllResults();

        if($manyData > 0){
            return true;
        }else {
            return false;
        }
    }

    public function insert_new($data){
        $query = $this->db->table($this->table)->insert($data);
         
         if($query){
            return true;
        }

        return false;
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
       
       if($query){
            return true;
        }

        return false;
    } 

  
}
