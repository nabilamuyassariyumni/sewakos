<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Data Kos
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/kos.css') }}">

    <div class="kos-container">
        <div class="kos-form-card">
            <h1>Formulir Kos Baru</h1>
            <p>Lengkapi informasi kos yang ingin kamu sewakan.</p>

            @if ($errors->any())
                <div class="kos-alert kos-alert-error">
                    Terdapat kesalahan pada formulir. Periksa kolom yang ditandai.
                </div>
            @endif

            <form action="{{ route('pemilik.kos.store') }}" method="POST" enctype="multipart/form-data">
                @include('pemilik.kos._form')
            </form>
        </div>
    </div>
</x-app-layout>
