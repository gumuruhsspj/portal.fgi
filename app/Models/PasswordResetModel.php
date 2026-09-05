<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'table_password_reset_tokens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'token', 'expires_at', 'used', 'failed_at'];
    protected $useTimestamps = false;

    /**
     * Generate OTP 7 digit
     */
    public function generateOTP(): string
    {
        return str_pad((string) random_int(1000000, 9999999), 7, '0', STR_PAD_LEFT);
    }

    /**
     * Buat token reset password baru
     */
    public function createResetToken(string $email): string
    {
        // Hapus token lama yang belum digunakan
        $this->where('email', $email)->where('used', 0)->delete();

        $token = $this->generateOTP();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->insert([
            'email'      => $email,
            'token'      => $token,
            'expires_at' => $expiresAt,
            'used'       => 0,
        ]);

        return $token;
    }

    /**
     * Verifikasi OTP
     * @return array ['valid' => bool, 'message' => string]
     */
    public function verifyOTP(string $email, string $token): array
    {
        $record = $this->where('email', $email)
            ->where('token', $token)
            ->where('used', 0)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$record) {
            $this->markFailed($email);
            return ['valid' => false, 'message' => 'Kode OTP tidak valid.'];
        }

        // Cek expired
        if (strtotime($record['expires_at']) < time()) {
            $this->markFailed($email);
            return ['valid' => false, 'message' => 'Kode OTP sudah kadaluwarsa. Silakan minta baru.'];
        }

        // Tandai sebagai used
        $this->update($record['id'], ['used' => 1]);

        return ['valid' => true, 'message' => 'OK'];
    }

    /**
     * Tandai gagal untuk rate limit
     */
    public function markFailed(string $email): void
    {
        $this->where('email', $email)
            ->where('used', 0)
            ->orderBy('created_at', 'DESC')
            ->set(['failed_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    /**
     * Cek apakah masih dalam cooldown (2 menit setelah gagal)
     */
    public function isInCooldown(string $email): bool
    {
        $record = $this->where('email', $email)
            ->where('used', 0)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$record || empty($record['failed_at'])) {
            return false;
        }

        $failedTime = strtotime($record['failed_at']);
        $now = time();
        return ($now - $failedTime) < 120; // 2 menit
    }

    /**
     * Hapus semua token expired
     */
    public function cleanExpired(): void
    {
        $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
