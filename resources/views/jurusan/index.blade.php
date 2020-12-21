@extends('layouts.app')

@section('content')
<h1 class="display-4 text-center my-5">Sistem Informasi STMIK Dharma Wacana</h1>

<div class="text-right pt-5">
  @auth
    <a href="{{ route('jurusans.create') }}" class="btn btn-info">
    Tambah Jurusan</a>
  @endauth
</div>

<div class="card-columns mt-3">
  @foreach($jurusans as $jurusan)

  <div class="card mt-1" id=card-{{ $jurusan->id }}>

    @auth
    <div class="btn-group btn-action">
      <a href="{{ route('jurusans.edit',['jurusan' => $jurusan->id])}}"
        class="btn btn-primary d-inline-block" title="Edit Jurusan">
          <i class="fa fa-edit fa-fw"></i>
      </a>
      <form action="{{route('jurusans.destroy',['jurusan' => $jurusan->id])}}"
        method="POST">
        @csrf  @method('DELETE')
        <button type="submit" class="btn btn-danger shadow-none btn-hapus"
        title="Hapus Jurusan" data-name="{{$jurusan->nama}}" data-table="jurusan">
          <i class="fa fa-trash fa-fw"></i>
        </button>
      </form>
    </div>
    @endauth

    <div class="card-body text-center">
    <h3 class="card-title py-1">{{ $jurusan->nama }}</h3>
    <hr>

    <div class="card-text py-1">Kepala Jurusan:
      <b>{{$jurusan->kepala_jurusan}}</b></div>
    <div class="card-text pb-4">Total Mahasiswa: {{$jurusan->mahasiswas_count}}
        (daya tampung {{$jurusan->daya_tampung}})</div>
    <a href="{{ route('jurusan-dosen',['jurusan_id' => $jurusan->id]) }}"
        class="btn btn-info btn-block">Dosen</a>
    <a href="{{ route('jurusan-mahasiswa',['jurusan_id' => $jurusan->id]) }}"
        class="btn btn-success btn-block">Mahasiswa</a>
    </div>
  </div>

  @endforeach
</div>
@endsection
