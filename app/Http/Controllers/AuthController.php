<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info($request);
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,psikolog,tim medis,tim keamanan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        // Validasi input
        Log::info($request);
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // Cek apakah username ada di database
        $user = User::where('name', $request->name)->first();

        // Jika user tidak ditemukan atau password salah
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.',
            ], 401);
        }

        // Jika login berhasil, buat token API
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully.',
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }
    public function checkLogin(Request $request)
    {
        try {
            // Cek apakah pengguna terautentikasi menggunakan token
            if (Auth::check()) {
                // Jika terautentikasi, kembalikan data pengguna
                return response()->json([
                    'success' => true,
                    'message' => 'User is logged in.',
                    'data' => Auth::user(), // Mengambil informasi pengguna yang sedang login
                ]);
            }

            // Jika tidak terautentikasi (token tidak valid atau tidak ada token)
            return response()->json([
                'success' => false,
                'message' => 'User is not logged in. Please provide a valid token.',
            ], 401); // Status 401 Unauthorized

        } catch (\Exception $e) {
            // Jika terjadi kesalahan lainnya (misalnya server error)
            Log::error('Login check error: ' . $e->getMessage(), ['exception' => $e]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking login status.',
                'error' => $e->getMessage(),  // Menampilkan pesan error yang lebih mendetail
            ], 500); // Status 500 Internal Server Error
        }
    }
    public function logout(Request $request)
    {
        // Mengambil pengguna yang sedang terautentikasi
        $user = Auth::user();

        // Menghapus token saat ini
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        // Mengembalikan response sukses
        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }


}
