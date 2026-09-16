<?php

namespace App\Http\Controllers;

use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FotoKegiatanController extends Controller
{
    private function uploadImages(array $files)
    {
        $paths = [];

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $filename = 'foto_kegiatan_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $paths[] = $file->storeAs('kegiatan/foto', $filename, 'public');
        }

        return $paths;
    }

    private function deleteImages(array $paths)
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    public function index($id_kegiatan)
    {
        $idKegiatan = dec_id($id_kegiatan);

        $data = [
            'page_title' => 'Foto Kegiatan',
            'kegiatan' => Kegiatan::findOrFail($idKegiatan),
            'id_kegiatan' => $id_kegiatan,
            'list_foto' => FotoKegiatan::where('id_kegiatan', $idKegiatan)
                ->orderByDesc('id')
                ->get()
        ];

        return view('kegiatan.foto-kegiatan', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required',
            'foto_img' => 'nullable|array',
            'foto_img.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'deleted_foto_ids' => 'nullable'
        ]);

        $uploadedPaths = [];

        try {
            $idKegiatan = dec_id($request->id_kegiatan);
            $deletedIds = $request->input('deleted_foto_ids', []);

            if (is_string($deletedIds)) {
                $deletedIds = explode(',', $deletedIds);
            }

            $deletedIds = array_values(array_filter($deletedIds, function ($value) {
                return $value !== null && $value !== '';
            }));

            DB::transaction(function () use ($request, $idKegiatan, $deletedIds, &$uploadedPaths) {
                if (!empty($deletedIds)) {
                    $deletedRows = FotoKegiatan::where('id_kegiatan', $idKegiatan)
                        ->whereIn('id', $deletedIds)
                        ->get();

                    $this->deleteImages($deletedRows->pluck('img_path')->filter()->values()->all());
                    FotoKegiatan::where('id_kegiatan', $idKegiatan)
                        ->whereIn('id', $deletedIds)
                        ->delete();
                }

                if ($request->hasFile('foto_img')) {
                    $uploadedPaths = $this->uploadImages($request->file('foto_img', []));

                    foreach ($uploadedPaths as $path) {
                        FotoKegiatan::create([
                            'id_kegiatan' => $idKegiatan,
                            'img_path' => $path,
                            'created_by' => session('user_id')
                        ]);
                    }
                }
            });

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil simpan foto kegiatan'
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            if (!empty($uploadedPaths)) {
                $this->deleteImages($uploadedPaths);
            }

            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $id = $request->input('id');
            $id = is_numeric($id) ? $id : dec_id($id);
            $data = FotoKegiatan::findOrFail($id);
            $imgPath = $data->img_path;
            $data->delete();

            if ($imgPath) {
                $this->deleteImages([$imgPath]);
            }

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil hapus foto'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }
}
