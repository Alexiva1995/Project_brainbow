<?php

namespace App\Http\Controllers;

use App\OrdenInversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IndexController;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use CoinPayment;

class InversionController extends Controller
{
    /**
     * Permite realizar el pago de la inversion realizada
     *
     * @param Request $request
     * @return void
     */
    public function pago(Request $request)
    {
        $validate = $request->validate([
            'inversion' => ['required'],
            'name' => ['required']
        ]);
        if ($validate) {
            $inversion = (double) $request->inversion;
            $total = ($inversion + 0);
            $transacion = [
                'amountTotal' => $total,
                'note' => 'Inversion de '.number_format($request->inversion, 2, ',', '.').' USD',
                'idorden' => $this->saveOrden($inversion, $request->idproducto),
                'tipo' => 'inversion',
                'buyer_email' => Auth::user()->user_email,
                'redirect_url' => route('tienda-index')
            ];
            $transacion['items'][] = [
                'itemDescription' => 'Inversion de '.number_format($request->inversion, 2, ',', '.').' USD',
                'itemPrice' => $inversion, // USD
                'itemQty' => (INT) 1,
                'itemSubtotalAmount' => $inversion // USD
            ];
            $transacion['items'][] = [
                'itemDescription' => 'Paquete de Inversion '.$request->name,
                'itemPrice' => 0, // USD
                'itemQty' => (INT) 1,
                'itemSubtotalAmount' => 0 // USD
            ];

            $ruta = CoinPayment::generatelink($transacion);
            return redirect($ruta);
        }
    }

    /**
     * Permite guardar la orden de compra de la inversion
     *
     * @param string $inversion - monto invertido
     * @param string $idpaquete - id del paqueted de inversion
     * @return integer
     */
    public function saveOrden($inversion, $idpaquete): int
    {
        $data = [
            'invertido' => (DOUBLE) $inversion,
            'concepto' => ($idpaquete != 0) ? 'Inversion de '.number_format($inversion, 2, ',', '.'). ' USD' : 'Paquete BlackBox',
            'iduser' => Auth::user()->ID,
            'idtrasancion' => '',
            'status' => 0,
            'paquete_inversion' => (int)$idpaquete
        ];

        $orden = OrdenInversion::create($data);

        return $orden->id;
    }

    /**
     * Lleva a la vista de inversiones del admin
     *
     * @return void
     */
    public function indexAdminInversion()
    {
        view()->share('title', 'Inversiones');
        
        $inversiones = OrdenInversion::all();
        $funciones = new IndexController();
        
        foreach ($inversiones as $inversion) {
            if ($inversion->paquete_inversion != 0) {
                $plan = $funciones->getProductDetails($inversion->paquete_inversion);
                $user = User::find($inversion->iduser);
                if (!empty($user)) {
                    $inversion->correo = $user->user_email;
                    $inversion->usuario = $user->display_name;
                }else{
                    $inversion->correo = 'Usuario Eliminado o no disponible';
                    $inversion->usuario = 'Usuario Eliminado o no disponible';
                }
                $inversion->plan = 'Plan no definido';
                if (!empty($plan)) {
                    $inversion->plan = $plan->post_title;
                }
            }
        }

        return view('admin.indexAdminInversiones', compact('inversiones'));
    }

    /**
     * Permite Verificar las compras procesadas
     *
     * @return void
     */
    public function verificarCompras()
    {
        try {
            $ordenes = OrdenInversion::where([
                ['status', '=', '1'],
                ['idtrasancion', '!=', ''],
                ['fecha_inicio', '=', null]
            ])->get();
            foreach ($ordenes as $orden) {
                $transacion = DB::table('coinpayment_transactions')->where('txn_id', '=', $orden->idtrasancion)->first();
                $funcione = new IndexController();
                if ($orden->paquete_inversion != 0) {
                    $paquete = $funcione->getProductDetails($orden->paquete_inversion);
                    $fecha_inicio = new Carbon($transacion->created_at);
                    $fecha_fin = new Carbon($transacion->created_at);
                    DB::table('orden_inversiones')->where('idtrasancion', '=', $orden->idtrasancion)->update([
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin->addMonth($paquete->duration)
                    ]);
                }else{
                    DB::table('orden_inversiones')->where('idtrasancion', '=', $orden->idtrasancion)->update([
                        'status' => 1
                    ]);
                }
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Permite procesar el pago del BlackBox
     *
     * @param Request $request
     * @return void
     */
    public function pagoBlackBox(Request $request)
    {
        $validate = $request->validate([
            'price' => ['required'],
        ]);
        if ($validate) {
            $price = (double) $request->price;
            $total = ($price + 0);
            $transacion = [
                'amountTotal' => $total,
                'note' => 'Paquete BlackBox',
                'idorden' => $this->saveOrden($price, 0),
                'tipo' => 'blackbox',
                'buyer_email' => Auth::user()->user_email,
                'redirect_url' => route('tienda-index')
            ];
            $transacion['items'][] = [
                'itemDescription' => 'Paquete BlackBox',
                'itemPrice' => $price, // USD
                'itemQty' => (INT) 1,
                'itemSubtotalAmount' => $price // USD
            ];

            $ruta = CoinPayment::generatelink($transacion);
            return redirect($ruta);
        }
    }

    public function indexBlackbox()
    {
        view()->share('title', 'Compras Blackbox');
        
        $inversiones = OrdenInversion::where('paquete_inversion', 0)->get();
        $funciones = new IndexController();
        
        foreach ($inversiones as $inversion) {
            $user = User::find($inversion->iduser);
            if (!empty($user)) {
                $inversion->correo = $user->user_email;
                $inversion->usuario = $user->display_name;
            }else{
                $inversion->correo = 'Usuario Eliminado o no disponible';
                $inversion->usuario = 'Usuario Eliminado o no disponible';
            }
            $inversion->plan = 'Paquetes Blackbox';
        }

        return view('admin.indexAdminInversiones', compact('inversiones'));
    }
}
