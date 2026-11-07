<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Menentukan apakah user boleh melihat detail peminjaman (Soal 4).
     * Admin & Petugas boleh melihat semua peminjaman.
     * Siswa HANYA boleh melihat peminjaman miliknya sendiri.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('petugas')) {
            return true;
        }

        return $user->id === $peminjaman->user_id;
    }
}