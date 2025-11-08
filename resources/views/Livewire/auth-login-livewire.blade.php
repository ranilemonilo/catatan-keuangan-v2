<div>
    <!-- SEMUA ISI LOGIN HARUS DI DALAM SATU DIV UTAMA -->
    <div class="text-center mb-4">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="100">
    </div>

    <div class="card shadow-sm mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Login</h4>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form wire:submit.prevent="login">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" wire:model="email" class="form-control" placeholder="Masukkan email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" wire:model="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3">
                Belum punya akun?
                <a href="{{ route('auth.register') }}">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
