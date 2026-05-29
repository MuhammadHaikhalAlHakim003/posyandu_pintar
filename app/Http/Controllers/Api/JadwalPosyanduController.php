<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalPosyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalPosyanduController extends Controller
{
    private function formatItem(JadwalPosyandu $item): array
    {
        return [
            'id' => $item->id,
            'tanggal_pelaksanaan' => $item->tanggal_pelaksanaan,
            'waktu_mulai' => $item->waktu_mulai,
            'waktu_selesai' => $item->waktu_selesai,
            'kategori_posyandu' => $item->kategori_posyandu,
            'lokasi' => $item->lokasi,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
    }

    public function index()
    {
        $items = JadwalPosyandu::latest()->get();

        return response()->json([
            'message' => 'Data jadwal posyandu berhasil diambil',
            'total' => $items->count(),
            'data' => $items->map(fn (JadwalPosyandu $item) => $this->formatItem($item)),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pelaksanaan' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kategori_posyandu' => 'required|in:balita,ibu_hamil,lansia',
            'lokasi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = JadwalPosyandu::create($validator->validated());

        return response()->json([
            'message' => 'Jadwal posyandu berhasil ditambahkan',
            'data' => $this->formatItem($item),
        ], 201);
    }

    public function show(JadwalPosyandu $jadwalPosyandu)
    {
        return response()->json([
            'message' => 'Data jadwal posyandu berhasil diambil',
            'data' => $this->formatItem($jadwalPosyandu),
        ]);
    }

    public function update(Request $request, JadwalPosyandu $jadwalPosyandu)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pelaksanaan' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kategori_posyandu' => 'required|in:balita,ibu_hamil,lansia',
            'lokasi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwalPosyandu->update($validator->validated());

        return response()->json([
            'message' => 'Jadwal posyandu berhasil diperbarui',
            'data' => $this->formatItem($jadwalPosyandu->fresh()),
        ]);
    }

    public function destroy(JadwalPosyandu $jadwalPosyandu)
    {
        $jadwalPosyandu->delete();

        return response()->json([
            'message' => 'Jadwal posyandu berhasil dihapus',
        ]);
    }
}