<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Liquidacion;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ComisionesController;

class LiquidationController extends Controller
{
    /**
     * LLeva a la vista de las liquidaciones pendientes
     *
     * @return void
     */
    public function index()
    {
        // TITLE
        view()->share('title', 'Generar Liquidaciones');
        
        $comisiones = $this->getComisionesTotalIndex([]);
        $filtro = false;
        return view('liquidation.index', compact('comisiones', 'filtro'));
    }

    /**
     * Permite el proceso de filtrado en las liquidaciones
     *
     * @param Request $request
     * @return void
     */
    public function indexFiltro(Request $request)
    {
        // TITLE
        view()->share('title', 'Generar Liquidaciones');
        $comisiones = $this->getComisionesTotalIndex($request->all());
        $filtro = true;
        return view('liquidation.index', compact('comisiones', 'filtro'));
    }

    /**
     * Permite traer las comisiones a proccesar dependiendo del o de los filtro aplicados
     *
     * @param array $filtros
     * @return array
     */
    public function getComisionesTotalIndex(array $filtros): array
    {
        $comisiones = [];
        $comisionestmp = Commission::where('status', '=', 0)->select(
            DB::raw('sum(total) as total'), 'user_id',
        )->groupBy('user_id')->get();

        foreach ($comisionestmp as $comision) {
            $user = User::find($comision->user_id)->only('display_name', 'status', 'user_email');
            $comision->usuario = 'Usuario No Disponible';
            $comision->status = 0;
            $comision->email = 'Correo no disponible';
            if (!empty($user)) {
                $comision->usuario = $user['display_name'];
                $comision->status = $user['status'];
                $comision->email = $user['user_email'];
            }
            if ($filtros == []) {
                $comisiones[] = $comision;
            }else{
                if (!empty($filtros['activo'])) {
                    if ($comision->status == 1) {
                        if (!empty($filtros['mayorque'])) {
                            if ($comision->total >= $filtros['mayorque']) {
                                $comisiones[] = $comision;
                            }
                        } else {
                            $comisiones[] = $comision;
                        }
                    }
                }else{
                    if (!empty($filtros['mayorque'])) {
                        if ($comision->total >= $filtros['mayorque']) {
                            $comisiones[] = $comision;
                        }
                    } else {
                        $comisiones[] = $comision;
                    }
                }
            }
        }
        return $comisiones;
    }

    /**
     * Permite obtener las comisiones un usuario
     *
     * @param integer $iduser
     * @param integer $status
     * @return object
     */
    public function getComisiones(int $iduser, $status): object {
        $comisiones = Commission::where([
            ['status', '=', $status],
            ['user_id', '=', $iduser]
        ])->select('id', 'date', 'referred_email', 'total', 'concepto')->get();

        foreach ($comisiones as $comision) {
            $user = User::where('user_email', '=', $comision->referred_email)->select('ID', 'display_name')->first();
            $comision->idreferido = 0;
            $comision->referido = 'Usuario no Disponible';
            if (!empty($user)) {
                $comision->idreferido = $user->ID;
                $comision->referido = $user->display_name;
                $comision->date = date('d-m-Y', strtotime($comision->date));
                $comision->total2 = number_format($comision->total, 2, ',', '.');
            }
        }

        return $comisiones;
    }

    /**
     * Permite obtener el total a pagar de las comisiones de un usuario
     *
     * @param integer $iduser
     * @param integer $status
     * @return float
     */
    public function getTotaPagar(int $iduser, $status) : float
    {
        $total = Commission::where([
            ['status', '=', $status],
            ['user_id', '=', $iduser]
        ])->get()->sum('total');

        return $total;
    }

    /**
     * Permite obtener los detalles de las comisiones
     *
     * @param integer $iduser
     * @return string
     */
    public function detalles(int $iduser): string
    {
        $user = User::find($iduser)->only('display_name');
        $data = [
            'comisiones' => $this->getComisiones($iduser, 0),
            'totalPagar' => number_format($this->getTotaPagar($iduser, 0), 2, ',', '.'),
            'usuario' => $user['display_name']
        ];

        return json_encode($data);
    }

    /**
     * Permite general la liquidaciones pendientes de los usuarios
     *
     * @param Request $request
     * @return void
     */
    public function liduidarUser(Request $request)
    {
        $validate = $request->validate([
            'listuser' => ['required']
        ]);
        if ($validate) {
            foreach ($request->listuser as $user) {
                $this->generanLiquidacion($user, []);
            }
            return redirect()->back()->with('msj', 'Liquidaciones Procesadas');
        }
    }

