<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\JadwalPosyanduWarga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Warga::query();
        $status = $request->get('status_verifikasi', 'disetujui');

        if ($status !== 'all') {
            $query->where('status_verifikasi', $status);
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        $wargas = $query->latest()->paginate(10);

        return response()->json([
            'message'      => 'Data warga berhasil diambil',
            'total'        => $wargas->total(),
            'current_page' => $wargas->currentPage(),
            'last_page'    => $wargas->lastPage(),
            'data'         => $wargas->items()   // ← hanya isi datanya saja
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap'   => 'required|string',
            'nik'            => 'required|string|unique:wargas',
            'tempat_lahir'   => 'nullable|string',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'kategori'       => 'required|in:balita,ibu_hamil,lansia',
            'nama_orang_tua' => 'nullable|string',
            'no_hp'          => 'nullable|string',
            'no_hp_wali'     => 'nullable|string',
            'alamat'         => 'required|string',
            'rt_rw'          => 'required|string',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $warga = Warga::create([
            'nama_lengkap'      => $request->nama_lengkap,
            'nik'               => $request->nik,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'kategori'          => $request->kategori,
            'nama_orang_tua'    => $request->nama_orang_tua,
            'no_hp'             => $request->no_hp,
            'no_hp_wali'        => $request->no_hp_wali,
            'alamat'            => $request->alamat,
            'rt_rw'             => $request->rt_rw,
            'status_verifikasi' => 'disetujui',
            'verified_by'       => auth('api')->id(),
            'verified_at'       => now(),
        ]);

        return response()->json([
            'message' => 'Warga berhasil ditambahkan',
            'data'    => $warga
        ], 201);
    }

    public function storePublic(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap'   => 'required|string',
            'nik'            => 'required|string|unique:wargas',
            'tempat_lahir'   => 'nullable|string',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'kategori'       => 'required|in:balita,ibu_hamil,lansia',
            'nama_orang_tua' => 'nullable|string',
            'no_hp'          => 'required|string',
            'no_hp_wali'     => 'nullable|string',
            'alamat'         => 'required|string',
            'rt_rw'          => 'required|string',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $warga = Warga::create([
            'nama_lengkap'      => $request->nama_lengkap,
            'nik'               => $request->nik,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'kategori'          => $request->kategori,
            'nama_orang_tua'    => $request->nama_orang_tua,
            'no_hp'             => $request->no_hp,
            'no_hp_wali'        => $request->no_hp_wali,
            'alamat'            => $request->alamat,
            'rt_rw'             => $request->rt_rw,
            'status_verifikasi' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pendaftaran berhasil dikirim',
            'data'    => $warga,
        ], 201);
    }

    public function status(Request $request)
    {
        $query = trim((string) $request->get('query', ''));

        if ($query === '') {
            return response()->json(['message' => 'Kata kunci pencarian diperlukan'], 422);
        }

        $warga = Warga::where('nik', $query)
            ->orWhere('no_hp', $query)
            ->latest()
            ->first();

        if (!$warga) {
            return response()->json(['message' => 'Data pendaftaran tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Data pendaftaran ditemukan',
            'data'    => $warga,
        ]);
    }

    public function approve(Request $request, Warga $warga)
    {
        if (!$this->canVerify()) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $warga->update([
            'status_verifikasi' => 'disetujui',
            'verified_by'       => auth('api')->id(),
            'verified_at'       => now(),
        ]);

        return response()->json([
            'message' => 'Data warga berhasil disetujui',
            'data'    => $warga->fresh(),
        ]);
    }

    public function assignJadwal(Request $request, Warga $warga)
    {
        if (!$this->canVerify()) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $v = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwal_posyandus,id',
            'notes' => 'nullable|string',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $assignment = JadwalPosyanduWarga::create([
            'jadwal_posyandu_id' => $request->jadwal_id,
            'warga_id' => $warga->id,
            'notes' => $request->notes ?? null,
            'status' => 'assigned',
        ]);

        $assignment->load('jadwalPosyandu');

        return response()->json([
            'message' => 'Jadwal berhasil ditetapkan untuk warga',
            'data' => $assignment,
        ]);
    }

    public function reject(Request $request, Warga $warga)
    {
        if (!$this->canVerify()) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $warga->update([
            'status_verifikasi' => 'ditolak',
            'verified_by'       => auth('api')->id(),
            'verified_at'       => now(),
        ]);

        return response()->json([
            'message' => 'Data warga ditolak',
            'data'    => $warga->fresh(),
        ]);
    }

    public function show(Warga $warga)
    {
        $warga->load(['penimbangans.kader', 'imunisasis.kader', 'jadwalAssignments.jadwalPosyandu']);
        return response()->json($warga);
    }

    public function update(Request $request, Warga $warga)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap'  => 'string',
            'nik'           => 'string|unique:wargas,nik,'.$warga->id,
            'tanggal_lahir' => 'date',
            'jenis_kelamin' => 'in:L,P',
            'kategori'      => 'in:balita,ibu_hamil,lansia',
            'alamat'        => 'string',
            'rt_rw'         => 'string',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }
        $warga->update($request->all());
        return response()->json([
            'message' => 'Warga berhasil diupdate',
            'data'    => $warga
        ]);
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();
        return response()->json(['message' => 'Warga berhasil dihapus']);
    }

    private function canVerify(): bool
    {
        $user = auth('api')->user();

        return $user && in_array($user->role, ['admin', 'kader'], true);
    }
}