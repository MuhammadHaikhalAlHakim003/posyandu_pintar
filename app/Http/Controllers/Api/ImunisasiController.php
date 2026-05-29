<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Imunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImunisasiController extends Controller
{
    public function index()
    {
        $data = Imunisasi::with(['warga', 'kader'])->latest()->get();

        $formatted = $data->map(function ($item) {
            return [
                'id'                 => $item->id,
                'jenis_imunisasi'    => $item->jenis_imunisasi,
                'tanggal_pemberian'  => $item->tanggal_pemberian,
                'tanggal_berikutnya' => $item->tanggal_berikutnya ?? '-',
                'keterangan'         => $item->keterangan,
                'warga'              => $item->warga->nama_lengkap,
                'kategori_warga'     => $item->warga->kategori,
                'kader'              => $item->kader->nama_lengkap,
            ];
        });

        return response()->json([
            'message' => 'Data imunisasi berhasil diambil',
            'total'   => $data->count(),
            'data'    => $formatted
        ]);
    }

        public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'warga_id'           => 'required|exists:wargas,id',
            'kader_id'           => 'required|exists:kaders,id',
            'jenis_imunisasi'    => 'required|string',
            'tanggal_pemberian'  => 'required|date',
            'tanggal_berikutnya' => 'nullable|date',
            'keterangan'         => 'nullable|string',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $data = Imunisasi::create($request->all());

        return response()->json([
            'message' => 'Data imunisasi berhasil disimpan',
            'data'    => [
                'id'                  => $data->id,
                'jenis_imunisasi'     => $data->jenis_imunisasi,
                'tanggal_pemberian'   => $data->tanggal_pemberian,
                'tanggal_berikutnya'  => $data->tanggal_berikutnya ?? '-',
                'keterangan'          => $data->keterangan,
                'warga'               => $data->warga->nama_lengkap,
                'kategori_warga'      => $data->warga->kategori,
                'kader'               => $data->kader->nama_lengkap,
            ]
        ], 201);
    }

    public function show(Imunisasi $imunisasi)
    {
        $imunisasi->load(['warga', 'kader']);

        return response()->json([
            'message' => 'Detail data imunisasi',
            'data'    => [
                'id'                 => $imunisasi->id,
                'jenis_imunisasi'    => $imunisasi->jenis_imunisasi,
                'tanggal_pemberian'  => $imunisasi->tanggal_pemberian,
                'tanggal_berikutnya' => $imunisasi->tanggal_berikutnya ?? '-',
                'keterangan'         => $imunisasi->keterangan,
                'warga'              => [
                    'nama'         => $imunisasi->warga->nama_lengkap,
                    'nik'          => $imunisasi->warga->nik,
                    'kategori'     => $imunisasi->warga->kategori,
                    'tanggal_lahir'=> $imunisasi->warga->tanggal_lahir,
                    'alamat'       => $imunisasi->warga->alamat,
                ],
                'kader'              => [
                    'nama'    => $imunisasi->kader->nama_lengkap,
                    'no_hp'   => $imunisasi->kader->no_hp,
                    'wilayah' => $imunisasi->kader->wilayah,
                ],
            ]
        ]);
    }

    public function update(Request $request, Imunisasi $imunisasi)
    {
        $imunisasi->update($request->all());
        return response()->json([
            'message' => 'Data diupdate',
            'data'    => $imunisasi
        ]);
    }

    public function destroy(Imunisasi $imunisasi)
    {
        $imunisasi->delete();
        return response()->json(['message' => 'Data dihapus']);
    }
}