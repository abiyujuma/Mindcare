<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Random\RandomError;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Doctor;

class AppointmentController extends Controller
{
    public function index()
    {
        $Specializations = Specialization::all();
        return view('landingpage', compact('Specializations'));
    }

public function get_doctor(Request $request)
{
    $id_special = $request->id_special;
    $doctors = User::where('Specialization', $id_special)->get();
    $fee = Specialization::find($id_special)?->fee ?? 0;

    return response()->json([
        'doctors' => $doctors,
        'fee' => $fee,
    ]);
}




    public function check()
    {
        $searchdata = "";
        return view('check-appointment', compact('searchdata'));
    }

    public function searchAppointment(Request $request)
    {
        $searchdata = $request->searchdata;

        $appointments = Appointment::where('AppointmentNumber', 'like', "$searchdata%")
            ->orWhere('Name', 'like', "$searchdata%")
            ->orWhere('MobileNumber', 'like', "$searchdata%")
            ->with(['doctorUser.specialization'])
            ->get();

        return view('check-appointment', compact('appointments', 'searchdata'));
    }

    public function newAppointment()
    {
        $appointments = Appointment::where('Status', NULL)->where('Doctor', Auth::user()->id)->get();
        return view('doctor.appointment.newAppointment.index', compact('appointments'));
    }

    public function cancelAppointment()
    {
        $appointments = Appointment::where('Status', 'Cancelled')->where('Doctor', Auth::user()->id)->get();
        return view('doctor.appointment.cancelAppointment.index', compact('appointments'));
    }

    public function aprvAppointment()
    {
        $appointments = Appointment::where('Status', 'Approved')->where('Doctor', Auth::user()->id)->get();
        return view('doctor.appointment.apprvAppointment.index', compact('appointments'));
    }

    public function allAppointment()
    {
        $appointments = Appointment::where('Doctor', Auth::user()->id)->get();
        return view('doctor.appointment.allAppointment.index', compact('appointments'));
    }

    public function approveAppointment($id)
    {
        $appointment = Appointment::find($id);

        $queueNumber = Appointment::where('doctor_id', $appointment->doctor_id)
            ->whereDate('appointment_date', $appointment->appointment_date)
            ->where('status', 'approved')
            ->count() + 1;

        $appointment->status = 'approved';
        $appointment->queue_number = $queueNumber;
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment approved with queue number ' . $queueNumber);
    }

    public function create() {}

    public function store(Request $request)
    {
        $doctor = User::find($request->Doctor);
        $specialization = Specialization::find($request->Specialization);

        $appointment = Appointment::create([
            'AppointmentNumber' => random_int(10000, 99999),
            'Name' => $request->Name,
            'Email' => $request->Email,
            'MobileNumber' => $request->MobileNumber,
            'AppointmentDate' => $request->AppointmentDate,
            'AppointmentTime' => $request->AppointmentTime,
            'Specialization' => $request->Specialization,
            'Doctor' => $request->Doctor,
            'Message' => $request->Message,
            'DoctorName' => $doctor->name ?? '-',
            'DoctorSpecialist' => $doctor->specialization->Specialization ?? '-',
            'DoctorWhatsapp' => $doctor->MobileNumber ?? '-',
            'price' => $specialization->fee ?? 0,
            'bonus' => $request->bonus ?? null,
        ]);

        Alert::success('Berhasil', 'Silakan lanjut ke pembayaran');
        return view('payment', compact('appointment'));
    }

    public function paymentPage($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('payment', compact('appointment'));
    }
public function uploadPayment(Request $request, $id)
{
    $request->validate([
        'payment_proof' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $appointment = Appointment::findOrFail($id);

    if ($request->hasFile('payment_proof')) {
        $file = $request->file('payment_proof');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('payments'), $filename);

        $appointment->payment_proof = $filename;
        $appointment->save();
    }

return redirect()->route('appointment.check')->with('success', 'Payment uploaded successfully');

}

    public function show($id, $aptnum)
    {
        $appointment = Appointment::where('id', $id)->where('AppointmentNumber', $aptnum)->first();
        return view('doctor.appointment.appointmentdetail', compact('appointment'));
    }

    public function searchPage()
    {
        $searchdata = "";
        return view('doctor.search.index', compact('searchdata'));
    }

    public function searchResult(Request $request)
    {
        $searchdata = $request->searchdata;

        $appointments = Appointment::where('AppointmentNumber', 'like', "$searchdata%")
            ->orWhere('Name', 'like', "$searchdata%")
            ->orWhere('MobileNumber', 'like', "$searchdata%")
            ->get();

        return view('doctor.search.index', compact('appointments', 'searchdata'));
    }

    public function edit(Appointment $appointment) {}

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $appointment->Remark = $request->Remark;
        $appointment->Status = $request->Status;
        $appointment->update();

        Alert::success('Berhasil', 'Remark and status has been updated');
        return to_route('allAppointment');
    }

    public function destroy(Appointment $appointment) {}
}
