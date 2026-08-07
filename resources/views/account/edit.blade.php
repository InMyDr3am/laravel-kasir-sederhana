@extends('layouts.app')
@section('title', 'Akun')

@section('content')
    <div class="topbar">
        <div>
            <h1>Akun Saya</h1>
            <div class="sub">Ubah password Anda</div>
        </div>
    </div>

    <div class="card pad" style="max-width:520px">
        <div class="field">
            <label>Nama</label>
            <input class="input" value="{{ auth()->user()->name }}" disabled>
        </div>
        <div class="field">
            <label>Email</label>
            <input class="input" value="{{ auth()->user()->email }}" disabled>
        </div>

        <hr style="border:none;border-top:1px solid var(--line);margin:18px 0">

        <form method="POST" action="{{ route('account.password') }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="current_password">Password Saat Ini</label>
                <input class="input" type="password" id="current_password" name="current_password" required>
                @error('current_password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="password">Password Baru</label>
                    <input class="input" type="password" id="password" name="password" required>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Ulangi Password Baru</label>
                    <input class="input" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <button type="submit" class="btn" style="margin-top:6px">Simpan Password</button>
        </form>
    </div>
@endsection
