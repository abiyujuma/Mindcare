<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors'; // atau 'users' jika kamu pakai satu tabel

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'Doctor'); // sesuaikan dengan foreign key di tabel appointments
    }
}
