<?php

namespace App\Models;

use CodeIgniter\Model;

class PemasukanModel extends Model
{
    protected $table      = 'table_pemasukan_instruktur';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_instruktur',
        'id_student',
        'id_materi',
        'id_pencairan',
        'nominal_bruto',
        'komisi_persen',
        'nominal_instruktur',
        'nominal_admin',
        'status'
    ];

    private $table_pencairan = 'table_pencairan_instruktur';
    private $table_users     = 'table_users';
    private $table_materi    = 'table_materi';

    /* ===================== LIST ===================== */

    public function get_all_by_instruktur($id_instruktur)
    {
        $b = $this->db->table($this->table . ' as p');
        $b->select('p.*, us.nama_lengkap AS nama_student, us.username AS username_student,
                    m.judul AS judul_materi, m.icon AS icon_materi');
        $b->join($this->table_users . ' as us', 'us.id = p.id_student', 'left');
        $b->join($this->table_materi . ' as m', 'm.id = p.id_materi', 'left');
        $b->where('p.id_instruktur', $id_instruktur);
        $b->orderBy('p.id', 'DESC');
        return $b->get()->getResult();
    }

    public function get_pencairan_by_instruktur($id_instruktur)
    {
        $b = $this->db->table($this->table_pencairan . ' as pc');
        $b->select('pc.*, ua.nama_lengkap AS nama_admin');
        $b->join($this->table_users . ' as ua', 'ua.id = pc.approved_by', 'left');
        $b->where('pc.id_instruktur', $id_instruktur);
        $b->orderBy('pc.id', 'DESC');
        return $b->get()->getResult();
    }

    public function get_pending_pencairan()
    {
        $b = $this->db->table($this->table_pencairan . ' as pc');
        $b->select('pc.*, ui.nama_lengkap AS nama_instruktur, ui.username AS username_instruktur,
                    ui.whatsapp AS wa_instruktur');
        $b->join($this->table_users . ' as ui', 'ui.id = pc.id_instruktur', 'left');
        $b->where('pc.status', 'pending');
        $b->orderBy('pc.id', 'DESC');
        return $b->get()->getResult();
    }

    /* ===================== SUMMARY ===================== */

    public function get_summary($id_instruktur)
    {
        $b = $this->db->table($this->table);
        $b->select('
            COALESCE(SUM(CASE WHEN status="pending"      THEN nominal_instruktur END),0) AS total_pending,
            COALESCE(SUM(CASE WHEN status="cair_request" THEN nominal_instruktur END),0) AS total_cair_request,
            COALESCE(SUM(CASE WHEN status="approved"     THEN nominal_instruktur END),0) AS total_approved,
            COALESCE(SUM(nominal_instruktur),0) AS total_all
        ');
        $b->where('id_instruktur', $id_instruktur);
        return $b->get()->getRow();
    }

    public function get_total_pending_only($id_instruktur)
    {
        $row = $this->db->table($this->table)
            ->selectSum('nominal_instruktur')
            ->where('id_instruktur', $id_instruktur)
            ->where('status', 'pending')
            ->get()->getRow();
        return (int) ($row->nominal_instruktur ?? 0);
    }

    /* ===================== INSERT (dipanggil dari checkout) ===================== */

    public function create_from_purchase(array $data)
    {
        $this->db->table($this->table)->insert($data);
        return $this->db->insertID();
    }

    /* ===================== PENCAIRAN ===================== */

    public function get_pencairan_by_id($id)
    {
        return $this->db->table($this->table_pencairan)->where('id', $id)->get()->getRow();
    }

    public function create_pencairan($id_instruktur)
    {
        $total = $this->get_total_pending_only($id_instruktur);
        if ($total <= 0) return false;

        $this->db->table($this->table_pencairan)->insert([
            'id_instruktur'   => $id_instruktur,
            'nominal_request' => $total,
            'status'          => 'pending',
            'tanggal_request' => date('Y-m-d H:i:s'),
        ]);
        $id_pencairan = $this->db->insertID();

        $this->db->table($this->table)
            ->where('id_instruktur', $id_instruktur)
            ->where('status', 'pending')
            ->update(['status' => 'cair_request', 'id_pencairan' => $id_pencairan]);

        return $id_pencairan;
    }

    /**
     * Approve pencairan. FIFO terhadap record pemasukan.
     * Kalau nominal_approved < total, sisa dikembalikan ke 'pending' (unlink pencairan).
     */
    public function approve_pencairan($id_pencairan, $nominal_approved, $admin_id)
    {
        $pencairan = $this->get_pencairan_by_id($id_pencairan);
        if (!$pencairan || $pencairan->status !== 'pending') return false;

        $nominal_approved = (int) $nominal_approved;
        if ($nominal_approved <= 0) return false;

        $rows = $this->db->table($this->table)
            ->where('id_pencairan', $id_pencairan)
            ->where('status', 'cair_request')
            ->orderBy('id', 'ASC')
            ->get()->getResult();

        $remaining = $nominal_approved;
        foreach ($rows as $p) {
            $nom = (int) $p->nominal_instruktur;
            if ($remaining >= $nom) {
                $this->db->table($this->table)->where('id', $p->id)
                    ->update(['status' => 'approved']);
                $remaining -= $nom;
            } elseif ($remaining > 0) {
                // partial: approve record ini, sisa nominal kecil dianggap admin ambil
                $this->db->table($this->table)->where('id', $p->id)
                    ->update(['status' => 'approved']);
                $remaining = 0;
            } else {
                // balikin ke pending biar bisa di-cairkan lagi nanti
                $this->db->table($this->table)->where('id', $p->id)
                    ->update(['status' => 'pending', 'id_pencairan' => null]);
            }
        }

        $this->db->table($this->table_pencairan)->where('id', $id_pencairan)->update([
            'status'           => 'approved',
            'nominal_approved' => $nominal_approved,
            'tanggal_approved' => date('Y-m-d H:i:s'),
            'approved_by'      => $admin_id,
        ]);
        return true;
    }

    public function reject_pencairan($id_pencairan, $admin_id, $keterangan = null)
    {
        $pencairan = $this->get_pencairan_by_id($id_pencairan);
        if (!$pencairan || $pencairan->status !== 'pending') return false;

        $this->db->table($this->table)
            ->where('id_pencairan', $id_pencairan)
            ->update(['status' => 'pending', 'id_pencairan' => null]);

        $this->db->table($this->table_pencairan)->where('id', $id_pencairan)->update([
            'status'           => 'rejected',
            'tanggal_approved' => date('Y-m-d H:i:s'),
            'approved_by'      => $admin_id,
            'keterangan'       => $keterangan,
        ]);
        return true;
    }
}
