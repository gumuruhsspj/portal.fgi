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
        'url_alive',
        'id_materi_custom',
        'nama_template',
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
    private $table_quiz_attempts_name = "table_quiz_attempts";
    private $table_quiz_answers_name  = "table_quiz_answers";

    private $table_comments_rating_name = "table_comments_rating";
    private $table_bab_materi_name = "table_bab_materi";
    private $table_quiz_materi_name = "table_quiz_materi";
    private $table_pembahasan_materi_name = "table_pembahasan_materi";
    private $table_pembahasan_custom_name = "table_pembahasan_custom";

    private $table_quiz_groups_name        = "table_quiz_groups";
    private $table_certificate_scores_name = "table_certificate_scores";
    private $table_certificate_templates_name = "table_certificate_templates";


    /* ===================== CERTIFICATE TEMPLATES ===================== */

    public function get_cert_templates($id_materi = null)
    {
        $b = $this->db->table($this->table_certificate_templates_name . ' as t');
        $b->select('t.*, m.judul as judul_materi');
        $b->join($this->table . ' as m', 'm.id = t.id_materi', 'left');
        if ($id_materi) $b->where('t.id_materi', $id_materi);
        $b->orderBy('t.id_materi', 'ASC')->orderBy('t.id', 'DESC');
        $rows = $b->get()->getResult();
        return count($rows) > 0 ? $rows : [];
    }

    public function get_cert_template_by_id($id)
    {
        $b = $this->db->table($this->table_certificate_templates_name);
        $row = $b->where('id', $id)->get()->getRow();
        return $row ?: false;
    }

    public function insert_cert_template($data)
    {
        if (empty($data)) return false;
        $this->db->table($this->table_certificate_templates_name)->insert($data);
        return $this->db->insertID();
    }

    public function update_cert_template($data, $id)
    {
        return $this->db->table($this->table_certificate_templates_name)
            ->update($data, ['id' => $id]) ? true : false;
    }

    public function delete_cert_template($id)
    {
        return $this->db->table($this->table_certificate_templates_name)
            ->delete(['id' => $id]);
    }

    /**
     * Cari template yang cocok untuk 1 attempt (user + materi + paket).
     */
    public function find_cert_template_for_attempt($attempt)
    {
        // Cari student_materi untuk tahu paket + id_custom_materi
        $b = $this->db->table($this->table_student_materi_name);
        $sm = $b->where('id_user', $attempt->id_user)
            ->where('id_materi', $attempt->id_materi)
            ->get()->getRow();

        if (!$sm) return false;

        $paket = $sm->paket;
        $cid   = $sm->id_custom_materi ?? null;

        $b2 = $this->db->table($this->table_certificate_templates_name);
        $b2->where('id_materi', $attempt->id_materi)
            ->where('paket', $paket)
            ->where('status', 'active');

        if ($paket === 'paket_kasus_custom' && $cid) {
            $b2->where('id_custom_materi', $cid);
        } else {
            $b2->where('id_custom_materi IS NULL', null, false);
        }

        $row = $b2->orderBy('id', 'DESC')->limit(1)->get()->getRow();
        return $row ?: false;
    }

    /* ===================== QUIZ GROUPS ===================== */

    public function get_all_quiz_groups($id_materi)
    {
        // Guard: kalau tabel belum dimigrasi, jangan crash
        if (!$this->db->tableExists($this->table_quiz_groups_name)) {
            return false;
        }
        $b = $this->db->table($this->table_quiz_groups_name);
        $b->where('id_materi', $id_materi);
        $b->orderBy('ordering_index', 'ASC')->orderBy('id', 'ASC');
        $rows = $b->get()->getResult();
        return count($rows) > 0 ? $rows : false;
    }

    public function get_quiz_group_by($filter)
    {
        $b = $this->db->table($this->table_quiz_groups_name);
        $b->where($filter);
        $row = $b->get()->getRow();
        return $row ?: false;
    }

    public function insert_new_quiz_group($data)
    {
        if (empty($data)) return false;
        if (!$this->db->tableExists($this->table_quiz_groups_name)) return false;
        $this->db->table($this->table_quiz_groups_name)->insert($data);
        return $this->db->insertID();
    }

    public function update_existing_quiz_group($data, $id)
    {
        return $this->db->table($this->table_quiz_groups_name)
            ->update($data, ['id' => $id]) ? true : false;
    }

    public function delete_existing_quiz_group($id)
    {
        // FK akan set NULL id_group di table_quiz_materi
        return $this->db->table($this->table_quiz_groups_name)
            ->delete(['id' => $id]);
    }

    public function get_max_ordering_quiz_group($id_materi)
    {
        if (!$this->db->tableExists($this->table_quiz_groups_name)) return 0;
        $row = $this->db->table($this->table_quiz_groups_name)
            ->selectMax('ordering_index')
            ->where('id_materi', $id_materi)
            ->get()->getRow();
        return ($row && $row->ordering_index) ? (int) $row->ordering_index : 0;
    }

    public function assign_quiz_to_group($id_quiz, $id_group)
    {
        // Kalau kolom id_group belum ada → skip tanpa error
        if (!$this->db->fieldExists('id_group', $this->table_quiz_materi_name)) {
            return false;
        }
        return $this->db->table($this->table_quiz_materi_name)
            ->update(['id_group' => $id_group ?: null], ['id' => $id_quiz]);
    }

