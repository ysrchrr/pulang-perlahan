<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuParent;
use App\Models\Roles;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SuperadminMenuController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Menu',
            'menu_parent' => MenuParent::get(),
            'roles' => Roles::where('is_active', '1')->get()
        ];

        return view('superadmin.manajemen-menu', $data);
    }

    public function getData(Request $request)
    {
        $data = Menu::get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('menu_name', function ($row) {
                $badges = '';
                if ($row->roles && $row->roles->count() > 0) {
                    foreach ($row->roles as $role) {
                        $badges .= '<span class="badge bg-primary me-1">' . $role->role_name . '</span>';
                    }
                } else {
                    $badges = '<span class="badge bg-danger">Belum Ada Role</span>';
                }

                return '<div class="mb-1">' . $row->menu_name . '</div>' . $badges;
            })
            ->addColumn('parent_menu', function ($row) {
                return $row->parent ? $row->parent->parent_name : '-';
            })
            ->addColumn('icon', function ($row) {
                return $row->icon ? $row->icon : '-';
            })
            ->addColumn('link', function ($row) {
                return env('APP_URL') . '/' . $row->slug_name;
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
                <button class="btn btn-warning" onclick="modalMenu(`' . enc_id($row->id) . '`)"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btn btn-danger" onclick="toRemove(`' . enc_id($row->id) . '`)">
                    <i class="fa-solid fa-trash"></i>
                </button>
                ';
            })
            ->rawColumns(['parent_menu', 'icon', 'is_active', 'action', 'menu_name'])
            ->make(true);
    }

    public function storeMenu(Request $request)
    {
        try {
            $isUpdate = $request->filled('id_menu');
            $menuId = $isUpdate ? dec_id($request->id_menu) : null;

            // Validasi
            $request->validate([
                'menu_name' => 'required|string|max:255',
                'slug_name' => 'required|string|max:255|unique:menus,slug_name,' . $menuId,
                'parent_id' => 'nullable|exists:menu_parent,id',
                'icon' => 'nullable|string',
                'menu_order' => 'nullable|integer|min:0',
                'is_active' => 'required|in:0,1',
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,id',
            ], [
                'menu_name.required' => 'Nama menu wajib diisi',
                'slug_name.required' => 'Slug wajib diisi',
                'slug_name.unique' => 'Slug sudah digunakan',
                'roles.required' => 'Minimal pilih 1 role',
            ]);

            // Data untuk insert/update
            $menuData = [
                'parent_id' => $request->parent_id,
                'menu_name' => $request->menu_name,
                'slug_name' => $request->slug_name,
                'icon' => $request->icon,
                'menu_order' => $request->menu_order ?? 0,
                'is_active' => $request->is_active,
            ];

            if ($isUpdate) {
                // UPDATE
                $menu = Menu::findOrFail($menuId);
                $menu->update($menuData);

                // Hapus relasi lama
                $menu->roles()->detach();

                $message = 'Menu berhasil diupdate';
            } else {
                // CREATE
                $menu = Menu::create($menuData);
                $message = 'Menu berhasil ditambahkan';
            }

            // Attach roles dengan permissions
            foreach ($request->roles as $roleId) {
                $permissions = $request->input("permissions.{$roleId}", []);

                $menu->roles()->attach($roleId, [
                    'permissions' => json_encode($permissions),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $menu
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detailMenu(Request $request)
    {
        try {
            $id = dec_id($request->id);

            $menu = Menu::with(['roles' => function ($query) {
                $query->select('roles.id', 'roles.role_name');
            }])->findOrFail($id);

            // Convert boolean ke string enum
            $menuArray = $menu->toArray();
            $menuArray['is_active'] = $menu->is_active ? '1' : '0';

            return response()->json([
                'status' => 'success',
                'message' => 'Data menu berhasil diambil',
                'data' => $menuArray
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setIsActive(Request $request)
    {
        try {
            $data = $request->input('data');

            $menu = Menu::findOrFail($data['id']);

            if ($menu->is_restricted && (string) $data['is_active'] === '0') {
                return response()->json([
                    'status' => false,
                    'msg' => 'Menu restricted tidak boleh dinonaktifkan'
                ], 403);
            }

            $menu->update([
                'is_active' => $data['is_active']
            ]);

            return response()->json([
                'status' => true,
                'msg' => 'Status menu berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteMenu(Request $request)
    {
        try {
            $id = dec_id($request->menu_id);

            $menu = Menu::findOrFail($id);

            if ($menu->is_restricted) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Menu restricted tidak boleh dihapus'
                ], 500);
            }

            $menu->roles()->detach();
            $menu->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus menu: ' . $e->getMessage()
            ], 500);
        }
    }
}
