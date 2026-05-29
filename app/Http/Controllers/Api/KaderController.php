<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kader;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaderController extends Controller
{
    public function index()
    {
        return response()->json(
            Kader::with('user')->latest()->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'user_id'      => 'required|exists:users,id',
            'nama_lengkap' => 'required|string',
            'no_hp'        => 'required|string',
            'alamat'       => 'required|string',
            'wilayah'      => 'required|string',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }
        $kader = Kader::create($request->all());
        return response()->json([
            'message' => 'Kader berhasil ditambahkan',
            'data'    => $kader
        ], 201);
    }

    public function storeAccount(Request $request)
    {
        $currentUser = auth('api')->user();
        if (!$currentUser || $currentUser->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $v = Validator::make($request->all(), [
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6|confirmed',
            'nama_lengkap' => 'required|string|max:100',
            'no_hp'        => 'required|string|max:25',
            'alamat'       => 'required|string',
            'wilayah'      => 'required|string|max:100',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $result = DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'kader',
            ]);

            $kader = Kader::create([
                'user_id'      => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'no_hp'        => $request->no_hp,
                'alamat'       => $request->alamat,
                'wilayah'      => $request->wilayah,
            ]);

            return compact('user', 'kader');
        });

        return response()->json([
            'message' => 'Akun kader berhasil ditambahkan',
            'data'    => $result,
        ], 201);
    }

    public function show(Kader $kader)
    {
        return response()->json($kader->load('user'));
    }

    public function update(Request $request, Kader $kader)
    {
        $kader->update($request->all());
        return response()->json([
            'message' => 'Kader berhasil diupdate',
            'data'    => $kader
        ]);
    }

    public function destroy(Kader $kader)
    {
        $kader->delete();
        return response()->json(['message' => 'Kader berhasil dihapus']);
    }
}