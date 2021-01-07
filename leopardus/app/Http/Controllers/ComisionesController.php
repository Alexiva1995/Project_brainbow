<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User; 
use App\Commission;
use App\OrdenInversion;
use App\SettingsComision;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WalletController;
use App\SettingsBono;
use App\WalletlogRentabilidad;

class ComisionesController extends Controller
{

  // Obtiene al usuario a a todo los usuarios
  public function ObtenerUsuarios()
  {
    $GLOBALS['settingsComision'] = SettingsComision::find(1);
    if (Auth::user()->rol_id == 0) {
        // $this->bonoUnilevel(Auth::user()->ID);
    } else {
        // $this->bonoDirecto();
    }
  }


  // guarda la comision una vez procesada
  public function guardarComision($iduser, $idcompra, $totalComision, $referred_email, $referred_level, $concepto, $tipo_comision)
  {
            $dinero = 0;
            $dinero = $totalComision;
            $comision = new Commission();
            $comision->user_id = $iduser;
            $comision->compra_id = $idcompra;
            $comision->date = Carbon::now();
            $comision->total = $totalComision;
            $comision->concepto = $concepto;
            $comision->tipo_comision = $tipo_comision;
            $comision->referred_email = $referred_email;
            $comision->referred_level = $referred_level;
            $comision->status = 0;

            $user = User::find($iduser);
            $user->wallet_amount = ($user->wallet_amount + $dinero);
            $user->save();
            $datos = [
                'iduser' => $iduser,
                'usuario' => $user->display_name,
                'descripcion' => $concepto,
                'descuento' => 0,
                'debito' => $dinero,
                'credito' => 0,
                'balance' => $user->wallet_amount,
                'tipotransacion' => 2,
                'status' => 0,
                'correo' => $referred_email
            ];
            $funciones = new WalletController;
            $funciones->saveWallet($datos);
            $comision->save();
  }




  /**
   * Permite pagar el pono unilevel
   *
   * @return void
   */
    public function bonoDirecto()
    {
        $funciones = new IndexController;
        $inversiones = $this->getInversiones(false);
        foreach ($inversiones as $inversion) {
            $paquete = $funciones->getProductDetails($inversion->paquete_inversion);
            if (!empty($paquete)) {
                $sponsors = $funciones->getSponsor($inversion->iduser, [], 2, 'ID', 'referred_id');
                foreach ($sponsors as $sponsor) {
                    $user = User::find($inversion->iduser);
                    if ($sponsor->ID != $inversion->iduser) {
                        $idcomision = $inversion->iduser.$sponsor->ID.$inversion->id.'40';
                        $porcentaje = 0;
                        $bonodirecto = SettingsBono::where('type_bono', 'directo')->first();
                        $bonodirecto->settings_bono = json_decode($bonodirecto->settings_bono);
                        if (stripos($paquete->post_title, 'BRONCE') !== false) {
                            $porcentaje = $bonodirecto->settings_bono->bronce;
                        }
                        if (stripos($paquete->post_title, 'PLATA') !== false) {
                            $porcentaje = $bonodirecto->settings_bono->plata;
                        }
                        if (stripos($paquete->post_title, 'ORO') !== false) {
                            $porcentaje = $bonodirecto->settings_bono->oro;
                        }
                        $check = DB::table('commissions')
                                ->select('id')
                                ->where('user_id', '=', $sponsor->ID)
                                ->where('compra_id', '=', $idcomision)
                                ->first();
                        if ($check == null && $porcentaje != 0) {
                            $pagar = ($inversion->invertido * $porcentaje);
                            $concepto = 'Bono Directo, usuario '.$user->display_name;
                            $this->guardarComision($sponsor->ID, $idcomision, $pagar, $user->user_email, 1, $concepto, 'referido');
                        }
                    }
                }
            }
        }
    }


    /**
     * Permite pagar el pono unilevel
     *
     * @return void
     */
    public function bonoBlackBox()
    {
        $funciones = new IndexController;
        $fecha = Carbon::now();
        $inversiones = OrdenInversion::where([
            ['status', '=', 1],
            ['paquete_inversion', '=', 0]
        ])->whereDate('created_at', '>', $fecha->subDay(20))->get();
        foreach ($inversiones as $inversion) {
            $sponsors = $funciones->getSponsor($inversion->iduser, [], 2, 'ID', 'referred_id');
            foreach ($sponsors as $sponsor) {
                $user = User::find($inversion->iduser);
                if ($sponsor->ID != $inversion->iduser) {
                    $idcomision = $inversion->iduser.$sponsor->ID.$inversion->id.'20';
                    $check = DB::table('commissions')
                            ->select('id')
                            ->where('user_id', '=', $sponsor->ID)
                            ->where('compra_id', '=', $idcomision)
                            ->first();
                    if ($check == null) {
                        $bonoblackbox = SettingsBono::where('type_bono', 'blackbox')->first();
                        $bonoblackbox->settings_bono = json_decode($bonoblackbox->settings_bono);
                        $pagar = $bonoblackbox->settings_bono->blackbox;
                        $concepto = 'Bono BlackBox, usuario '.$user->display_name;
                        $this->guardarComision($sponsor->ID, $idcomision, $pagar, $user->user_email, 1, $concepto, 'referido');
                    }
                }
            }
        }
    }


