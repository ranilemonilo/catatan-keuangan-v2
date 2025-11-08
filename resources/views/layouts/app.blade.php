<!doctype html>
<html lang="id">
<head>
    {{-- Meta --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Icon --}}
    <link rel="icon" href="/logo.png" type="image/x-icon" />

    {{-- Judul --}}
    <title>Catatan Keuangan - Laravel</title>

    {{-- Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    {{-- Trix Editor CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">

    <style>
        /* Custom Trix Editor Styling */
        trix-toolbar .trix-button-group { margin-bottom: 0; }
        trix-editor {
            min-height: 150px;
            max-height: 400px;
            overflow-y: auto;
        }
        trix-toolbar .trix-button-group--file-tools { display: none; }
        .trix-content h1 { font-size: 1.75rem; font-weight: bold; margin-bottom: 0.5rem; }
        .trix-content h2 { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
        .trix-content strong { font-weight: bold; }
        .trix-content em { font-style: italic; }
        .trix-content a { color: #007bff; text-decoration: underline; }
        .trix-content ul, .trix-content ol { margin-left: 1.5rem; margin-bottom: 1rem; }
        .trix-content blockquote {
            border-left: 4px solid #ddd;
            padding-left: 1rem;
            margin-left: 0;
            color: #666;
        }
        .trix-content pre {
            background-color: #f4f4f4;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid">
        @yield('content')
    </div>

    {{-- Scripts --}}
    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("livewire:initialized", () => {
            Livewire.on("closeModal", (data) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(data.id));
                if (modal) modal.hide();
            });

            Livewire.on("showModal", (data) => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(data.id));
                if (modal) modal.show();
            });

            Livewire.on("showAlert", (data) => {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? 'Berhasil!' : 'Gagal!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        });
    </script>

    @stack('scripts')
    @livewireScripts
</body>
</html>
