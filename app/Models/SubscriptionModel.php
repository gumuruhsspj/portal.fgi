<?php 
namespace App\Models;
use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $returnType     = 'object';
    
    protected $table = 'table_subscriptions';
    protected $primaryKey = 'id';
    // fillable?
    protected $allowedFields = [
        'jenis',
        'students_limit',
        'fitur_sertifikat',
        'fitur_diskusi_wa',
        'url_diskusi_wa',
        'fitur_loker',
        'fitur_materi_gratis',
        'fitur_aset_digital'
    ];    

}