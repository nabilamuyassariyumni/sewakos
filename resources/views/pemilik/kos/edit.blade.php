
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Kos
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/kos.css') }}">

    <div class="kos-container">
        <div class="kos-form-card">
            <h1>Edit Informasi Kos</h1>
            <p>Perbarui informasi kos sesuai kondisi terbaru.</p>

            @if ($errors->any())
                <div class="kos-alert kos-alert-error">
                    Terdapat kesalahan pada formulir. Periksa kolom yang ditandai.
                </div>
            @endif

            <form
                action="{{ route('pemilik.kos.update', $kos) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @method('PUT')
                @include('pemilik.kos._form')
            </form>
        </div>
    </div>
</x-app-layout>
