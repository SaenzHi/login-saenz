<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login()
    {
        return response()->json([
            'success' => true,
            'login' => ['correo' => null, 'passwordd' => null],
            'message' => 'Login disponible',
            'status' => 200
        ], 200);
    }

    public function post_login(Request $request)
    {
        $usuarioLogueado = DB::select('CALL sp_Usuario_Login(?, ?)', [$request->correo, $request->passwordd]);
        $isSuccess = count($usuarioLogueado) > 0;
        $statusCode = $isSuccess ? 200 : 404;

        $responseData = [
            'success' => $isSuccess,
            'usuario' => $isSuccess ? $usuarioLogueado : null,
            'message' => $isSuccess ? 'Inicio de sesión correcto' : 'Correo o contraseña incorrectos',
            'status' => $statusCode
        ];
        return response()->json($responseData, $statusCode);
    }

    public function register()
    {
        return response()->json([
            'success' => true,
            'registro' => ['nombres' => null, 'correo' => null, 'passwordd' => null],
            'message' => 'Registro disponible',
            'status' => 200
        ], 200);
    }

    public function save(Request $request)
    {
        DB::statement('CALL sp_Usuario_Guardar(?, ?, ?)', [$request->nombres, $request->correo, $request->passwordd]);
        $usuarioGuardado = DB::select('SELECT * FROM Usuario WHERE Correo = ?', [$request->correo]);
        
        $isSuccess = count($usuarioGuardado) > 0;
        $statusCode = $isSuccess ? 200 : 404;

        $responseData = [
            'success' => $isSuccess,
            'usuario' => $isSuccess ? $usuarioGuardado : null,
            'message' => $isSuccess ? 'Usuario registrado correctamente' : 'No se pudo registrar el usuario',
            'status' => $statusCode
        ];
        return response()->json($responseData, $statusCode);
    }

    public function recover()
    {
        return response()->json([
            'success' => true,
            'recuperar' => ['correo' => null],
            'message' => 'Recuperación disponible',
            'status' => 200
        ], 200);
    }

    public function send_code(Request $request)
    {
        $usuarioEncontrado = DB::select('SELECT * FROM Usuario WHERE Correo = ?', [$request->correo]);

        if (count($usuarioEncontrado) == 0) {
            return response()->json([
                'success' => false,
                'codigo' => null,
                'message' => 'No existe un usuario con ese correo',
                'status' => 404
            ], 404);
        }

        $resultadoCodigo = DB::select('CALL sp_Usuario_Codigo(?)', [$request->correo]);
        $isSuccess = count($resultadoCodigo) > 0;
        $statusCode = $isSuccess ? 200 : 404;

        $responseData = [
            'success' => $isSuccess,
            'codigo' => $isSuccess ? $resultadoCodigo : null,
            'message' => $isSuccess ? 'Código generado correctamente' : 'No se pudo generar el código',
            'status' => $statusCode
        ];
        return response()->json($responseData, $statusCode);
    }

    public function validate()
    {
        return response()->json([
            'success' => true,
            'validar' => ['codigo' => null],
            'message' => 'Validación disponible',
            'status' => 200
        ], 200);
    }

    public function validate_code(Request $request)
    {
        $resultadoValidacion = DB::select('CALL sp_Usuario_Validar(?)', [$request->codigo]);
        $isSuccess = count($resultadoValidacion) > 0;
        $statusCode = $isSuccess ? 200 : 404;

        $responseData = [
            'success' => $isSuccess,
            'codigo' => $isSuccess ? $resultadoValidacion : null,
            'message' => $isSuccess ? 'Código válido' : 'Código incorrecto o expirado',
            'status' => $statusCode
        ];
        return response()->json($responseData, $statusCode);
    }

    public function passwordd()
    {
        return response()->json([
            'success' => true,
            'passwordd' => ['correo' => null, 'passwordd' => null],
            'message' => 'Servicio de cambio de contraseña disponible',
            'status' => 200
        ], 200);
    }

    public function update_passwordd(Request $request)
    {
        DB::statement('CALL sp_Usuario_UpdatePasswordd(?, ?)', [$request->correo, $request->passwordd]);
        $usuarioActualizado = DB::select('SELECT * FROM Usuario WHERE Correo = ?', [$request->correo]);

        $isSuccess = count($usuarioActualizado) > 0;
        $statusCode = $isSuccess ? 200 : 404;

        $responseData = [
            'success' => $isSuccess,
            'usuario' => $isSuccess ? $usuarioActualizado : null,
            'message' => $isSuccess ? 'Contraseña actualizada correctamente' : 'No existe el usuario',
            'status' => $statusCode
        ];
        return response()->json($responseData, $statusCode);
    }
}