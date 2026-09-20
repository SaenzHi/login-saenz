<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    public function login()
    {
        $respuesta = [
            'success' => true,
            'login' => [
                'correo' => null,
                'passwordd' => null
            ],
            'message' => 'Login disponible',
            'status' => 200
        ];

        return response()->json($respuesta, 200);
    }



    public function post_login(Request $request)
    {
        $correo = $request->correo;
        $passwordd = $request->passwordd;

        $usuario = DB::select(
            'CALL sp_Usuario_Login(?, ?)',
            [$correo, $passwordd]
        );

        $success = count($usuario) > 0;
        $status = $success ? 200 : 404;

        $respuesta = [
            'success' => $success,
            'usuario' => $success ? $usuario : null,
            'message' => $success
                ? 'Inicio de sesión correcto'
                : 'Correo o contraseña incorrectos',
            'status' => $status
        ];

        return response()->json($respuesta, $status);
    }


    public function register()
    {
        $respuesta = [
            'success' => true,
            'registro' => [
                'nombres' => null,
                'correo' => null,
                'passwordd' => null
            ],
            'message' => 'Registro disponible',
            'status' => 200
        ];

        return response()->json($respuesta, 200);
    }



    public function save(Request $request)
    {
        $nombres = $request->nombres;
        $correo = $request->correo;
        $passwordd = $request->passwordd;

        DB::statement(
            'CALL sp_Usuario_Guardar(?, ?, ?)',
            [$nombres, $correo, $passwordd]
        );

        $usuario = DB::select('SELECT * FROM Usuario WHERE Correo = ?',[$correo]);

        $success = count($usuario) > 0;
        $status = $success ? 200 : 404;

        $respuesta = [
            'success' => $success,
            'usuario' => $success ? $usuario : null,
            'message' => $success
                ? 'Usuario registrado correctamente'
                : 'No se pudo registrar el usuario',
            'status' => $status
        ];

        return response()->json($respuesta, $status);
    }


    public function recover()
    {
        $respuesta = [
            'success' => true,
            'recuperar' => [
                'correo' => null
            ],
            'message' => 'Recuperación disponible',
            'status' => 200
        ];

        return response()->json($respuesta, 200);
    }



    public function send_code(Request $request)
    {
        $correo = $request->correo;

        $usuario = DB::select('SELECT * FROM Usuario WHERE Correo = ?',[$correo]);

        if (count($usuario) == 0) {
            return response()->json([
                'success' => false,
                'codigo' => null,
                'message' => 'No existe un usuario con ese correo',
                'status' => 404
            ], 404);
        }

        $resultado = DB::select(
            'CALL sp_Usuario_Codigo(?)',
            [$correo]
        );

        $success = count($resultado) > 0;
        $status = $success ? 200 : 404;

        $respuesta = [
            'success' => $success,
            'codigo' => $success ? $resultado : null,
            'message' => $success
                ? 'Código generado correctamente'
                : 'No se pudo generar el código',
            'status' => $status
        ];

        return response()->json($respuesta, $status);
    }


    public function validate()
    {
        $respuesta = [
            'success' => true,
            'validar' => [
                'codigo' => null
            ],
            'message' => 'Validación disponible',
            'status' => 200
        ];

        return response()->json($respuesta, 200);
    }



    public function validate_code(Request $request)
    {
        $codigo = $request->codigo;

        $resultado = DB::select('CALL sp_Usuario_Validar(?)',[$codigo]);

        $success = count($resultado) > 0;
        $status = $success ? 200 : 404;

        $respuesta = [
            'success' => $success,
            'codigo' => $success ? $resultado : null,
            'message' => $success
                ? 'Código válido'
                : 'Código incorrecto o expirado',
            'status' => $status
        ];

        return response()->json($respuesta, $status);
    }



    public function passwordd()
    {
        $respuesta = [
            'success' => true,
            'passwordd' => [
                'correo' => null,
                'passwordd' => null
            ],
            'message' => 'Servicio de cambio de contraseña disponible',
            'status' => 200
        ];

        return response()->json($respuesta, 200);
    }



     public function update_passwordd(Request $request)
    {
        $correo = $request->correo;
        $passwordd = $request->passwordd;

        DB::statement('CALL sp_Usuario_UpdatePasswordd(?, ?)',[$correo, $passwordd]);

        $usuario = DB::select('SELECT * FROM Usuario WHERE Correo = ?', [$correo]);

        $success = count($usuario) > 0;
        $status = $success ? 200 : 404;

        $respuesta = [
            'success' => $success,
            'usuario' => $success ? $usuario : null,
            'message' => $success
                ? 'Contraseña actualizada correctamente'
                : 'No existe el usuario',
            'status' => $status
        ];

        return response()->json($respuesta, $status);
    }

}
