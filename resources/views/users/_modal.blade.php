@php
    $isErr = old('_modal') === $modalId;
    $val = fn ($field, $default) => $isErr ? old($field, $default) : $default;
    $editing = $method === 'PUT';
@endphp
<div class="modal-backdrop" id="{{ $modalId }}">
    <div class="modal">
        <div class="modal-head">
            <h3>{{ $title }}</h3>
            <button type="button" class="modal-x" onclick="closeModal(this)">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ $action }}">
                @csrf
                @if ($editing) @method('PUT') @endif
                <input type="hidden" name="_modal" value="{{ $modalId }}">

                <div class="field">
                    <label>Nama</label>
                    <input class="input" name="name" value="{{ $val('name', $user->name) }}" required>
                    @if ($isErr) @error('name') <div class="error">{{ $message }}</div> @enderror @endif
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label>Email</label>
                        <input class="input" type="email" name="email" value="{{ $val('email', $user->email) }}" required>
                        @if ($isErr) @error('email') <div class="error">{{ $message }}</div> @enderror @endif
                    </div>
                    <div class="field">
                        <label>Role</label>
                        <select name="role" required>
                            <option value="kasir" {{ $val('role', $user->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="admin" {{ $val('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label>Password</label>
                        <input class="input" type="password" name="password" {{ $editing ? '' : 'required' }}>
                        @if ($isErr) @error('password') <div class="error">{{ $message }}</div> @enderror @endif
                        @if ($editing) <div class="hint">Kosongkan jika tidak ingin mengubah.</div> @endif
                    </div>
                    <div class="field">
                        <label>Ulangi Password</label>
                        <input class="input" type="password" name="password_confirmation" {{ $editing ? '' : 'required' }}>
                    </div>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn ghost" onclick="closeModal(this)">Batal</button>
                    <button type="submit" class="btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
