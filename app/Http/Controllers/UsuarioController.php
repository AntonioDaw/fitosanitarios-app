<?php

namespace App\Http\Controllers;



use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'id'); // columna por defecto
        $sortDir = $request->input('sort_dir', 'asc'); // dirección por defecto

        $query = User::query();

        if ($search) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        // Validar sortDir para evitar valores inválidos
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $query->orderBy($sortBy, $sortDir);

        $usuariosPaginados = $query->paginate($perPage);

        $usuariosResource = UserResource::collection($usuariosPaginados);

        return $this->paginatedResponse($usuariosResource, $usuariosPaginados);
    }

    public function show($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ], 404); // Not Found
        }

        return response()->json([
            'status' => 'success',
            'data' => new  UserResource($usuario)
        ], 200); // OK
    }

    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'data' => $user,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        // Buscar usuario por ID
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        // Validación
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ignora el email del propio usuario
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:6', // Opcional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Actualizar campos
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Si se proporciona una nueva contraseña, la actualiza
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'data' => $user,
        ], 200);
    }


    public function destroy($id){
        // Buscar la usuario antes de eliminarla
        $usuario = User::find($id);

        // Verificar si la usuario existe
        if (!$usuario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parcela no encontrada.'
            ], 404); // 404 Not Found
        }

        // Eliminar usuario
        $usuario->delete();

        // Retornar la respuesta con la usuario eliminada
        return response()->json([
            'status' => 'success',
            'data' => $usuario
        ], 200); // 200 OK
    }
}
