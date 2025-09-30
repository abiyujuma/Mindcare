@extends('layouts.patient')

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <section class="section-padding" id="booking">
        <div class="container">

            {{-- ✅ Flash message success --}}
            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">

                <div class="col-lg-12 col-12 mx-auto">
                    <div class="booking-form">

                        <h2 class="text-center mb-lg-3 mb-2">
                            Search Appointment History by Appointment Number/Name/Mobile No
                        </h2>

                        <form role="form" method="get" action="{{ route('appointment.search') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <input id="searchdata" type="text" name="searchdata" required
                                        class="form-control" placeholder="Appointment No./Name/Mobile No.">
                                </div>

                                <div class="col-lg-3 col-md-4 col-6 mx-auto">
                                    <button type="submit" class="form-control">Check</button>
                                </div>
                            </div>
                        </form>

                    </div>

                    @if ($searchdata != '')
                        <h4 align="center">Result against "{{ $searchdata }}" keyword </h4>

                        <div class="widget-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Appointment Number</th>
                                            <th>Patient Name</th>
                                            <th>Mobile Number</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>

                                    @php $no = 1; @endphp
                                    <tbody>
                                        @if ($appointments != null && count($appointments) > 0)
                                            @foreach ($appointments as $appointment)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $appointment->AppointmentDate }}</td>
                                                    <td>{{ $appointment->Name }}</td>
                                                    <td>{{ $appointment->MobileNumber }}</td>
                                                    <td>{{ $appointment->Email }}</td>

                                                    <td>
                                                        {{ $appointment->Status ?: 'Not Updated Yet' }}
                                                    </td>

                                                    <td>
                                                        {{ $appointment->Remark ?: 'Not Updated Yet' }}
                                                    </td>
                                                </tr>

                                                @if (Str::lower(trim($appointment->Status)) === 'approved')
                                                    <tr>
                                                        <td colspan="7" style="font-size: 14px">
                                                            @php
                                                                $doctor = \App\Models\User::find($appointment->Doctor);
                                                            @endphp

                                                            <strong>Dokter:</strong> {{ $doctor->name ?? '-' }} |
                                                            <strong>Spesialis:</strong> {{ $doctor->specialization->Specialization ?? '-' }} |
                                                            <strong>WhatsApp:</strong>
                                                            @if ($doctor && $doctor->MobileNumber)
                                                                <a href="https://wa.me/{{ $doctor->MobileNumber }}" target="_blank">
                                                                    {{ $doctor->MobileNumber }}
                                                                </a>
                                                            @else
                                                                Tidak tersedia
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8"> No record found against this search</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
