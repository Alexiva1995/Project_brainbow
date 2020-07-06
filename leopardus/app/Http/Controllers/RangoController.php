<?php

namespace App\Http\Controllers;



use App\Commission;
use App\OrdenInversion;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\SettingsRol;
use App\Rol;
use App\Http\Controllers\ComisionesController;
use App\Http\Controllers\IndexController;

class RangoController extends Controller
{
	function __construct()
	{
     
    }

    /**
     * Verifica toda las condiciones para subir de rango al usuario
     * 
     * @param integer $user_id - el id de usuario a actualizar
     */
    public function ValidarRango($iduser)
    {
        $user = User::find($iduser);
        $rol_actual = $user->rol_id;
        $rol_new = $user->rol_id + 1;
        $rol = Rol::find($rol_new);
        $cantrol = Rol::all()->count('ID');

        $cantrequisito = 0;
        $cantaprobado = 0;

        if($cantrol > $rol_new){ 

            $cantrequisito++;
            if ($this->checkPuntos($iduser, $rol->grupal)) {
                $cantaprobado++;
            }

            if ($rol_actual == $rol->rolprevio) {
                if ($cantrequisito == $cantaprobado) {
                    $this->ActualizarRango($iduser, $rol_new);
                }
            }
        }
    }

    /**
     * Sube de Rango al Usuario
     * 
     * @access private
     * @param int $iduser - id usuario, int $rol_new - el rango a subir
     */
    private function ActualizarRango($iduser, $rol_new)
    {
        $usuario = User::find($iduser);
        $usuario->rol_id = $rol_new;
        $usuario->save();
        $rol = Rol::find($rol_new);
        if ($rol->bonos > 0) {
            $comision = new ComisionesController;
            $concepto = 'Bono Liderazgo, Rango: '.$rol->name;
            $comision->guardarComision($iduser, 10, $rol->bonos, Auth::user()->user_email, 0, $concepto, 'bono');
        }
    }

    /**
     * Permite verificar la cantidad de puntos necesarios para subir de nivel
     *
     * @param integer $iduser - id del usuario a verificar
     * @param integer $requisito - requisito necesario para subir de nivel
     * @return void
     */
    public function checkPuntos($iduser, $requisito)
    {
        $result = false;
        $miInversion = $this->getTotalInvertion($iduser);
        $redInversion = $this->getTotalInvertionRed($iduser);
        $sumInversion = ($miInversion + $redInversion);
        $puntos = ($sumInversion / 500);

        if ($puntos >= $requisito) {
            $result = true;
        }
        return $result;
    }

    /**
     * Permite obtener el total que he invetido
     *
     * @param integer $iduser
     * @return float
     */
    public function getTotalInvertion($iduser) : float
    {
        $result = OrdenInversion::where([
            ['iduser', '=', $iduser],
            ['status', '=', 1]
        ])->get()->sum('invetido');

        return $result;
    }

    /**
     * Permite obtener el total que ha invertido toda mi red
     *
     * @param integer $iduser
     * @return float
     */
    private function getTotalInvertionRed($iduser): float
    {
        $result = 0;
        $funciones = new IndexController();
        $users = $funciones->getChidrens2($iduser, [], 1, 'referred_id', 0);
        foreach ($users as $user) {
            if ($user->nivel < 11) {
                $totalRed = OrdenInversion::where([
                    ['iduser', '=', $user->ID],
                    ['status', '=', 1]
                ])->get()->sum('invetido');
                $result = ($result + $totalRed);
            }
        }
        return $result;
    }


}