/* ===================== CERTIFICATE SCORING ===================== */

    /**
     * Hitung scoring per group dari satu attempt.
     * Hasil: array of object { id_group, group_name, total_questions,
     *                          correct_count, avg_score }
     */
    public function get_group_scores_for_attempt($id_attempt)
    {
        $tbl_g = $this->table_quiz_groups_name;
        $tbl_q = $this->table_quiz_materi_name;
        $tbl_a = $this->table_quiz_answers_name;

        $b = $this->db->table($tbl_g . ' as g');
        $b->select('
        g.id as id_group,
        g.nama as group_name,
        g.ordering_index,
        COUNT(a.id) as total_questions,
        SUM(CASE WHEN a.is_correct = 1 THEN 1 ELSE 0 END) as correct_count,
        ROUND(AVG(COALESCE(a.score, 0)), 2) as avg_score
    ');
        $b->join($tbl_q . ' as q', 'q.id_group = g.id', 'left');
        $b->join($tbl_a . ' as a', 'a.id_quiz = q.id', 'left');
        $b->where('a.id_attempt', $id_attempt);
        $b->groupBy('g.id, g.nama, g.ordering_index');
        $b->orderBy('g.ordering_index', 'ASC');

        return $b->get()->getResult();
    }

    /**
     * Persist scoring ke cache (dipanggil setelah attempt graded).
     */
    public function persist_certificate_scores($attempt, $rows)
    {
        $this->db->table($this->table_certificate_scores_name)
            ->where('id_attempt', $attempt->id)
            ->delete();

        foreach ($rows as $r) {
            $this->db->table($this->table_certificate_scores_name)->insert([
                'id_attempt'      => $attempt->id,
                'id_user'         => $attempt->id_user,
                'id_materi'       => $attempt->id_materi,
                'id_group'        => $r->id_group,
                'group_name'      => $r->group_name,
                'total_questions' => (int) $r->total_questions,
                'correct_count'   => (int) $r->correct_count,
                'avg_score'       => (float) $r->avg_score,
            ]);
        }
    }

    public function get_certificate_scores($id_attempt)
    {
        $b = $this->db->table($this->table_certificate_scores_name);
        $b->where('id_attempt', $id_attempt);
        $b->orderBy('id', 'ASC');
        $rows = $b->get()->getResult();
        return count($rows) > 0 ? $rows : false;
    }

    /**
     * Ambil seluruh attempt yang sudah graded (untuk list certificate admin).
     */
    public function get_graded_attempts_with_user($id_materi = null)
    {
        $tbl_qa = $this->table_quiz_attempts_name . ' as qa';
        $tbl_u  = 'table_users as u';
        $tbl_m  = $this->table . ' as m';

        $b = $this->db->table($tbl_qa);
        $b->select('qa.*, u.username, u.nama_lengkap, u.email,
                m.judul as judul_materi, m.icon, m.rilis_sertifikat');
        $b->join($tbl_u, 'u.id = qa.id_user', 'left');
        $b->join($tbl_m, 'm.id = qa.id_materi', 'left');
        $b->where('qa.status', 'graded');
        if (!empty($id_materi)) $b->where('qa.id_materi', $id_materi);
        $b->orderBy('qa.date_graded', 'DESC');

        return $b->get()->getResult();
    }

    public function get_student_materi_progress($id_user)
    {
        $tbl = $this->table_student_materi_name;

        $b = $this->db->table($tbl);
        $b->select("
        COUNT(*) as total_enrolled,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as total_completed,
        SUM(CASE WHEN status = 'in progress' THEN 1 ELSE 0 END) as total_progress
    ");
        $b->where('id_user', $id_user);

        $row = $b->get()->getRow();

        $total     = (int) ($row->total_enrolled ?? 0);
        $completed = (int) ($row->total_completed ?? 0);

        $percentage = $total > 0 ? round(($completed / $total) * 100, 0) : 0;

        return [
            'total_enrolled'  => $total,
            'total_completed' => $completed,
            'total_progress'  => (int) ($row->total_progress ?? 0),
            'percentage'      => $percentage,
        ];
    }

    public function get_quiz_attempts_by_user($id_user)
    {
        $tbl_qa = $this->table_quiz_attempts_name . ' as qa';
        $tbl_m  = $this->table . ' as m';

        $b = $this->db->table($tbl_qa);
        $b->select('
        qa.id,
          qa.id_user, 
        qa.id_materi,
        qa.total_questions,
        qa.pg_questions,
        qa.essay_questions,
        qa.auto_score,
        qa.final_score,
        qa.status,
        qa.date_created,
        qa.date_graded,
        m.judul as judul_materi,
        m.icon,
        m.rilis_sertifikat
    ');
        $b->join($tbl_m, 'm.id = qa.id_materi', 'left');
        $b->where('qa.id_user', $id_user);
        $b->orderBy('qa.date_created', 'DESC');

        $rows = $b->get()->getResult();
        return count($rows) > 0 ? $rows : [];
    }

    public function get_quiz_attempts_map_by_user($id_user)
    {
        $tbl_qa = $this->table_quiz_attempts_name . ' as qa';
        $tbl_m  = $this->table . ' as m';

        $b = $this->db->table($tbl_qa);
        $b->select('qa.*, m.rilis_sertifikat');
        $b->join($tbl_m, 'm.id = qa.id_materi', 'left');
        $b->where('qa.id_user', $id_user);

        $rows = $b->get()->getResult();

        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r->id_materi] = $r;
        }
        return $map;
    }

    public function get_all_quiz_attempts_with_relations($id_materi = null)
    {
        $tbl_qa = $this->table_quiz_attempts_name . ' as qa';
        $tbl_u  = 'table_users as u';
        $tbl_m  = $this->table . ' as m';

        $b = $this->db->table($tbl_qa);
        $b->select('qa.*, u.username, u.nama_lengkap, u.email, m.judul as judul_materi, m.icon');
        $b->join($tbl_u, 'u.id = qa.id_user', 'left');
        $b->join($tbl_m, 'm.id = qa.id_materi', 'left');
        if (!empty($id_materi)) {
            $b->where('qa.id_materi', $id_materi);
        }
        $b->orderBy('qa.date_created', 'DESC');

        return $b->get()->getResult();
    }

    public function get_quiz_attempt_by_materi_and_user($id_materi, $id_user)
    {
        $b = $this->db->table($this->table_quiz_attempts_name);
        $b->where('id_materi', $id_materi)->where('id_user', $id_user);
        $row = $b->get()->getRow();
        return $row ?: false;
    }

    public function update_quiz_answer($id, $data)
    {
        return $this->db->table($this->table_quiz_answers_name)->update($data, ['id' => $id]);
    }

    public function get_materi_with_quiz_attempts()
    {
        $tbl_qa = $this->table_quiz_attempts_name . ' as qa';
        $tbl_m  = $this->table . ' as m';

        $b = $this->db->table($tbl_qa);
        $b->select('qa.id_materi, m.judul as judul_materi, m.icon, COUNT(qa.id) as total_submissions');
        $b->join($tbl_m, 'm.id = qa.id_materi', 'left');
        $b->groupBy('qa.id_materi, m.judul, m.icon');
        $b->orderBy('m.judul', 'ASC');

        return $b->get()->getResult();
    }

    public function get_quiz_attempt_by_user($id_user, $id_materi)
    {
        $b = $this->db->table($this->table_quiz_attempts_name);
        $b->where('id_user', $id_user)->where('id_materi', $id_materi);
        $b->orderBy('id', 'DESC')->limit(1);
        $row = $b->get()->getRow();
        return $row ?: false;
    }

    public function get_quiz_attempt_by_id($id)
    {
        $b = $this->db->table($this->table_quiz_attempts_name);
        $row = $b->where('id', $id)->get()->getRow();
        return $row ?: false;
    }

    /* ===================== CERTIFICATE TOKEN ===================== */

    /**
     * Ambil token existing atau generate baru untuk attempt ini.
     * Return token (string 32 char hex) atau false kalau gagal.
     */
    public function ensure_certificate_token($attempt_id)
    {
        $b = $this->db->table($this->table_quiz_attempts_name);
        $row = $b->select('certificate_token')->where('id', $attempt_id)->get()->getRow();

        if (!$row) return false;

        // Kalau sudah ada, langsung return
        if (!empty($row->certificate_token)) {
            return $row->certificate_token;
        }

        // Generate token baru — retry kalau collision (super jarang)
        $token = false;
        for ($i = 0; $i < 5; $i++) {
            $candidate = bin2hex(random_bytes(16)); // 32 char hex
            $exists = $this->db->table($this->table_quiz_attempts_name)
                ->where('certificate_token', $candidate)
                ->countAllResults();
            if ($exists === 0) {
                $token = $candidate;
                break;
            }
        }

        if (!$token) return false;

        $ok = $this->db->table($this->table_quiz_attempts_name)
            ->update(['certificate_token' => $token], ['id' => $attempt_id]);

        return $ok ? $token : false;
    }

    public function has_cert_template_for_attempt($attempt)
    {
        return $this->find_cert_template_for_attempt($attempt) !== false;
    }

    /**
     * Cari attempt berdasarkan token. Return object attempt atau false.
     */
    public function get_attempt_by_certificate_token($token)
    {
        if (empty($token) || !preg_match('/^[a-f0-9]{32}$/', $token)) {
            return false;
        }
        $b = $this->db->table($this->table_quiz_attempts_name);
        $row = $b->where('certificate_token', $token)->get()->getRow();
        return $row ?: false;
    }

    public function create_quiz_attempt($data)
    {
        $this->db->table($this->table_quiz_attempts_name)->insert($data);
        return $this->db->insertID();
    }

    public function update_quiz_attempt($id, $data)
    {
        return $this->db->table($this->table_quiz_attempts_name)->update($data, ['id' => $id]);
    }

    public function insert_quiz_answer($data)
    {
        return $this->db->table($this->table_quiz_answers_name)->insert($data);
    }

    public function get_quiz_answers($id_attempt)
    {
        $b = $this->db->table($this->table_quiz_answers_name . ' as qa');
        $b->select('qa.*, q.pertanyaan, q.jenis as jenis_soal, q.opsi_a, q.opsi_b, q.opsi_c, q.opsi_d, q.final_answer, q.keterangan');
        $b->join($this->table_quiz_materi_name . ' as q', 'q.id = qa.id_quiz', 'left');
        $b->where('qa.id_attempt', $id_attempt);
        $b->orderBy('q.ordering_index', 'ASC');
        $rows = $b->get()->getResult();
        return count($rows) > 0 ? $rows : false;
    }

    public function add_student_materi($data)
    {

        $builder = $this->db->table($this->table_student_materi_name);
        return $builder->insert($data);
    }

    public function get_custom_highest_ordering_index($id_bab, $id_custom)
    {

        $builder = $this->db->table($this->table_pembahasan_custom_name);

        $builder->selectMax('ordering_index');

        $filter = array(
            'id_bab' => $id_bab,
            'id_materi_custom' => $id_custom
        );

        $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            $end_result =  $query->getRow();
            return new \ArrayObject((array) $end_result, \ArrayObject::ARRAY_AS_PROPS);
        } else {
            return false;
        }
    }

    public function get_highest_ordering_index($id_bab)
    {

        $builder = $this->db->table($this->table_pembahasan_materi_name);

        $builder->selectMax('ordering_index');

        $filter = array(
            'id_bab' => $id_bab
        );

        $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            $end_result =  $query->getRow();
            return new \ArrayObject((array) $end_result, \ArrayObject::ARRAY_AS_PROPS);
        } else {
            return false;
        }
    }

    public function insert_new_pembahasan_bab($data)
    {
        if (!empty($data)) {
            $this->db->table($this->table_bab_materi_name)->insert($data);
            return $this->db->insertID();
        }
        return false;
    }



    public function update_existing_pembahasan($data, $id)
    {
        $query = $this->db->table($this->table_pembahasan_materi_name)->update($data, array('id' => $id));
        if ($query) {
            return true;
        }

        return false;
    }

    public function get_navigasi_pembahasan($id_materi, $current_pembahasan_id, $id_materi_custom = null)
    {
        $tbl_pm  = $id_materi_custom
            ? $this->table_pembahasan_custom_name
            : $this->table_pembahasan_materi_name;

        $tbl_bab = $this->table_bab_materi_name;

        $builder = $this->db->table($tbl_pm . ' as pm');
        $builder->select('pm.id, pm.ordering_index, pm.id_bab');
        $builder->join($tbl_bab . ' as bab', 'bab.id = pm.id_bab', 'inner');

        if ($id_materi_custom) {
            $builder->where('pm.id_materi_custom', $id_materi_custom);
        } else {
            $builder->where('pm.id_materi', $id_materi);
        }

        // Urutan lintas bab: bab dulu, baru ordering_index dalam bab
        $builder->orderBy('bab.id', 'ASC');
        $builder->orderBy('pm.ordering_index', 'ASC');

        $rows = $builder->get()->getResult();

        $ids         = array_map(fn($r) => (int) $r->id, $rows);
        $current_idx = array_search((int) $current_pembahasan_id, $ids, true);

        return [
            'prev_id' => ($current_idx !== false && $current_idx > 0)
                ? $ids[$current_idx - 1]
                : null,
            'next_id' => ($current_idx !== false && $current_idx < count($ids) - 1)
                ? $ids[$current_idx + 1]
                : null,
        ];
    }

    public function get_pembahasan_by($filter)
    {
        $builder = $this->db->table($this->table_pembahasan_materi_name);

        $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            return $query->getRow();
        } else {
            return false;
        }
    }

    public function insert_new_custom_pembahasan($data)
    {

        $hasil = false;

        if (!empty($data)) {
            $hasil = $this->db->table($this->table_pembahasan_custom_name)->insert($data);
        }

        if ($hasil) {
            return $this->db->insertID();
        }

        return $hasil;
    }

    public function insert_new_pembahasan($data)
    {

        $hasil = false;

        if (!empty($data)) {
            $hasil = $this->db->table($this->table_pembahasan_materi_name)->insert($data);
        }

        if ($hasil) {
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
        // 1. Ambil data bab dulu untuk tahu id_materi-nya
        $bab = $this->db->table($this->table_bab_materi_name)
            ->where('id', $id)
            ->get()
            ->getRow();

        if (!$bab) {
            return false;
        }

        // 2. Hapus pembahasan yang terikat ke bab ini
        $this->db->table($this->table_pembahasan_materi_name)
            ->where('id_bab', $id)
            ->delete();

        // 3. Hapus pembahasan orphaned (id_bab = 0) untuk materi yang sama
        $this->db->table($this->table_pembahasan_materi_name)
            ->where('id_materi', $bab->id_materi)
            ->where('id_bab', 0)
            ->delete();

        // 4. Hapus custom pembahasan jika ada
        if (!empty($bab->id_materi_custom)) {
            $this->db->table($this->table_pembahasan_custom_name)
                ->where('id_bab', $id)
                ->delete();

            $this->db->table($this->table_pembahasan_custom_name)
                ->where('id_materi_custom', $bab->id_materi_custom)
                ->where('id_bab', 0)
                ->delete();
        }

        // 5. Terakhir hapus bab-nya
        return $this->db->table($this->table_bab_materi_name)
            ->where('id', $id)
            ->delete();
    }

    public function update_existing_bab($data, $id)
    {
        $query = $this->db->table($this->table_bab_materi_name)->update($data, array('id' => $id));
        if ($query) {
            return true;
        }

        return false;
    }

    public function get_bab_list_with_pembahasan($id_materi, $custom_id = null)
    {
        $builder = $this->db->table($this->table_bab_materi_name);
        $builder->where('id_materi', $id_materi);

        if ($custom_id !== null) {
            $builder->where('id_materi_custom', $custom_id);
        } else {
            $builder->where('id_materi_custom IS NULL', null, false);
        }

        $builder->orderBy('id', 'ASC');
        $bab_list = $builder->get()->getResult();

        $result = [];
        foreach ($bab_list as $bab) {
            if ($custom_id !== null) {
                $pb = $this->db->table($this->table_pembahasan_custom_name);
                $pb->where('id_bab', $bab->id);
                $pb->where('id_materi_custom', $custom_id);
            } else {
                $pb = $this->db->table($this->table_pembahasan_materi_name);
                $pb->where('id_bab', $bab->id);
            }
            $pb->orderBy('ordering_index', 'ASC');
            $pembahasan = $pb->get()->getResult();

            $result[] = (object) [
                'id'          => $bab->id,
                'judul'       => $bab->judul,
                'deskripsi'   => $bab->deskripsi,
                'pembahasan'  => $pembahasan,
            ];
        }

        return $result;
    }

    public function get_orphaned_pembahasan($id_materi, $cid = null)
    {
        if ($cid != null) {
            $builder = $this->db->table($this->table_pembahasan_custom_name);
            $builder->where('id_materi_custom', $cid);
            $builder->where('id_bab', 0);
        } else {
            $builder = $this->db->table($this->table_pembahasan_materi_name);
            $builder->where('id_materi', $id_materi);
            $builder->where('id_bab', 0);
        }

        $builder->orderBy('ordering_index', 'ASC');
        $query = $builder->get();
        $results = $query->getResult();

        return count($results) > 0 ? $results : false;
    }

    public function get_all_quiz_by_materi_id($id)
    {
        if (empty($id)) {
            return false;
        }

        $builder = $this->db->table($this->table_quiz_materi_name);
        $builder->where('id_materi', $id);
        $builder->orderBy('ordering_index', 'ASC');
        $builder->orderBy('id', 'ASC');

        // AMAN: ambil hasil LANGSUNG tanpa countAllResults() perantara
        $rows = $builder->get()->getResult();

        return count($rows) > 0 ? $rows : false;
    }

    public function get_quiz_by($filter)
    {
        $builder = $this->db->table($this->table_quiz_materi_name);
        $builder->where($filter);
        $query = $builder->get();
        $row = $query->getRow();

        if ($row === null) {
            return false;
        }

        return new \ArrayObject((array) $row, \ArrayObject::ARRAY_AS_PROPS);
    }

    public function insert_new_quiz($data)
    {
        $hasil = false;

        // Kalau kolom id_group belum ada tapi data mau insert id_group, buang dulu
        if (isset($data['id_group']) && !$this->db->fieldExists('id_group', $this->table_quiz_materi_name)) {
            unset($data['id_group']);
        }

        if (!empty($data)) {
            $hasil = $this->db->table($this->table_quiz_materi_name)->insert($data);
        }

        return $hasil ? $this->db->insertID() : $hasil;
    }

    public function update_existing_quiz($data, $id)
    {
        if (isset($data['id_group']) && !$this->db->fieldExists('id_group', $this->table_quiz_materi_name)) {
            unset($data['id_group']);
        }
        $query = $this->db->table($this->table_quiz_materi_name)
            ->update($data, array('id' => $id));
        return $query ? true : false;
    }

    public function delete_existing_quiz($id)
    {
        $query = $this->db->table($this->table_quiz_materi_name)
            ->delete(array('id' => $id));
        return $query;
    }

    public function get_max_ordering_quiz($id_materi)
    {
        $row = $this->db->table($this->table_quiz_materi_name)
            ->selectMax('ordering_index')
            ->where('id_materi', $id_materi)
            ->get()
            ->getRow();

        return ($row && $row->ordering_index) ? (int) $row->ordering_index : 0;
    }

    public function update_ordering_quiz($id, $ordering_index)
    {
        return $this->db->table($this->table_quiz_materi_name)
            ->update(['ordering_index' => $ordering_index], ['id' => $id]);
    }

    public function get_all_pembahasan_by_bab_id($id)
    {

        $builder = $this->db->table($this->table_pembahasan_materi_name);

        $filter = array(
            'id_bab' => $id
        );

        $builder->where($filter);
        $builder->orderBy('ordering_index', 'ASC');

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_all_bab_by_materi_id($id, $cid = null)
    {

        // returned value is object
        // id, id_materi, judul, deskripsi, and jumlah pembahasan only

        $builder = $this->db->table($this->table_bab_materi_name);

        // Join table_bab_materi with table_pembahasan
        $builder->select('table_bab_materi.id, table_bab_materi.id_materi, table_bab_materi.judul as judul, 
        table_bab_materi.deskripsi as deskripsi, 
        COUNT(table_pembahasan_materi.id) as jumlah_pembahasan');

        $builder->join(
            $this->table_pembahasan_materi_name,
            'table_pembahasan_materi.id_bab = table_bab_materi.id',
            'left'
        );

        $filter = array(
            'table_bab_materi.id_materi' => $id
        );

        if ($cid != null) {
            $filter['id_materi_custom'] = $cid;
        } else {
            $filter['id_materi_custom'] = null;
        }

        $builder->where($filter);
        $builder->groupBy('table_bab_materi.id');

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            $end_result = $query->getResult();
            return new \ArrayObject((array) $end_result, \ArrayObject::ARRAY_AS_PROPS);
        } else {
            return false;
        }
    }

    public function insert_new_comments_rating($data)
    {
        /* kirim ini ke table comments_rating */
        $hasil = false;

        if (!empty($data)) {
            $hasil = $this->db->table($this->table_comments_rating_name)->insert($data);
        }

        return $hasil;
    }

    public function get_student_materi_by($filter)
    {

        $builder = $this->db->table($this->table_student_materi_name);

        $builder->where($filter);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            return $query->getRow();
        } else {
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
        $builder->select('tmc.id, tm.icon, tm.judul, tm.kategori, tm.username, tmc.nama_template, tmc.date_created');
        $builder->join($tmc, 'tmc.id_materi = tm.id', 'inner');

        if ($username != null) {
            $builder->where('tm.username', $username);
        }

        if ($id_materi != null) {
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

        if ($id_materi != null) {
            $builder->where('id_materi', $id_materi);
        }

        $rows = $builder->get()->getResult();
        return count($rows) > 0 ? $rows : false;
    }

    public function update_status($id_materi, $status)
    {

        $builder = $this->db->table($this->table_student_materi_name);

        $data = [
            'status' => $status
        ];

        $filter = [
            'id_materi' => $id_materi
        ];

        $query = $builder->update($data, $filter);
        if ($query) {
            return true;
        }

        return false;
    }

    public function insert_custom_new($data)
    {

        $builder = $this->db->table($this->table_materi_custom_name);
        return $builder->insert($data);
    }

    public function update_custom_existing($data, $id)
    {

        $filter = array('id' => $id);

        $builder = $this->db->table($this->table_materi_custom_name);
        return $builder->update($data, $filter);
    }

    public function delete_custom_existing($id)
    {
        $filter = array('id' => $id);
        $builder = $this->db->table($this->table_materi_custom_name);
        return $builder->delete($filter);
    }

    public function get_custom_by($filter)
    {
        $builder = $this->db->table($this->table_materi_custom_name);
        $builder->where($filter);
        $query = $builder->get();
        $row = $query->getRow();

        if ($row === null) {
            return false;
        }

        return new \ArrayObject((array) $row, \ArrayObject::ARRAY_AS_PROPS);
    }

    // single data
    public function get_subscribed_materi($id_materi, $id_user)
    {
        // 1. Mulai dari tabel materi (sebagai tabel utama A)
        $builder = $this->db->table($this->table); // Ini table_materi

        // 2. Pilih kolom yang mau diambil
        // Ambil semua dari materi, dan beberapa dari student_materi (misal: paket, status, tgl_beli)
        $builder->select($this->table . '.*, ' . $this->table_student_materi_name . '.status, ' .
            $this->table_student_materi_name . '.paket, ' .
            $this->table_student_materi_name . '.url_alive as custom_url_alive');

        // 3. Join ke table_student_materi (Tabel B)
        // Relasi: table_materi.id = table_student_materi.id_materi
        $builder->join(
            $this->table_student_materi_name,
            $this->table . '.id = ' . $this->table_student_materi_name . '.id_materi'
        );


        // 4. Filter spesifik untuk user dan materi tersebut
        $filter = [
            $this->table_student_materi_name . '.id_materi' => $id_materi,
            $this->table_student_materi_name . '.id_user'  => $id_user
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

    public function get_all_by_student($id_user = null)
    {
        $builder = $this->db->table($this->table);

        $builder->select('*, ' . $this->table_student_materi_name . '.paket');
        $builder->join($this->table_student_materi_name, $this->table . '.id=' . $this->table_student_materi_name . '.id_materi');

        $data = array(
            $this->table_student_materi_name . '.id_user' => $id_user
        );

        $builder->where($data);

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            return $query->getResult();
        } else {
            return false;
        }
    }

    public function get_all_detail_by($filter)
    {
        $tbl_pm = $this->table_pembahasan_materi_name;
        $tbl_sm = $this->table_student_materi_name;
        $tbl_m  = $this->table;

        $builder = $this->db->table($tbl_pm);

        $builder->select(
            $tbl_pm . '.judul, '
                . $tbl_pm . '.id as id_pembahasan, '
                . $tbl_pm . '.id_bab, '
                . $tbl_pm . '.ordering_index, '
                . $tbl_m . '.judul as nama_materi, '
                . $tbl_m . '.deskripsi as deskripsi_utama, '
                . $tbl_m . '.attachment, '
                . $tbl_sm . '.status'
        );

        $builder->join($tbl_m, $tbl_m . '.id = ' . $tbl_pm . '.id_materi');
        $builder->join($tbl_sm, $tbl_sm . '.id_materi = ' . $tbl_m . '.id');

        $builder->where($tbl_sm . '.id_user', $filter['id_user']);

        // FIX: filter per materi
        if (!empty($filter['id_materi'])) {
            $builder->where($tbl_pm . '.id_materi', $filter['id_materi']);
        }

        $builder->orderBy($tbl_pm . '.id_bab', 'ASC');
        $builder->orderBy($tbl_pm . '.ordering_index', 'ASC');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $results = $query->getResult();
            $final_data = [];
            foreach ($results as $row) {
                $final_data[] = new \ArrayObject((array)$row, \ArrayObject::ARRAY_AS_PROPS);
            }
            return $final_data;
        }

        return false;
    }

    public function get_all_custom_detail_by($filter)
    {
        // Definisi nama tabel agar dinamis
        $tbl_pm = $this->table_pembahasan_custom_name; // table_pembahasan_materi
        $tbl_sm = $this->table_student_materi_name;    // table_student_materi
        $tbl_m  = $this->table;                        // table_materi

        $builder = $this->db->table($tbl_pm);

        // 1. Pilih kolom: Semua dari pembahasan, ambil judul dari materi
        $builder->select($tbl_pm . '.judul, ' . $tbl_pm . '.id as id_pembahasan, ' . $tbl_m . '.judul as nama_materi, ' . $tbl_m . '.deskripsi as deskripsi_utama, '  . $tbl_m . '.attachment, ' . $tbl_sm . '.status');

        // 2. Join Pertama: Pembahasan ke Materi (untuk dapetin detail materi)
        $builder->join($tbl_m, $tbl_m . '.id = ' . $tbl_pm . '.id_materi');

        // 3. Join Kedua: Materi ke Student Materi (untuk filter berdasarkan hak akses student)
        $builder->join($tbl_sm, $tbl_sm . '.id_materi = ' . $tbl_m . '.id');

        // 4. Filter berdasarkan id_user yang ada di table_student_materi
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

        if ($username != null) {
            $builder->where('username', $username);
        }

        $query = $builder->get();
        $manyData = $builder->countAllResults();

        if ($manyData > 0) {

            return $query->getResult();
        } else {
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

        if ($manyData > 0) {

            return $query->getResult();
        } else {
            return false;
        }
    }

    public function insert_new($data)
    {
        $query = $this->db->table($this->table)->insert($data);
        return $query;
    }

    public function insert_new_kategori($data)
    {
        $query = $this->db->table($this->table_kategori_name)->insert($data);
        return $query;
    }

    public function update_existing($data, $id)
    {
        $query = $this->db->table($this->table)->update($data, array('id' => $id));
        if ($query) {
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
