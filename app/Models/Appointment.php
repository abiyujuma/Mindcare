<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
    'AppointmentNumber',
    'Name',
    'Email',
    'MobileNumber',
    'AppointmentDate',
    'AppointmentTime',
    'Specialization',
    'Doctor',
    'Message',
    'ApplyDate',
    'Remark',
    'Status',
    'DoctorName',
    'DoctorSpecialist',
    'DoctorWhatsapp',
];
public function doctorUser()
{
    return $this->belongsTo(User::class, 'Doctor'); // Kolom Doctor menyimpan ID dokter
}

    public function doctor()
    {
        return $this->hasOne(User::class, 'name', 'Doctor'); // relasi ke model Doctor
    }
}
