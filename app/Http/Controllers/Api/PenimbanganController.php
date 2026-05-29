<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penimbangan;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PenimbanganController extends Controller
{
    private function determineStatusGizi(?Warga $warga, array $data): ?string
    {
        if (!$warga) {
            return null;
        }

        $beratBadan = isset($data['berat_badan']) ? (float) $data['berat_badan'] : null;
        $tinggiBadan = isset($data['tinggi_badan']) ? (float) $data['tinggi_badan'] : null;
        $bmi = $this->calculateBmi($beratBadan, $tinggiBadan);
        $kategori = $warga->kategori ?? null;

        if ($kategori === 'balita') {
            return $this->determineBalitaStatus($bmi, $data);
        }

        if ($kategori === 'ibu_hamil') {
            return $this->determineIbuHamilStatus($bmi, $data);
        }

        if ($kategori === 'lansia') {
            return $this->determineLansiaStatus($bmi, $data, $warga);
        }

        return $this->determineGeneralStatus($bmi);
    }

    private function calculateBmi(?float $beratBadan, ?float $tinggiBadan): ?float
    {
        if (!$beratBadan || !$tinggiBadan || $tinggiBadan <= 0) {
            return null;
        }

        $tinggiMeter = $tinggiBadan / 100;

        if ($tinggiMeter <= 0) {
            return null;
        }

        return $beratBadan / ($tinggiMeter * $tinggiMeter);
    }

    private function calculateAgeInMonths(?Warga $warga, ?string $tanggal): ?int
    {
        if (!$warga || !$warga->tanggal_lahir || !$tanggal) {
            return null;
        }

        try {
            $lahir = Carbon::parse($warga->tanggal_lahir);
            $periksa = Carbon::parse($tanggal);
        } catch (\Throwable $e) {
            return null;
        }

        if ($periksa->lessThan($lahir)) {
            return null;
        }

        return $lahir->diffInMonths($periksa);
    }

    private function parseBloodPressure(?string $value): ?array
    {
        if (!$value || !preg_match('/^(\d{2,3})\s*[\/,\-]\s*(\d{2,3})$/', $value, $matches)) {
            return null;
        }

        return [
            'systolic' => (int) $matches[1],
            'diastolic' => (int) $matches[2],
        ];
    }

    private function determineBalitaStatus(?float $bmi, array $data, ?Warga $warga = null): ?string
    {
        $lingkarKepala = isset($data['lingkar_kepala']) ? (float) $data['lingkar_kepala'] : null;
        $usiaBulan = $this->calculateAgeInMonths($warga, $data['tanggal'] ?? null);

        if ($bmi === null) {
            return null;
        }

        $rentangKepala = null;
        if ($lingkarKepala !== null) {
            if ($usiaBulan !== null && $usiaBulan <= 6) {
                $rentangKepala = [34, 43];
            } elseif ($usiaBulan !== null && $usiaBulan <= 12) {
                $rentangKepala = [40, 46.5];
            } elseif ($usiaBulan !== null && $usiaBulan <= 24) {
                $rentangKepala = [43.5, 49.5];
            } else {
                $rentangKepala = [45, 52.5];
            }
        }

        $kepalaBuruk = $rentangKepala && ($lingkarKepala < $rentangKepala[0] - 1.5 || $lingkarKepala > $rentangKepala[1] + 1.5);
        $kepalaKurang = $rentangKepala && !$kepalaBuruk && ($lingkarKepala < $rentangKepala[0] || $lingkarKepala > $rentangKepala[1]);

        if ($bmi < 13.5 || $bmi > 18.5 || $kepalaBuruk) {
            return 'buruk';
        }

        if ($bmi < 15.5 || $bmi > 17.5 || $kepalaKurang) {
            return 'kurang';
        }

        return 'baik';
    }

    private function determineIbuHamilStatus(?float $bmi, array $data): ?string
    {
        $lila = isset($data['lingkar_lengan_atas']) ? (float) $data['lingkar_lengan_atas'] : null;
        $tekananDarah = $this->parseBloodPressure($data['tekanan_darah'] ?? null);

        if ($bmi === null) {
            return null;
        }

        if ($bmi < 18.5 || ($lila !== null && $lila < 23) || ($tekananDarah && ($tekananDarah['systolic'] >= 140 || $tekananDarah['diastolic'] >= 90))) {
            return 'buruk';
        }

        if ($bmi < 20 || $bmi > 27 || ($lila !== null && $lila < 23.5) || ($tekananDarah && ($tekananDarah['systolic'] >= 130 || $tekananDarah['diastolic'] >= 85))) {
            return 'kurang';
        }

        return 'baik';
    }

    private function determineLansiaStatus(?float $bmi, array $data, Warga $warga): ?string
    {
        $lingkarPerut = isset($data['lingkar_perut']) ? (float) $data['lingkar_perut'] : null;
        $kolesterol = isset($data['kolesterol']) ? (float) $data['kolesterol'] : null;
        $asamUrat = isset($data['asam_urat']) ? (float) $data['asam_urat'] : null;
        $tekananDarah = $this->parseBloodPressure($data['tekanan_darah'] ?? null);
        $batasLingkarPerut = ($warga->jenis_kelamin ?? '') === 'P' ? 80 : 90;
        $batasAsamUrat = ($warga->jenis_kelamin ?? '') === 'P' ? 6.0 : 7.0;

        if ($bmi === null) {
            return null;
        }

        if ($bmi < 18.5 || $bmi >= 30) {
            return 'buruk';
        }

        if (
            $bmi < 22 ||
            $bmi > 27 ||
            ($lingkarPerut !== null && $lingkarPerut > $batasLingkarPerut + 10) ||
            ($kolesterol !== null && $kolesterol >= 240) ||
            ($asamUrat !== null && $asamUrat > $batasAsamUrat + 1) ||
            ($tekananDarah && ($tekananDarah['systolic'] >= 140 || $tekananDarah['diastolic'] >= 90))
        ) {
            return 'buruk';
        }

        if (
            $bmi < 22 ||
            $bmi > 25.5 ||
            ($lingkarPerut !== null && $lingkarPerut > $batasLingkarPerut) ||
            ($kolesterol !== null && $kolesterol >= 200) ||
            ($asamUrat !== null && $asamUrat > $batasAsamUrat) ||
            ($tekananDarah && ($tekananDarah['systolic'] >= 130 || $tekananDarah['diastolic'] >= 85))
        ) {
            return 'kurang';
        }

        return 'baik';
    }

    private function determineGeneralStatus(?float $bmi): ?string
    {
        if ($bmi === null) {
            return null;
        }

        if ($bmi < 18.5 || $bmi >= 30) {
            return 'buruk';
        }

        if ($bmi >= 25) {
            return 'kurang';
        }

        return 'baik';
    }

    public function index()
    {
        $data = Penimbangan::with(['warga', 'kader'])->latest()->get();

        $formatted = $data->map(function ($item) {
            return [
                'id'             => $item->id,
                'tanggal'        => $item->tanggal,
                'warga'          => $item->warga->nama_lengkap,
                'kategori'       => $item->warga->kategori,
                'berat_badan'    => $item->berat_badan . ' kg',
                'tinggi_badan'   => $item->tinggi_badan . ' cm',
                'lingkar_kepala' => $item->lingkar_kepala ? $item->lingkar_kepala . ' cm' : null,
                'status_gizi'    => $item->status_gizi,
                'catatan'        => $item->catatan,
                'kader'          => $item->kader->nama_lengkap,
                'tekanan_darah'  => $item->tekanan_darah,
                'lingkar_lengan_atas' => $item->lingkar_lengan_atas ? $item->lingkar_lengan_atas . ' cm' : null,
                'lingkar_perut'  => $item->lingkar_perut ? $item->lingkar_perut . ' cm' : null,
                'kolesterol'     => $item->kolesterol ? $item->kolesterol . ' mg/dL' : null,
                'asam_urat'      => $item->asam_urat ? $item->asam_urat . ' mg/dL' : null,
            ];
        });

        return response()->json([
            'message' => 'Data penimbangan berhasil diambil',
            'total'   => $data->count(),
            'data'    => $formatted
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'warga_id'       => 'required|exists:wargas,id',
            'kader_id'       => 'required|exists:kaders,id',
            'tanggal'        => 'required|date',
            'berat_badan'    => 'required|numeric',
            'tinggi_badan'   => 'required|numeric',
            'lingkar_kepala' => 'nullable|numeric',
            'tekanan_darah'  => 'nullable|string',
            'lingkar_lengan_atas' => 'nullable|numeric',
            'lingkar_perut'  => 'nullable|numeric',
            'kolesterol'     => 'nullable|numeric',
            'asam_urat'      => 'nullable|numeric',
            'status_gizi'    => 'nullable|string',
            'catatan'        => 'nullable|string',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $validated = $v->validated();
        $warga = Warga::find($validated['warga_id']);
        $validated['status_gizi'] = $this->determineStatusGizi($warga, $validated);
        $data = Penimbangan::create($validated);

        return response()->json([
            'message' => 'Data penimbangan berhasil disimpan',
            'data'    => [
                'id'             => $data->id,
                'tanggal'        => $data->tanggal,
                'berat_badan'    => $data->berat_badan . ' kg',
                'tinggi_badan'   => $data->tinggi_badan . ' cm',
                'lingkar_kepala' => $data->lingkar_kepala ? $data->lingkar_kepala . ' cm' : null,
                'status_gizi'    => $data->status_gizi,
                'catatan'        => $data->catatan,
                'warga'          => $data->warga->nama_lengkap,
                'kader'          => $data->kader->nama_lengkap,
                'tekanan_darah'  => $data->tekanan_darah,
                'lingkar_lengan_atas' => $data->lingkar_lengan_atas ? $data->lingkar_lengan_atas . ' cm' : null,
                'lingkar_perut'  => $data->lingkar_perut ? $data->lingkar_perut . ' cm' : null,
                'kolesterol'     => $data->kolesterol ? $data->kolesterol . ' mg/dL' : null,
                'asam_urat'      => $data->asam_urat ? $data->asam_urat . ' mg/dL' : null,
            ]
        ], 201);
    }

    public function show(Penimbangan $penimbangan)
    {
        return response()->json(
            $penimbangan->load(['warga','kader'])
        );
    }

    public function update(Request $request, Penimbangan $penimbangan)
    {
        $v = Validator::make($request->all(), [
            'warga_id'       => 'required|exists:wargas,id',
            'kader_id'       => 'required|exists:kaders,id',
            'tanggal'        => 'required|date',
            'berat_badan'    => 'required|numeric',
            'tinggi_badan'   => 'required|numeric',
            'lingkar_kepala' => 'nullable|numeric',
            'tekanan_darah'  => 'nullable|string',
            'lingkar_lengan_atas' => 'nullable|numeric',
            'lingkar_perut'  => 'nullable|numeric',
            'kolesterol'     => 'nullable|numeric',
            'asam_urat'      => 'nullable|numeric',
            'status_gizi'    => 'nullable|string',
            'catatan'        => 'nullable|string',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $validated = $v->validated();
        $warga = Warga::find($validated['warga_id']);
        $validated['status_gizi'] = $this->determineStatusGizi($warga, $validated);
        $penimbangan->update($validated);
        return response()->json([
            'message' => 'Data diupdate',
            'data'    => $penimbangan->fresh()->load(['warga','kader'])
        ]);
    }

    public function destroy(Penimbangan $penimbangan)
    {
        $penimbangan->delete();
        return response()->json(['message' => 'Data dihapus']);
    }
}