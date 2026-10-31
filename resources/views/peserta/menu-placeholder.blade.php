@extends('layouts.dashboard')

@section('title', $judul)
@section('page-title', $judul)

@section('content')
    <section class="content-card">
        <h3>{{ $judul }}</h3>

        <p>{{ $deskripsi }}</p>

        <span class="secondary-button">
            Tahap pengembangan berikutnya
        </span>
    </section>
@endsection