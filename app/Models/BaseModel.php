<?php

namespace App\Models;

use CodeIgniter\Model;
use ArrayObject;

class BaseModel extends Model
{
    /**
     * Override findAll - semua hasil berupa ArrayObject (akses array & properti)
     */
    public function findAll(?int $limit = 0, int $offset = 0)
    {
        $result = parent::findAll($limit, $offset);
        if (empty($result)) {
            return [];
        }
        return array_map(fn($row) => new ArrayObject($row, ArrayObject::ARRAY_AS_PROPS), $result);
    }

    /**
     * Override find - single row
     */
    public function find($id = null)
    {
        $row = parent::find($id);
        if (empty($row)) {
            return null;
        }
        return new ArrayObject($row, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Override first
     */
    public function first()
    {
        $row = parent::first();
        if (empty($row)) {
            return null;
        }
        return new ArrayObject($row, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Override getRowArray? Tidak perlu, karena method ini biasanya dipakai di dalam query builder manual.
     * Tapi jika Anda sering pakai $builder->get()->getRowArray(), Anda bisa buat helper sendiri.
     * Untuk keperluan fleksibel, kita override juga get()? Tidak disarankan.
     * Alternatif: tambahkan method untuk membungkus hasil query builder manual.
     */
}