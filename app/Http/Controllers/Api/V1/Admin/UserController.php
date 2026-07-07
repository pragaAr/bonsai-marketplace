<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Controller API untuk manajemen user oleh Admin.
 *
 * Semua business logic didelegasikan ke UserService,
 * sehingga controller ini hanya bertanggung jawab atas:
 *   1. Validasi HTTP request
 *   2. Memanggil service
 *   3. Membentuk JSON response
 *
 * Cara mendaftarkan route (di routes/api.php):
 * -----------------------------------------------
 * use App\Http\Controllers\Api\V1\Admin\UserController;
 *
 * Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:admin|system_admin'])->group(function () {
 *     Route::apiResource('users', UserController::class)->except(['show']);
 *     Route::put('users/{user}/access', [UserController::class, 'updateAccess']);
 * });
 * -----------------------------------------------
 */
class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    /**
     * GET /api/v1/admin/users
     * Daftar user dengan filter opsional: q, role, isGoogle.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'q'        => ['nullable', 'string', 'max:255'],
            'role'     => ['nullable', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'isGoogle' => ['nullable', 'in:0,1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $users = $this->service->getUserList(
            search: $request->string('q'),
            filterRole: $request->string('role'),
            isGoogleUser: $request->input('isGoogle'),
            perPage: (int) $request->input('per_page', 15),
        );

        return response()->json($users);
    }

    /**
     * POST /api/v1/admin/users
     * Buat user baru.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'whatsapp' => ['required', 'string', 'max:20', 'unique:users,whatsapp'],
            'address'  => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
                Rule::notIn(['system_admin', 'vendor']),
            ],
        ]);

        $user = $this->service->createUser($data);

        return response()->json([
            'message' => 'User berhasil ditambahkan.',
            'data'    => $user->load('roles'),
        ], 201);
    }

    /**
     * PUT /api/v1/admin/users/{user}
     * Perbarui data user (name, email, whatsapp, address, role, password opsional).
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'whatsapp' => ['required', 'string', 'max:20', Rule::unique('users', 'whatsapp')->ignore($user->id)],
            'address'  => ['required', 'string'],
            'role'     => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
                Rule::notIn(['system_admin', 'vendor']),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updated = $this->service->updateUser($user, $data);

        return response()->json([
            'message' => 'User berhasil diperbarui.',
            'data'    => $updated->load('roles'),
        ]);
    }

    /**
     * DELETE /api/v1/admin/users/{user}
     * Hapus user.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->service->deleteUser($user->id);

        return response()->json(['message' => 'User berhasil dihapus.']);
    }

    /**
     * PUT /api/v1/admin/users/{user}/access
     * Perbarui role dan direct permission user.
     */
    public function updateAccess(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
                Rule::notIn(['system_admin']),
            ],
            'permissions'   => ['array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ]);

        try {
            $this->service->saveAccess(
                user: $user,
                role: $data['role'],
                permissions: $data['permissions'] ?? [],
                authId: $request->user()->id,
            );
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Akses user berhasil diperbarui.']);
    }
}
