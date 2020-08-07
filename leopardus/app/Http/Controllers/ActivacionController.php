<?php

namespace App\Http\Controllers;
use App\User;

use Carbon\Carbon;
use App\Http\Controllers\IndexController;


class ActivacionController extends Controller
{

    /**
     * Verifica que es estado de los usuarios 
     * 
     * @access public 
     * @param int $userid - id del usuarios a verificar
     * @return string
     */
    public function activarUsuarios($userid)
    {
        $user = User::find($userid);
        $index = new IndexController();
        $inversiones = $index->getInversionesUserDashboard($userid);
        if (count($inversiones) > 0) {
            $user->status = 1;
        }else{
            $user->status = 0;
        }
        $user->save();
    }

    /**
     * Permite verificar el estado del usuario
     *
     * @param object $user
     * @return bool
     */
    private function statusActivacion($user): bool
    {
        $result = true;
        $fechaActual = Carbon::now();
        if (empty($user->fecha_activacion)) {
            $result = false;
        }else{
            $fechatmp = new Carbon($user->fecha_activacion);
            if ($fechatmp < $fechaActual) {
                $result = false;
            }
        }
        return $result;
    }

}
