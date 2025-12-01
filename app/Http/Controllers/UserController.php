<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index()
    {
        return response()->json([
            'success' => 1,
            'data' => User::with('role')->get()
        ]);
    }

    /**
     * Registrar un usuario.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id
        ]);

        return $this->success('Usuario registrado exitosamente.', $user);
    }

    /**
     * Mostrar un usuario.
     */
    public function show($id)
    {
        try {
            $user = User::with('role')->findOrFail($id);

            return $this->success(null, $user);

        } catch (ModelNotFoundException $e) {
            return $this->notFound('Usuario no encontrado.');
        }
    }

    /**
     * Actualizar un usuario.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:150',
                'email'    => 'required|email|unique:users,email,' . $id,
                'role_id'  => 'required|exists:roles,id'
            ]);

            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }

            $user->update($request->only('name', 'email', 'role_id'));

            return $this->success('Usuario actualizado correctamente.', $user);

        } catch (ModelNotFoundException $e) {
            return $this->notFound('Usuario no encontrado.');
        }
    }

    /**
     * Cambiar contraseña.
     */
    public function changePassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password'     => 'required|min:6',
                'confirm_password' => 'required|same:new_password'
            ]);

            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->error('La contraseña actual es incorrecta.');
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return $this->success('Contraseña actualizada correctamente.');

        } catch (ModelNotFoundException $e) {
            return $this->notFound('Usuario no encontrado.');
        }
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->success('Usuario eliminado correctamente.');

        } catch (ModelNotFoundException $e) {
            return $this->notFound('Usuario no encontrado.');
        }
    }

    /* =======================================================
     * 🔧 MÉTODOS DE RESPUESTA ESTANDARIZADOS
     * =======================================================
     */

    private function success($message = null, $data = null)
    {
        return response()->json([
            'success' => 1,
            'message' => $message,
            'data'    => $data
        ]);
    }

    private function error($message)
    {
        return response()->json([
            'success' => 0,
            'message' => $message
        ]);
    }

    private function validationError($errors)
    {
        return response()->json([
            'success' => 0,
            'message' => 'Error de validación',
            'errors'  => $errors
        ], 422);
    }

    private function notFound($message)
    {
        return response()->json([
            'success' => 0,
            'message' => $message
        ], 404);
    }
}
