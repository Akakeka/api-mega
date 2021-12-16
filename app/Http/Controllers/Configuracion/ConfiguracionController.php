<?php

namespace App\Http\Controllers\Configuracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Rol;


class ConfiguracionController extends Controller
{
	protected $rol;
    public function __construct(Rol $rol)
    {
    	$this->rol = $rol;
        // $this->middleware('auth');
    }

    public function storeRol(Request $request){
    	// dd($request);
    	$rol = $this->rol->createRol($request);
    	$respuesta = [
            'status' => 'Rol Registrado',
            'data' => compact('rol')
        ];
        return response($respuesta);
    }

    public function roles(Request $request){
    	$roles = $this->rol->roles();
    	$respuesta = [
            'status' => 'ok',
            'data' => compact('roles')
        ];
        return response($respuesta);
    }

    public function asignarPerfil(Request $request){
    	$rol = $this->rol->asignar($request);
    	if ($rol == 200) {
    		$mensaje = 'Perfil Asignado';
    	}else {
    		$mensaje = 'Su usuario ya tiene el perfil solicitado';
    	}
    	$respuesta = [
            'status' => $mensaje,
        ];
        return response($respuesta);
    }

    public function storeCita(Request $request){
    	$rol = $this->rol->crearCita($request);
    	if ($rol == 200) {
    		$mensaje = 'Cita Creada';
    	}else {
    		$mensaje = 'No tiene configurado rol para crear cita';
    	}
    	$respuesta = [
            'status' => $mensaje,
        ];
        return response($respuesta);
    }
    public function suscribirse(Request $request){
    	$Suscripcion = $this->rol->suscribir($request);
    	if ($Suscripcion == 200) {
    		$mensaje = 'Suscripcion realizada';
    	}elseif ($Suscripcion == 301) {
    			$mensaje = 'Ya estas suscrito al prestador solicitado';
    	}else {
    		$mensaje = 'No tiene configurado rol para suscribirse a prestador';
    	}
    	$respuesta = [
            'status' => $mensaje,
        ];
        return response($respuesta);
    }

    public function AsociarCupoCita(Request $request){
    	$status = $this->rol->asignarCupos($request);
    	if ($status == 200) {
    		$mensaje = 'Cupo Asignado';
    	}elseif ($status == 403) {
    			$mensaje = 'No tiene configurado rol para asignar cupos a citas';
    	}elseif ($status == 401) {
    			$mensaje = 'Ya tiene un cupo asignado para esta cita';
    	}elseif ($status == 402) {
    			$mensaje = 'No existe cupos disponibles para esta cita';
    	}elseif ($status == 303) {
    			$mensaje = 'No existe la cita a la cual quiere asignar cupo';
    	}else {
    		$mensaje = 'Error al registrar cupo';
    	}
    	$respuesta = [
            'status' => $mensaje,
        ];
        return response($respuesta);
    }

    public function cupos(){
        $cupos = $this->rol->getCupos($request);
        
       $respuesta = [
            'status' => 'ok',
            'data' => compact('cupos')
        ];
        return response($respuesta);
    }
}
