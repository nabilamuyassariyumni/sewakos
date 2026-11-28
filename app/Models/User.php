<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Kos;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke booking yang diajukan sebagai Pencari
    public function bookingSebagaiPencari(): HasMany
    {
        return $this->hasMany(Booking::class, 'pencari_id');
    }

    // Relasi ke booking untuk kos miliknya sebagai Pemilik
    public function bookingSebagaiPemilik(): HasMany
    {
        return $this->hasMany(Booking::class, 'pemilik_id');
    }

    // Relasi ke kos yang didaftarkan oleh Pemilik
    public function kos(): HasMany
    {
        return $this->hasMany(Kos::class, 'user_id');
    }
}
