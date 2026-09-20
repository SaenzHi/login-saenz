<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    private function generarRespuesta($data, $clave, $msjExito, $msjError)
    {
        $success = !empty($data);
        $status = $success ? 200 : 404;

        return response()->json([
            'success' => $success,
            $clave => $success ? $data : null,
            'message' => $success ? $msjExito : $msjError,
            'status' => $status
        ], $status);
    }

    private function generarPreguntaxd($clave, $pedido, $msj)
    {
        return response()->json([
            'success' => true,
            $clave => $pedido,
            'message' => $msj,
            'status' => 200
        ], 200);
    }
    
    public function login()
    {
        return $this->generarPreguntaxd('login', ['correo' => null, 'passwordd' => null], 'Login disponible');
    }

    public function postLogin(Request $request)
    {
        $usuario = DB::select('CALL sp_Usuario_Login(?, ?)', [$request->correo, $request->passwordd]);
        return $this->generarRespuesta($usuario, 'usuario', 'Inicio de sesión correcto', 'Correo o contraseña incorrectos');
    }

    public function register()
    {
        return $this->generarPreguntaxd('registro', ['nombres' => null, 'correo' => null, 'passwordd' => null], 'Registro disponible');
    }

    public function save(Request $request)
    {
        DB::statement('CALL sp_Usuario_Guardar(?, ?, ?)', [$request->nombres, $request->correo, $request->passwordd]);
        $usuario = DB::select('SELECT * FROM Usuario WHERE Correo = ?', [$request->correo]);
        
        return $this->generarRespuesta($usuario, 'usuario', 'Usuario registrado correctamente', 'No se pudo registrar el usuario');
    }

    public function recover()
    {
        return $this->generarPreguntaxd('recuperar', ['correo' => null], 'Recuperación disponible');
    }

    public function sendCode(Request $request)
    {
        $usuario = DB::select('SELECT * FROM Usuario WHERE Correo = ?', [$request->correo]);

        if (empty($usuario)) {
            return response()->json([
                'success' => false,
                'codigo' => null,
                'message' => 'No existe un usuario con ese correo >:(',
                'status' => 404
            ], 404);
        }

        $codigo = DB::select('CALL sp_Usuario_Codigo(?)', [$request->correo]);
        return $this->generarRespuesta($codigo, 'codigo', 'Código generado correctamente', 'No se pudo generar el código');
    }

    public function validate()
    {
        return $this->generarPreguntaxd('validar', ['codigo' => null], 'Validación disponible');
    }

    public function validateCode(Request $request)
    {
        $codigo = DB::select('CALL sp_Usuario_Validar(?)', [$request->codigo]);
        return $this->generarRespuesta($codigo, 'codigo', 'Código válido', 'Código incorrecto o expirado');
    }

    public function passwordd()
    {
        return $this->generarPreguntaxd('passwordd', ['correo' => null, 'passwordd' => null], 'Servicio de cambio de contraseña disponible');
    }

    public function updatePasswordd(Request $request)
    {
        DB::statement('CALL sp_Usuario_UpdatePasswordd(?, ?)', [$request->correo, $request->passwordd]);
        $usuario = DB::select('SELECT * FROM Usuario WHERE Correo = ?', [$request->correo]);

        return $this->generarRespuesta($usuario, 'usuario', 'Contraseña actualizada correctamente', 'No existe el usuario');
    }
}