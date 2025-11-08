<div class="card shadow-sm mx-auto" style="max-width: 400px;">
    <div class="card-body">
        <h4 class="card-title text-center mb-4">Daftar Akun</h4>

        <form wire:submit.prevent="register">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" wire:model="name" class="form-control" placeholder="Masukkan nama" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" wire:model="email" class="form-control" placeholder="Masukkan email" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" wire:model="password" class="form-control" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" wire:model="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-success w-100">Daftar</button>
        </form>

        <p class="text-center mt-3">
            Sudah punya akun?
            <a href="{{ route('auth.login') }}">Login</a>
        </p>
    </div>
</div>
