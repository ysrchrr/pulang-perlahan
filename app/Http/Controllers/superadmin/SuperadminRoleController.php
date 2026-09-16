<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SuperadminRoleController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Role'
        ];

        return view('superadmin.manajemen-role', $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Roles::where('id', '!=', 1)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role_name', function ($row) {
                    $description = $row->description ? '<br><p class="text-muted mb-0">' . e($row->description) . '</p>' : '';

                    return e($row->role_name) . '<br><span class="badge bg-primary text-white">Slug: ' . e($row->slug_name) . '</span>';
                })
                ->addColumn('is_active', function ($row) {
                    return '<div class="flex-shrink-0">
                            <div class="form-check form-switch form-switch-right form-switch-md">
                                <label for="FormValidationDefault" class="form-label text-muted"></label>
                                <input class="form-check-input code-switcher" type="checkbox" data-id-role="' . $row->id . '" id="FormValidationDefault" ' . ($row->is_active ? 'checked' : '') . '>
                            </div>
                        </div>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-warning" onclick="modalRole(' . $row->id . ')">
                        <i class="fa-solid fa-pen-to-square"></i> 
                    </button>
                    <button class="btn btn-danger" onclick="toRemove(' . $row->id . ')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    ';
                })
                ->rawColumns(['action', 'is_active', 'role_name'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->input('data');

            $roleId = !empty($data['id']) ? $data['id'] : null;

            $validated = validator($data, [
                'role_name' => 'required|string|max:255',
                'slug_name' => 'required|string|max:255|unique:roles,slug_name,' . $roleId,
                'description' => 'nullable|string',
            ], [
                'role_name.required' => 'Nama role wajib diisi',
                'slug_name.required' => 'Slug wajib diisi',
                'slug_name.unique' => 'Slug sudah digunakan',
            ])->validate();

            $payload = [
                'role_name' => $validated['role_name'],
                'slug_name' => $validated['slug_name'],
                'description' => $validated['description'] ?? null,
            ];

            if ($roleId == null) {
                $payload['is_active'] = '0';

                Roles::create($payload);
                $msg = 'Role berhasil ditambahkan';
            } else {
                Roles::where('id', $roleId)->update($payload);
                $msg = 'Role berhasil diupdate';
            }

            return response()->json([
                'status' => true,
                'msg' => $msg
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'msg' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function setIsActive(Request $request)
    {
        try {
            $data = $request->input('data');

            Roles::where('id', $data['id'])->update([
                'is_active' => $data['is_active']
            ]);

            return response()->json([
                'status' => true,
                'msg' => 'Status role berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function getDetail(Request $request)
    {
        $role = Roles::find($request->id);
        return response()->json($role);
    }

    public function delete(Request $request)
    {
        try {
            $role = Roles::find($request->id);

            if (!$role) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Role tidak ditemukan'
                ], 404);
            }

            if ((int) $role->id === 1) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Role ini tidak boleh dihapus'
                ], 422);
            }

            UserRole::where('roles_id', $role->id)->delete();
            $role->menus()->detach();
            $role->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Role berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