    /**
     * Permite verificar si una comision ya fue cobrada
     *
     * @param integer $idcompra
     * @param integer $iduser
     * @return void
     */
    public function checkComision($idcompra, $iduser)
    {
        $result = false;
        $check = DB::table('commissions')
                ->select('id')
                ->where('user_id', '=', $iduser)
                ->where('compra_id', '=', $idcompra)
                ->first();
        if ($check == null) {
            $result = true;
        }
        return $result;
    }


    /**
     * Permite pagar el bono matriz
     *
     * @param integer $iduser
     * @return void
     */
    public function bonoMatrix($iduser)
    {
        $fechaActual = Carbon::now();
        $funciones = new IndexController;
        $TodosUsuarios = $funciones->getChidrens2($iduser, [], 1, 'position_id', 0);
        $invertido = OrdenInversion::where([
            ['iduser', '=', $iduser],
            ['status', '=', 1],
            ['paquete_inversion', '!=', 0]
        ])->whereDate('fecha_fin', '>=', $fechaActual)->get()->sum('invertido');
        
        foreach ($TodosUsuarios as $usuario) {
            $invertidoReferidos = OrdenInversion::where([
                ['iduser', '=', $usuario->ID],
                ['status', '=', 1],
                ['paquete_inversion', '!=', 0]
            ])->whereDate('fecha_fin', '>=', $fechaActual)->get()->sum('invertido');
            $idcomision = $iduser.$usuario->ID.Carbon::now()->format('dmY');
            // para inversiones mayores de 500 y menores de 1000
            if ($invertido >= 500 && $invertido < 1000) {
                if ($usuario->nivel <= 3) {
                    if ($invertidoReferidos >= 1000) {
                        
                        $check = DB::table('commissions')
                                ->select('id')
                                ->where('user_id', '=', $iduser)
                                ->where('compra_id', '=', $idcomision)
                                ->first();
                        if ($check == null) {
                            $pagar = $this->getMontoAPagar($usuario->nivel);
                            $concepto = 'Bono Matriz, usuario '.$usuario->display_name;
                            $this->guardarComision($iduser, $idcomision, $pagar, $usuario->user_email, $usuario->nivel, $concepto, 'referido');
                        }
                    }
                }
            }
            // Para las inversiones mayores 1000 y menores de 2000
            if ($invertido >= 1000 && $invertido < 2000) {
                if ($usuario->nivel <= 6) {
                    if ($invertidoReferidos >= 1000) {
                        $check = DB::table('commissions')
                                ->select('id')
                                ->where('user_id', '=', $iduser)
                                ->where('compra_id', '=', $idcomision)
                                ->first();
                        if ($check == null) {
                            $pagar = $this->getMontoAPagar($usuario->nivel);
                            $concepto = 'Bono Matriz, usuario '.$usuario->display_name;
                            $this->guardarComision($iduser, $idcomision, $pagar, $usuario->user_email, $usuario->nivel, $concepto, 'referido');
                        }
                    }
                }
            }

            // Para las inversiones mayores 2000
            if ($invertido >= 2000) {
                if ($usuario->nivel <= 10) {
                    if ($invertidoReferidos >= 1000) {
                        $check = DB::table('commissions')
                                ->select('id')
                                ->where('user_id', '=', $iduser)
                                ->where('compra_id', '=', $idcomision)
                                ->first();
                        if ($check == null) {
                            $pagar = $this->getMontoAPagar($usuario->nivel);
                            $concepto = 'Bono Matriz, usuario '.$usuario->display_name;
                            $this->guardarComision($iduser, $idcomision, $pagar, $usuario->user_email, $usuario->nivel, $concepto, 'referido');
                        }
                    }
                }
            }
        }
    }


