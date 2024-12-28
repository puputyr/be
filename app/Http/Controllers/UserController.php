<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function get_dashboard()
    {
        Log::info("get dashboard");
        // Menghitung total pengguna
        $totalUsers = User::count();

        // Menghitung total psikolog
        $totalPsychologists = User::where('role', 'psikolog')->count();

        // Mengambil data pengguna dengan role psikolog
        $psychologists = User::where('role', 'psikolog')
            ->get(['name', 'role'])
            ->toArray();

        // Mengembalikan response JSON
        return response()->json([
            'total_user' => $totalUsers,
            'total_psikolog' => $totalPsychologists,
            'data' => $psychologists
        ]);
    }
    public function getAllUsers()
    {
        // Ambil hanya id, name, dan role dari tabel users
        $users = User::select('id', 'name', 'role')->get();

        // Kembalikan respon dalam format JSON
        return response()->json([
            'data' => $users,
        ]);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8', // Password opsional
            'role' => 'required|string|in:admin,user',
        ]);

        // Update data user
        $user->update([
            'name' => $validated['name'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
            'role' => $validated['role'],
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
