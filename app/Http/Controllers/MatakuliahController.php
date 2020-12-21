<?php

namespace App\Http\Controllers;

use App\Matakuliah;
use App\Jurusan;
use App\Dosen;
use App\Mahasiswa;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MatakuliahController extends Controller
{
    // Untuk membatasi hak akses
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matakuliahs = Matakuliah::with('dosen','jurusan')
                       ->orderBy('nama')->paginate(10);
        return view('matakuliah.index',['matakuliahs' => $matakuliahs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jurusans = Jurusan::orderBy('nama')->get();
        $dosens = Dosen::orderBy('nama')->get();
        return view('matakuliah.create',[
            'jurusans' => $jurusans,
            'dosens' => $dosens,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kode' => 'required|alpha_num|size:5|unique:matakuliahs,kode',
            'nama' => 'required',
            'dosen_id' => 'required|exists:App\Dosen,id',
            'jurusan_id' => 'required|exists:App\Jurusan,id',
            'jumlah_sks' => 'required|digits_between:1,6',
        ]);
        Matakuliah::create($validateData);
        Alert::success('Berhasil',"Mata kuliah $request->nama berhasil dibuat");
        // Trik agar halaman kembali ke awal
        return redirect($request->url_asal);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Matakuliah  $matakuliah
     * @return \Illuminate\Http\Response
     */
    public function show(Matakuliah $matakuliah)
    {
        $mahasiswas = $matakuliah->mahasiswas->sortBy('nama');
        return view('matakuliah.show',[
            'matakuliah' => $matakuliah,
            'mahasiswas' => $mahasiswas,
        ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Matakuliah  $matakuliah
     * @return \Illuminate\Http\Response
     */
    public function edit(Matakuliah $matakuliah)
    {
        $jurusans = Jurusan::orderBy('nama')->get();
        $dosens = Dosen::orderBy('nama')->get();

        return view('matakuliah.edit',
        [
            'matakuliah' => $matakuliah,
            'jurusans' => $jurusans,
            'dosens' => $dosens,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Matakuliah  $matakuliah
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matakuliah $matakuliah)
    {
        $validateData = $request->validate([
            'kode' => 'required|alpha_num|size:5|unique:matakuliahs,kode,'
                       .$matakuliah->id,
            'nama' => 'required',
            'dosen_id' => 'required|exists:App\Dosen,id',
            'jurusan_id' => 'required|exists:App\Jurusan,id',
            'jumlah_sks' => 'required|digits_between:1,6',
        ]);

        // Antisipasi jika ada yang edit inputan jurusan_id yang sudah di hidden
        if (($matakuliah->mahasiswas()->count() > 0) AND
            ($matakuliah->jurusan_id != $request->jurusan_id) ) {
            Alert::error('Update gagal',"Jurusan tidak bisa diubah!");
            return back();
        }

        $matakuliah->update($validateData);
        Alert::success('Berhasil',"Mata kuliah $request->nama telah di update");
        // Trik agar halaman kembali ke awal
        return redirect($request->url_asal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Matakuliah  $matakuliah
     * @return \Illuminate\Http\Response
     */
    public function destroy(Matakuliah $matakuliah)
    {
        $matakuliah->delete();
        Alert::success('Berhasil', "Mata kuliah $matakuliah->nama telah di hapus");
        return redirect("/matakuliahs");
    }


    // Method untuk tombol "Buat Matakuliah" di view dosen.show
    public function buatMatakuliah(Dosen $dosen)
    {
        $jurusans = Jurusan::orderBy('nama')->get();
        return view('matakuliah.create',[
            'dosen' => $dosen,
            'jurusans' => $jurusans,
        ]);
    }

    public function daftarkanMahasiswa(Matakuliah $matakuliah)
    {
      // Ambil semua daftar mahasiswa dari jurusan yang sama dengan matakuliah
      $mahasiswas = Mahasiswa::where('jurusan_id',$matakuliah->jurusan_id)
                   ->orderBy('nama')->get();

      // Buat array dari daftar mahasiswa yang sudah terdaftar sebelumnya
      // Ini akan dipakai untuk proses repopulate form
      $mahasiswas_sudah_terdaftar = $matakuliah->mahasiswas->pluck('id')->all();
      return view('matakuliah.daftarkan-mahasiswa',
      [
        'matakuliah' => $matakuliah,
        'mahasiswas' => $mahasiswas,
        'mahasiswas_sudah_terdaftar' => $mahasiswas_sudah_terdaftar,
      ]);
    }

    public function prosesDaftarkanMahasiswa(Request $request,
                                             Matakuliah $matakuliah)
    {
      // Ambil semua id mahasiswa untuk jurusan yang sama dengan mata kuliah.
      // Ini dipakai untuk proses validasi agar mahasiswa beda jurusan tidak
      // bisa didaftarkan.
      $mahasiswa_jurusan=Mahasiswa::where('jurusan_id',$matakuliah->jurusan_id)
                        ->pluck('id')->toArray();

      $validateData = $request->validate([
          'mahasiswa.*' => 'distinct|in:'.implode(',',$mahasiswa_jurusan),
      ]);

      // Proses mahasiswa yang didaftarkan
      $matakuliah->mahasiswas()->sync($validateData['mahasiswa'] ?? []);

      Alert::success('Berhasil', "Terdapat ".
                     count($validateData['mahasiswa'] ?? []).
                     " mahasiswa yang mengambil $matakuliah->nama");

      return redirect(route('matakuliahs.show',
                     ['matakuliah' => $matakuliah->id]));
    }
}
