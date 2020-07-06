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
        $this->bonoDirecto(Auth::user()->ID);
    }
  }

  /**
   * Permite recorrer la matriz para obtener los puntos necesarios
   *
   * @param [type] $iduser
   * @return void
   */
  public function recordPoint($iduser)
  {
        $funciones = new IndexController;
        $directoMatrix = User::where('position_id', $iduser)->get();
        if ($directoMatrix->isNoEmpty()) {
            foreach ($directoMatrix as $directo) {
                if ($directo->ladomatrix == 'D') {
                    $this->detalleComision($directo->ID, $directo->user_email, 'D');
                    $arregloD = $funciones->getChidrens2($directo->ID, [], 2, 'position_id', 1);
                    if (!empty($arregloD)) {
                        foreach ($arregloD as $user) {
                            if ($user->nivel < 7) {
                                $this->detalleComision($user->ID, $user->user_email, 'D');
                            }
                        }
                    }
                }
                if ($directo->ladomatrix == 'I') {
                    $this->detalleComision($directo->ID, $directo->user_email, 'I');
                    $arregloI = $funciones->getChidrens2($directo->ID, [], 2, 'position_id', 1);
                    if (!empty($arregloI)) {
                        foreach ($arregloI as $user) {
                            if ($user->nivel < 7) {
                                $this->detalleComision($user->ID, $user->user_email, 'I');
                            }
                        }
                    }
                }
            }
        }
    }

  /**
   * Permite verificar el detalle de la compra 
   *
   * @param integer $iduser
   * @param string $email
   * @param string $side
   * @return void
   */
  public function detalleComision($iduser, $email, $side)
  {
        $funciones = new IndexController;
        $compras = $funciones->getInforShopping($iduser);
        foreach ($compras as $compra) {
            $idcomision = '10'.$compra['idcompra'];
            if ($this->checkComision($idcomision, $iduser)) {
                foreach ($compra['productos'] as $producto) {
                    $this->PuntosPaquetes($iduser, $producto['precio'], $email, $side);
                }
            }
        }
    }


  /**
   * Agrega los puntos obtenido por los paquetes comprando mis usuarios
   *
   * @param integer $iduser - id usuario
   * @param integer $totalcomision - puntos obtenidos
   * @return void
   */
  public function PuntosPaquetes(int $iduser, float $totalcomision, string $referred_email, $lado)
  {
        if ($iduser != 1) {
            $user = User::find($iduser);
            $referido = User::where('user_email', $referred_email)->first();
            $puntosI = 0; $puntosD = 0;
            if ($referido->ID != $iduser) {
                if ($lado == 'I') {
                    $user->puntosizq = ($user->puntosizq + $totalcomision);
                    $puntosI = $totalcomision;
                }elseif($lado == 'D'){
                    $user->puntosder = ($user->puntosder + $totalcomision);
                    $puntosD = $totalcomision;
                }
                $user->save();
                $concepto = 'Puntos por las compras del usuario '.$referido->display_name;
                $datos = [
                    'iduser' => $iduser,
                    'usuario' => $user->display_name,
                    'descripcion' => $concepto,
                    'puntos' => 0,
                    'puntosI' => $puntosI,
                    'puntosD' => $puntosD,
                    'tantechcoin' => 0,
                    'descuento' => 0,
                    'debito' => 0,
                    'credito' => 0,
                    'balance' => 0,
                    'tipotransacion' => 2
                ];
                $funciones = new WalletController;
                $funciones->saveWallet($datos);
            }
        }
    }

  // guarda la comision una vez procesada
  public function guardarComision($iduser, $idcompra, $totalComision, $referred_email, $referred_level, $concepto, $tipo_comision)
  {
            $dinero = 0; $puntos = 0;
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
            $comision->status = true;

            if ($concepto != 'Primera Compra sin Comision') {
                $user = User::find($iduser);
                if ($user->porc_rentabilidad < $user->rentabilidad) {
                    
                    if ($idcompra == 51) {
                        $user->porc_rentabilidad = ($user->porc_rentabilidad + $totalComision);
                        if ($user->porc_rentabilidad >= $user->rentabilidad) {
                            $user->porc_rentabilidad = $user->rentabilidad;
                        }
                    }
                    $user->wallet_amount = ($user->wallet_amount + $dinero);
                    $user->save();
                    $datos = [
                        'iduser' => $iduser,
                        'usuario' => $user->display_name,
                        'descripcion' => $concepto,
                        'puntos' => 0,
                        'puntosI' => 0,
                        'puntosD' => 0,
                        'descuento' => 0,
                        'debito' => $dinero,
                        'tantechcoin' => 0,
                        'credito' => 0,
                        'balance' => $user->wallet_amount,
                        'tipotransacion' => 2
                    ];
                    $funciones = new WalletController;
                    $funciones->saveWallet($datos);
                }else{
                    if ($user->ID == 1) {
                        $user->wallet_amount = ($user->wallet_amount + $dinero);
                        $user->save();
                        $datos = [
                            'iduser' => $iduser,
                            'usuario' => $user->display_name,
                            'descripcion' => $concepto,
                            'puntos' => 0,
                            'puntosI' => 0,
                            'puntosD' => 0,
                            'descuento' => 0,
                            'debito' => $dinero,
                            'tantechcoin' => 0,
                            'credito' => 0,
                            'balance' => $user->wallet_amount,
                            'tipotransacion' => 2
                        ];
                        $funciones = new WalletController;
                        $funciones->saveWallet($datos);
                    }
                }
            }
            $comision->save();
  }




  /**
   * Permite pagar el pono unilevel
   *
   * @param integer $iduser
   * @return void
   */
    public function bonoDirecto($iduser)
    {
        $user = User::find($iduser);
        $funciones = new IndexController;
        $TodosUsuarios = $funciones->getChidrens2($iduser, [], 1, 'referred_id', 1);
        $bono = 0;
        foreach ($TodosUsuarios as $user) {
            if ($user->nivel == 1) {
                $inversiones = OrdenInversion::where('iduser', $user->ID)->get();
                if (!empty($user->paquete)) {
                    $paquete = json_decode($user->paquete);
                    $porcentaje = 0;
                    if (stripos($paquete->nombre, 'BRONCE') !== false) {
                        $porcentaje = 0.01;
                    }
                    if (stripos($paquete->nombre, 'PLATA') !== false) {
                        $porcentaje = 0.02;
                    }
                    if (stripos($paquete->nombre, 'ORO') !== false) {
                        $porcentaje = 0.03;
                    }
                    foreach ($inversiones as $inversion) {
                        $idcomision = $iduser.$user->ID.$inversion->id.'40';
                        if ($this->checkComision($idcomision, $iduser) && $porcentaje != 0) {
                            $pagar = ($inversion->invertido * $porcentaje);
                            $concepto = 'Bono Directo, usuario '.$user->display_name;
                            $this->guardarComision($iduser, $idcomision, $pagar, $user->email, $user->nivel, $concepto, 'referido');
                        }
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
        $funciones = new IndexController;
        $user = User::find($iduser);
        $TodosUsuarios = $funciones->getChidrens2($iduser, [], 1, 'position_id', 0);
        $invertido = OrdenInversion::where('iduser', $iduser)->get()->sum('invertido');
        
        foreach ($TodosUsuarios as $usuario) {
            $invertidoReferidos = OrdenInversion::where('iduser', $usuario->ID)->get()->sum('invertido');
            $idcomision = $iduser.$usuario->ID.Carbon::now()->format('dmY');
            // para inversiones mayores de 500 y menores de 1000
            if ($invertido >= 500 && $invertido < 1000) {
                if ($usuario->nivel <= 3) {
                    if ($invertidoReferidos >= 1000) {
                        if ($this->checkComision($idcomision, $iduser)) {
                            $pagar = $this->getMontoAPagar($usuario->nivel);
                            $concepto = 'Bono Matriz, usuario '.$usuario->display_name;
                            $this->guardarComision($iduser, $idcomision, $pagar, $user->email, $user->nivel, $concepto, 'referido');
                        }
                    }
                }
            }
            // Para las inversiones mayores 1000 y menores de 2000
            if ($invertido >= 1000 && $invertido < 2000) {
                if ($usuario->nivel <= 6) {
                    if ($invertidoReferidos >= 1000) {
                        if ($this->checkComision($idcomision, $iduser)) {
                            $pagar = $this->getMontoAPagar($usuario->nivel);
                            $concepto = 'Bono Matriz, usuario '.$usuario->display_name;
                            $this->guardarComision($iduser, $idcomision, $pagar, $user->email, $user->nivel, $concepto, 'referido');
                        }
                    }
                }
            }

            // Para las inversiones mayores 2000
            if ($invertido >= 2000) {
                if ($usuario->nivel <= 10) {
                    if ($invertidoReferidos >= 1000) {
                        if ($this->checkComision($idcomision, $iduser)) {
                            $pagar = $this->getMontoAPagar($usuario->nivel);
                            $concepto = 'Bono Matriz, usuario '.$usuario->display_name;
                            $this->guardarComision($iduser, $idcomision, $pagar, $user->email, $user->nivel, $concepto, 'referido');
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
        $arreMonto = [
            1 => 8, 2 => 6, 3 => 5, 4 => 4, 5 => 3,
            6 => 3, 7 => 2, 8 => 2, 9 => 1, 10 => 1
        ];

        return $arreMonto[$nivel];
    }


    public function getRentabilidad($iduser)
    {
        $user = User::find($iduser);
        $paquete = json_decode($user->paquete);
        if (!empty($user->paquete)) {
            $fechatmpSemana = Carbon::now();
            $semanayear = $fechatmpSemana->weekOfYear;
            $year = $fechatmpSemana->year;
            $semanas = ($paquete->duracion / 4.345);
            $porcentaje = ($paquete->rentabilidad / 100);
            $rentabilidad = (($paquete->precio * $porcentaje) / $semanas);
            $user->rentabilidad = ($user->rentabilidad + $rentabilidad);
            $data = [
                'iduser' => $iduser,
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


    /**
     * Permite guardar la informacion de las rentabilidad
     *
     * @param array $data
     * @return void
     */
    public function sabeWalletRentabilidad(array $data)
    {
        WalletlogRentabilidad::create($data);
    }
}

