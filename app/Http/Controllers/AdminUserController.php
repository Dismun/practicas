<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;


class AdminUserController extends Controller
{
    public function index(Request $request)
    {
    	// dd($request);
    //	if(count($request->all())==0) {
    		$rol=$request['rol'];
    		$usuarios=User::name($request['name']);
    		$usuarios=$usuarios->email($request['email']);
    		if($rol!=0)
    			$usuarios=$usuarios->whereHas('roles',function($q) use ($rol){
    				$q->where('role_id',$rol);
    			});
    		$usuarios = $usuarios->get();
    //	}
  //  	else{
    //		if(trim($request['name'])!='')
    //			$usuarios=User::name($request['name'])->get();
   // 		if($request['mail']=='')
    //				$usuarios=array();
  //  	}
    	$todoslosroles=Role::all();
    	// dd( $usuarios[2]->roles[0]->name);
        return view('users.index',compact('usuarios','todoslosroles'));
    	
    }

    public function meterol_ajax(Request $request){
    	$salida['error'] = 'Ok';
    	$salida['rol']=Role::select('description')->where('id',$request['rol_id'])->first();
  		//dd($salida);  
    	$user=User::find($request['user_id']);
    	//dd($user->roles, $request['rol_id']);
    	foreach($user->roles as $rol){
    		if($rol->id == $request['rol_id']) {
    			$salida['error']=' Este Rol ya lo posee el usuario';
    			break;
    		}
    	}
    	if($salida['error']=='Ok') {
    		$user->roles()->attach($request['rol_id']);
    		$user->save();
    	}
    	if(is_null($user)){
    		$salida['error']='No se ha podido localizar al usuario';
    	}
    	return $salida;
    }

    public function quitarol_ajax(Request $request){
    	$salida['error'] = 'Ok';
    	$user=User::find($request['user_id']);

    	if($salida['error']=='Ok') {
    		$user->roles()->detach($request['rol_id']);
    		$user->save();
    	}
    	if(is_null($user)){
    		$salida['error']='No se ha podido localizar al usuario';
    	}
    	return $salida;
    }


    public function actualizausuario_ajax(Request $request){
    	//dd( Auth::user() );
    	$salida['error'] = 'Ok';
    	$user=User::find($request['user_id']);
    	$user->name=$request['name'];
    	$user->email=$request['mail'];
    	//dd($request['user_id'],$request['name'],$request['mail']);
    	if($salida['error']=='Ok') {
    		$user->save();
    	}
    	if(is_null($user)){
    		$salida['error']='No se ha podido localizar al usuario';
    	}
    	return $salida;
    }

    public function borrausuario_ajax(Request $request){
    	// dd( Auth::user() );
    	$salida['error'] = 'Ok';
    	$user=User::find($request['user_id']);
    	if(is_null($user)){
    		$salida['error']='No se ha podido localizar al usuario';
    	}
    	if($salida['error']=='Ok') {
    		$user->roles()->detach(null);
    		$user->delete();
    	}
    	
    	return $salida;
    } 
}
