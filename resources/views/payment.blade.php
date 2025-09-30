@extends('layouts.patient')

@section('content')
<div class="container mt-5">
    <h3 class="mb-3">Konfirmasi Pembayaran</h3>
    <p>Silakan transfer ke QRIS berikut dan upload bukti pembayaran Anda.</p>

    <div class="mb-4">
        <strong>Metode Pembayaran:</strong> QRIS<br>
        <img src="{{ asset('images/qris_dummy.png') }}" alt="qris_dummy" width="220" class="img-thumbnail">
    </div>

    <form action="{{ route('payment.upload', $appointment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="payment_proof">Upload Bukti Pembayaran (JPG/PNG/PDF)</label>
            <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            Kirim Bukti Pembayaran
        </button>
    </form>
</div>
@endsection