    /**
     * Permite obtener lo que va pagar por el bono matriz
     *
     * @param integer $nivel
     * @return integer
     */
    public function getMontoAPagar(int $nivel): int
    {
        // $arreMonto = [
        //     1 => 8, 2 => 6, 3 => 5, 4 => 4, 5 => 3,
        //     6 => 3, 7 => 2, 8 => 2, 9 => 1, 10 => 1
        // ];

        $bonomatrix = SettingsBono::where('type_bono', 'matrix')->first();
        $bonomatrix->settings_bono = json_decode($bonomatrix->settings_bono);

        
        return $bonomatrix->settings_bono->$nivel;
    }

    /**
     * Permite pagar todas las inversiones realizadas
     *
     * @return void
     */
    public function getRentabilidad()
    {
        $fechaActual = Carbon::now();
        $funciones = new IndexController;
        $inversiones = $this->getInversiones(true);
        foreach ($inversiones as $inversion) {
            $paquete = $funciones->getProductDetails($inversion->paquete_inversion);
            $user = User::find($inversion->iduser);
            if (!empty($paquete)) {
                $fechaInversion = new Carbon($inversion->created_at);
                if ($fechaInversion->addMonth($paquete->duration) > $fechaActual) {
                    $fechatmpSemana = Carbon::now();
                    $semanayear = $fechatmpSemana->weekOfYear;
                    $year = $fechatmpSemana->year;
                    // $semanas = ($paquete->duration / 4.345);
                    // $porcentaje = ($paquete->rentabilidad / 100);
                    $porcentaje = 0;
                    if (stripos($paquete->post_title, 'BRONCE') !== false) {
                        $porcentaje = 0.0125;
                    }
                    if (stripos($paquete->post_title, 'PLATA') !== false) {
                        $porcentaje = 0.0104;
                    }
                    if (stripos($paquete->post_title, 'ORO') !== false) {
                        $porcentaje = 0.0083;
                    }
                    $rentabilidad = ($inversion->invertido * $porcentaje);
                    dump('Inversion '.$inversion->invertido.' - pago rentabilidad '.$rentabilidad. ' - Idusuario '.$inversion->iduser);
                    $user->rentabilidad = ($user->rentabilidad + $rentabilidad);
                    $data = [
                        'iduser' => $inversion->iduser,
                        'idinversion' => $inversion->id,
                        'concepto' => 'Rentabilidad de la semana '.$semanayear.' del año '.$year,
                        'debito' => $rentabilidad,
                        'credito' => 0,
                        'balance' => $user->rentabilidad,
                        'semana' => $semanayear,
                        'year' => $year,
                        'fecha_pago' => Carbon::now(),
                        'descuento' => 0,
                    ];

                    $this->sabeWalletRentabilidad($data);
                    $user->save();
                }
            }
        }
    }

    /**
     * Permite obtener la informacion para la barra de progreso
     *
     * @param integer $iduser
     * @return array
     */
    public function getBarraRentabilidad(int $iduser): array
    {
        $data = [];
        $fecha = Carbon::now();
        $funciones = new IndexController;
        $inversion = OrdenInversion::where([
            ['status', '=', 1],
            ['paquete_inversion', '!=', 0],
            ['iduser', '=', $iduser]
        ])->whereDate('fecha_fin', '>=', $fecha)->first();

        if ($inversion != null) {
            $paquete = $funciones->getProductDetails($inversion->paquete_inversion);
            $maximoRentabilidad = ($inversion->invertido * '1'.$paquete->rentabilidad);
            $ganancia = WalletlogRentabilidad::where([
                ['idinversion', '=', $inversion->id],
                ['semana', '!=', '']
            ])->get()->sum('debito');
            $porcentage = (($ganancia * 100) / $maximoRentabilidad);
            $data = [
                'maximo' => $maximoRentabilidad,
                'progreso' => $ganancia,
                'progre_porc' => $porcentage
            ];
        }else{
            $data = [
                'maximo' => 0,
                'progreso' => 0,
                'progre_porc' => 0
            ];
        }

        return $data;
    }


    /**
     * Permite guardar la informacion de las rentabilidad
     *
     * @param array $data
     * @return void
     */
    public function sabeWalletRentabilidad(array $data)
    {
        $result = WalletlogRentabilidad::create($data);
        return $result->id;
    }

    /**
     * Permite obtener las inversiones realizadas por los usuarios
     *
     * @return object
     */
    public function getInversiones($rentabilidad) : object
    {
        if ($rentabilidad) {
            $inversiones = OrdenInversion::where([
                ['status', '=', 1],
                ['paquete_inversion', '!=', 0]
            ])->get();
        }else{
            $fecha = Carbon::now();
            $inversiones = OrdenInversion::where([
                ['status', '=', 1],
                ['paquete_inversion', '!=', 0]
            ])->whereDate('created_at', '>', $fecha->subDay(20))->get();
        }

        return $inversiones;
    }
}

