@extends('layouts.app')
@section('title', 'User')

@section('content')
    <div class="topbar">
        <div>
            <h1>User</h1>
            <div class="sub">Kelola akun admin & kasir</div>
        </div>
        <button type="button" class="btn" onclick="openModal('user-new')">+ User</button>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td class="muted">{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->isAdmin() ? 'solid' : '' }}">{{ $user->role }}</span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn ghost sm" onclick="openModal('user-{{ $user->id }}')">Edit</button>
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn danger sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                            @include('users._modal', [
                                'modalId' => 'user-'.$user->id,
                                'title' => 'Edit User',
                                'action' => route('users.update', $user),
                                'method' => 'PUT',
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $users->links() }}</div>

    @include('users._modal', [
        'modalId' => 'user-new',
        'title' => 'User Baru',
        'action' => route('users.store'),
        'method' => 'POST',
        'user' => new \App\Models\User(['role' => 'kasir']),
    ])

    @if ($errors->any() && old('_modal'))
        @push('scripts')
            <script>document.addEventListener('DOMContentLoaded', () => openModal(@json(old('_modal'))));</script>
        @endpush
    @endif
@endsection