    /**
     * Permite Procesar las liquidaciones de forma individual para cada usuario
     *
     * @param Request $request
     * @return void
     */
    public function procesarComisiones(Request $request)
    {
        $validate = $request->validate([
            'listcomisiones' => ['required']
        ]);
        if ($validate) {
            if ($request->action == 'liquidar') {
                $this->generanLiquidacion($request->iduser, $request->listcomisiones);
                return redirect()->back()->with('msj', 'Liquidacion Procesadas');
            }elseif($request->action == 'rechazar'){
                $this->rechazarComisiones($request->listcomisiones, $request->iduser);
                return redirect()->back()->with('msj', 'Comisiones Rechazadas');
            }
        }
    }

    /**
     * Permite rechazar las comisiones
     *
     * @param array $listComisiones
     * @param integer $iduser
     * @return void
     */
    public function rechazarComisiones(array $listComisiones, int $iduser)
    {
        $totalLiquidation = 0;
        foreach ($listComisiones as $comision) {
            $totalLiquidation = ($totalLiquidation + $comision->total);
            Commission::where('id', $comision)->update(['status' => 2]);
        }

        $concepto = 'Comisiones no Restribuidas';

        $user = User::find($iduser);
        $user->wallet_amount = ($user->wallet_amount - $totalLiquidation);
        $dataWallet = [
            'iduser' => $iduser,
            'usuario' => $user->display_name,
            'descripcion' => $concepto,
            'descuento' => 0,
            'debito' => 0,
            'credito' => $totalLiquidation,
            'balance' => $user->wallet_amount,
            'tipotransacion' => 3,
            'status' => 0
        ];
        $this->saveWallet($dataWallet);
    }

    /**
     * Permite procesar las liquidaciones de los usuarios 
     *
     * @param integer $iduser
     * @param array $comisiones
     * @return void
     */
    public function generanLiquidacion($iduser, $comisionesList)
    {
        $comisiones = $this->getComisiones($iduser, 0);
        $comisionesProcesar = [];
        $totalLiquidation = 0;
        foreach ($comisiones as $comision) {
            if ($comisionesList != []) {
                if (in_array($comision->id, $comisionesList)) {
                    $comisionesProcesar [] = $comision;
                    $totalLiquidation = ($totalLiquidation + $comision->total);
                }
            }else{
                $comisionesProcesar [] = $comision;
                $totalLiquidation = ($totalLiquidation + $comision->total);
            }
        }
        
        $wallet = DB::table('user_campo')->where('ID', '=', $iduser)->select('paypal')->first();
        $data = [
            'iduser' => $iduser,
            'total' => $totalLiquidation,
            'wallet_used' => $wallet->paypal,
            'process_date' => Carbon::now(),
            'status' => 0
        ];
        $idLiquidacion = $this->saveLiquidation($data);
        $concepto = 'Liquidacion generada por un monto de '.$totalLiquidation;

        $user = User::find($iduser);
        $user->wallet_amount = ($user->wallet_amount - $totalLiquidation);
        $dataWallet = [
            'iduser' => $iduser,
            'usuario' => $user->display_name,
            'descripcion' => $concepto,
            'descuento' => 0,
            'debito' => 0,
            'credito' => $totalLiquidation,
            'balance' => $user->wallet_amount,
            'tipotransacion' => 3,
            'status' => 0
        ];
        $this->saveWallet($dataWallet);

        foreach ($comisionesProcesar as $comision) {
            Commission::where('id', $comision->id)->update(['status' => 1, 'id_liquidacion' => $idLiquidacion]);
        }
    }

    /**
     * Permite guardar la liquidacion y devolver el id correspondiente
     *
     * @param array $data
     * @return integer
     */
    public function saveLiquidation($data): int
    {
        $liquidacion = Liquidacion::create($data);

        return $liquidacion->id;
    }

    /**
     * Permite guardar en la billetera
     *
     * @param array $data
     * @return void
     */
    public function saveWallet(array $data)
    {
        $comisiones = new ComisionesController();

        $comisiones->saveWallet($data);
    }

    /**
     * Permite llevar a las liquidaciones pendientes
     *
     * @return void
     */
    public function liquidacionPendientes()
    {
        // TITLE
        view()->share('title', 'Liquidaciones Pendientes');
        $liquidaciones = Liquidacion::where('status', '=', 0)->get();

        foreach ($liquidaciones as $liquidacion) {
            $user = User::find($liquidacion->iduser)->only('display_name', 'user_email');
            $liquidacion->usuario = 'Usuario No Disponible';
            $liquidacion->email = 'Correo no disponible';
            if (!empty($user)) {
                $liquidacion->usuario = $user['display_name'];
                $liquidacion->email = $user['user_email'];
            }
        }

        return view('liquidation.liquidacionPendiente', compact('liquidaciones'));
    }
}
