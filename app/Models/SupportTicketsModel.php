<?php 
namespace App\Models;
use CodeIgniter\Model;

class SupportTicketsModel extends Model
{
    // Nama tabel di database
	protected $table      = 'table_support_tickets'; 

    // Kolom primary key
    protected $primaryKey = 'id'; 

    // Tipe data yang dikembalikan oleh metode find(), dll.
    //protected $returnType = 'array'; // Atau 'object' jika diinginkan

    // 💡 CI4 menggunakan $allowedFields sebagai pengganti $fillable
    // Ini adalah daftar kolom yang BISA diisi melalui metode save() atau insert()
    protected $allowedFields = [
        'email', 
        'customer_name', 
        'title', 
        'descriptions', 
        'status', 
        'ref_number'
    ]; 

    // Jika Anda menggunakan fitur Timestamps CI4, Anda akan menambahkan:
    /*
    protected $useTimestamps = true;
    protected $createdField  = 'date_created'; // Sesuaikan dengan nama kolom Anda
    protected $updatedField  = 'date_updated'; // Jika ada kolom ini
    */
    
    // Properti lain yang mungkin diperlukan:
    /*
    protected $useAutoIncrement = true;
    protected $protectFields    = true; // Defaultnya true (melindungi kolom dari mass assignment)
    */
}