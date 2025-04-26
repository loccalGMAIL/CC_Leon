@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Informes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">Informes</li>
        </ol>
      </nav>
      
    </div><!-- End Page Title -->

    <section class="section dashboard">

    </section>

  </main>
@endsection