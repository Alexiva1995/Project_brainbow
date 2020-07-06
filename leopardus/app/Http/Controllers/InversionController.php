<?php

namespace App\Http\Controllers;

use App\OrdenInversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'inversion' => ['required']
        ]);

        if ($validate) {
            $inversion = (double) $request->inversion;
            $transacion = [
                'amountTotal' => $inversion,
                'note' => 'Inversion de '.number_format($request->inversion, 2, ',', '.').' USD',
                'idorden' => $this->saveOrden($inversion),
                'tipo' => 'inversion',
                'buyer_email' => Auth::user()->user_email,
                // 'redirect_url' => route('pagos')
            ];
            $transacion['items'][] = [
                'itemDescription' => 'Inversion de '.number_format($request->inversion, 2, ',', '.').' USD',
                'itemPrice' => $inversion, // USD
                'itemQty' => (INT) 1,
                'itemSubtotalAmount' => $inversion // USD
            ];
            $ruta = CoinPayment::generatelink($transacion);
            return redirect($ruta);
        }
    }

    /**
     * Permite guardar la orden de compra de la inversion
     *
     * @param string $inversion - monto invertido
     * @return integer
     */
    public function saveOrden($inversion): int
    {
        $data = [
            'invertido' => (DOUBLE) $inversion,
            'concepto' => 'Inversion de '.number_format($inversion, 2, ',', '.'). ' USD',
            'iduser' => Auth::user()->ID,
            'idtrasancion' => '',
            'status' => 0,
            'saldo_capital' => (DOUBLE) $inversion
        ];

        $orden = OrdenInversion::create($data);

        return $orden->id;
    }
}
