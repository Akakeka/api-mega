<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Rol;
use Carbon\Carbon;
class Rol extends Model
{
    protected $table="roles";
    public $timestamps = false;
    protected $fillable = [
        'descripcion'
    ];

    public function rolSolcicitante($usuario){
    	$puede = DB::table('usuarios_roles')
    	->where('cod_usuario',$usuario)
    	->where('cod_rol',2)
    	->select('cod_usuario')
		->max('cod_usuario');
		return $puede;
    }
    public function rolPrestador($usuario){
    	$puede = DB::table('usuarios_roles')
    	->where('cod_usuario',$usuario)
    	->where('cod_rol',1)
    	->select('cod_usuario')
		->max('cod_usuario');
		return $puede;
    }

    public function createRol($request){
    	$rol= Rol::create([
            'descripcion' => $request->descripcion,
        ]);
        return $rol;
    }

	public function roles(){
    	$rol= DB::table('roles')->select('cod','descripcion')->get()->toArray();
        return $rol;
    }
    public function asignar($request){
    	$rolId = DB::table('usuarios_roles')
    			->where('cod_usuario',11)
    			->where('cod_rol',$request->perfil)
    			->select('cod_usuario')
    			->max('cod_usuario');
		if ($rolId) {
			$status = 300;
		}else{
			$usuario_rol = DB::table('usuarios_roles')->insert(['cod_usuario'=>11,'cod_rol'=>$request->perfil]);
			if ($request->perfil == 1) {
				DB::table('prestadores')->insert(['cod_usuario'=>11]);
			}else{
				DB::table('solicitantes')->insert(['cod_usuario'=>11]);
			}
			$status = 200;
		}
		return $status;
    }
    public function crearCita($request){
    	$puede = $this->rolPrestador(11);
		if ($puede) {
			$usuario_rol = DB::table('citas')
			->insert(['descripcion'=>$request->descripcion,'cupos_totales'=>$request->cupos_totales,'cupos_disponibles'=>$request->cupos_totales,'cod_usuario_prestador'=>11,'fecha'=>Carbon::now()]);
			$status = 200;
		}else{
			$status = 300;
		}
		return $status;
    }


    public function suscribir($request){
    	$puede = $this->rolSolcicitante(11);
		if ($puede) {
			$existe = DB::table('solicitantes_prestadores')
	    	->where('cod_usuario_solicitante',11)
	    	->where('cod_usuario_prestador',$request->prestador)
	    	->select('cod_usuario_solicitante')
			->max('cod_usuario_solicitante');
			if ($existe) {
				$status = 301;	
			}else{
				DB::table('solicitantes_prestadores')->insert(['cod_usuario_solicitante'=>11,'cod_usuario_prestador'=>$request->prestador]);
				$status = 200;	
			}
			
		}else{
			$status = 300;
		}
		return $status;
    }

    public function cupoInCita($cod_cita){
    	$si = DB::table('cupos')
			->where('cod_cita',$cod_cita)
			->where('cod_usuario_solicitante',11)
			->select('cod_usuario_solicitante')
			->max('cod_usuario_solicitante');
		return $si;
    }

    public function actualizarCupos($cod_cita,$cupo){
    	$update = DB::table('citas')
    		->where('cod',$cod_cita)
			->update(['cupos_disponibles'=>$cupo]);
		return $update;

    }
    public function asignarCupos($request){
		$puede = $this->rolSolcicitante(11);
		if ($puede) {
			$cita = DB::table('citas')
			->select('cupos_totales','cupos_disponibles')
			->where('cod',$request->cita)
			->get()->toArray();
			if ($cita) {
				$si = $this->cupoInCita($request->cita);
				if ($si) {
					$status = 401;
				}else{
					$cupos = (int) $cita[0]->cupos_disponibles;
					if ($cupos > 0) {
						$cupo = DB::table('cupos')->insert(['cod_cita'=>$request->cita,'cod_usuario_solicitante'=>11,'usuario_cod_rol'=>2]);
						if ($cupo) {
							$nuevo_cupo = $cupos - 1;
							$update = $this->actualizarCupos($request->cita,$nuevo_cupo);
							$status = 200;
						}else{
							$status = 201;
						}
					}else{
						$status = 402;
					}	
				}
				
			}else{
				$status = 303;
			}
		}else{
			$status = 403;
		}

		return $status;

    }

    public function getCupos($request){
    	$cupos = DB::table('cupos')
    			->select('cod_cita','cod_usuario_solicitante','usuario_cod_rol')
    			->where('cod_cita',$request->cita)
    			->get()->toArray();
		return $cupos;
    }
}
