<?php

namespace App\Http\Controllers;

use App\OrdenInversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IndexController;
use App\User;
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
            $total = ($inversion + $request->precio);
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
                'itemPrice' => $request->precio, // USD
                'itemQty' => (INT) 1,
                'itemSubtotalAmount' => $request->precio // USD
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
            'concepto' => 'Inversion de '.number_format($inversion, 2, ',', '.'). ' USD',
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

        return view('admin.indexAdminInversiones', compact('inversiones'));
    }
}
