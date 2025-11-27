<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index()
    {
        $users = User::with('role')->get();

        return response()->json([
            'success' => 1,
            'data' => $users
        ]);
    }

    /**
     * Registrar un nuevo usuario.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id'
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Debe ingresar un correo válido.',
            'email.unique'      => 'El correo ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'role_id.required'  => 'El rol es obligatorio.',
            'role_id.exists'    => 'El rol seleccionado no existe.'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Error de validación',
                'errors'  => $validate->errors()
            ]);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role_id'  => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Usuario registrado exitosamente.',
            'data'    => $user
        ]);
    }

    /**
     * Mostrar un usuario en específico.
     */
    public function show($id)
    {
        $user = User::with('role')->find($id);

        if (! $user) {
            return response()->json([
                'success' => 0,
                'message' => 'Usuario no encontrado.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => $user
        ]);
    }

    /**
     * Actualizar un usuario.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => 0,
                'message' => 'Usuario no encontrado.'
            ]);
        }

        $validate = Validator::make($request->all(), [
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role_id'  => 'required|exists:roles,id'
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Debe ingresar un correo válido.',
            'email.unique'      => 'El correo ya está registrado por otro usuario.',
            'role_id.required'  => 'El rol es obligatorio.',
            'role_id.exists'    => 'El rol seleccionado no existe.',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Error de validación',
                'errors'  => $validate->errors()
            ]);
        }

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Usuario actualizado correctamente.',
            'data' => $user
        ]);
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => 0,
                'message' => 'Usuario no encontrado.'
            ]);
        }

        $user->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Usuario eliminado correctamente.'
        ]);
    }
}
