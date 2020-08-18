<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Liquidacion;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ComisionesController;
use App\OrdenInversion;
use App\WalletlogRentabilidad;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WalletController;


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
            DB::raw('sum(total) as total'), 'user_id'
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
            return redirect()->route('liquidacion')->with('msj', 'Liquidaciones Procesadas');
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
                return redirect()->route('liquidacion')->with('msj', 'Liquidacion Procesadas');
            }elseif($request->action == 'rechazar'){
                $this->rechazarComisiones($request->listcomisiones, $request->iduser);
                return redirect()->route('liquidacion')->with('msj', 'Comisiones Rechazadas');
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
        $feed = ($totalLiquidation * 0.05);
        $totalPagar = ($totalLiquidation - $feed);
        $data = [
            'iduser' => $iduser,
            'total' => $totalPagar,
            'wallet_used' => $wallet->paypal,
            'process_date' => Carbon::now(),
            'status' => 0,
            'type_liquidation' => 'Comisiones',
            'monto_bruto' => $totalLiquidation,
            'feed' => $feed
        ];
        $idLiquidacion = $this->saveLiquidation($data);

        $concepto = 'Liquidacion generada por un monto de '.$totalLiquidation;
        
        $user = User::find($iduser);
        $user->wallet_amount = ($user->wallet_amount - $totalLiquidation);
        $user->save();
        $dataWallet = [
            'iduser' => $iduser,
            'usuario' => $user->display_name,
            'descripcion' => $concepto,
            'descuento' => $feed,
            'debito' => 0,
            'credito' => $totalPagar,
            'balance' => $user->wallet_amount,
            'tipotransacion' => 3,
            'status' => 0,
            'correo' => $user->user_email,
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
        $funciones = new WalletController;
        $funciones->saveWallet($data);
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

    /**
     * Permite llevar a las liquidaciones Realizadas
     *
     * @return void
     */
    public function liquidacionesRealizada()
    {
        // TITLE
        view()->share('title', 'Liquidaciones Realizadas');
        $liquidaciones = Liquidacion::where('status', '=', 1)->get();

        foreach ($liquidaciones as $liquidacion) {
            $user = User::find($liquidacion->iduser)->only('display_name', 'user_email');
            $liquidacion->usuario = 'Usuario No Disponible';
            $liquidacion->email = 'Correo no disponible';
            if (!empty($user)) {
                $liquidacion->usuario = $user['display_name'];
                $liquidacion->email = $user['user_email'];
            }
        }

        return view('liquidation.liquidacionRealizadas', compact('liquidaciones'));
    }

    /**
     * Lleva a la vista las liquidaciones de inversion
     *
     * @return void
     */
    public function liquidacionesInversion()
    {
        // TITLE
        view()->share('title', 'Liquidaciones de Inversiones');

        $inversiones = $this->getInversionesUser(false);

		return view('liquidation.indexInversiones', compact('inversiones')); 
    }

     /**
     * Permite obtener las inversiones realizadas por los usuarios
     *
     * @return array
     */
    public function getInversionesUser($dashboard) : array
    {
        $funciones = new IndexController();
        $fechaActual = Carbon::now();
        $arrayInversiones = [];
        if ($dashboard) {
            $inversiones = OrdenInversion::where([
                ['paquete_inversion', '!=', ''],
                ['status', '=', 1]
            ])->orderBy('id', 'desc')->get()->take(10);
        } else {
            $inversiones = OrdenInversion::where([
                ['paquete_inversion', '!=', ''],
                ['status', '=', 1]
            ])->get();
        }
        foreach ($inversiones as $inversion) {
            $paquete = $funciones->getProductDetails($inversion->paquete_inversion);
            if ($paquete != null) {
                $rentabilidad = WalletlogRentabilidad::where([
                    ['iduser', '=', $inversion->iduser],
                    ['idinversion', '=', $inversion->id],
                ])->get()->sum('debito');
                $user = User::find($inversion->iduser)->only('display_name', 'user_email');
                $fecha_vencimiento = new Carbon($inversion->fecha_fin);
                $estado = ($fecha_vencimiento > $fechaActual) ? 'Activa' : 'Vencidad';
                $arrayInversiones [] = [
                    'id' => $inversion->id,
                    'img' => asset('products/'.$paquete->post_excerpt),
                    'usuario' => $user['display_name'],
                    'iduser' => $inversion->iduser,
                    'inversion' => $inversion->invertido,
                    'plan' => $paquete->post_title,
                    'rentabilidad' => $rentabilidad,
                    'fecha_venci' => $fecha_vencimiento,
                    'penalizacion' => $paquete->penalizacion,
                    'estado' => $estado
                ];
            }
        }
        return $arrayInversiones;
    }

    /**
	 * Permite procesar el proceso de la liquidacion de la inversiones
	 *
	 * @param Request $request
	 * @return void
	 */
	public function liquidarInversiones(Request $request)
	{

		$user = User::find($request->iduser);
		$admin = User::find(1);
		$inversion = OrdenInversion::find($request->idinversion);
		$concepto = 'Liquidacion de '.$request->retirar.' de la inversion: '.$request->idinversion;
		$credito = $request->retirar;
		if ($request->porc_penalizacion != 0) {
			$user->rentabilidad = ($user->rentabilidad - $request->retirar);
            $admin->rentabilidad = ($user->rentabilidad + $request->mont_penalizacion);
            $inversion->invertido = ($inversion->invertido - $request->retirar);
            $inversion->save();
		}else{
            $user->rentabilidad = ($user->rentabilidad - $request->retirar);
        }
        
		$user->save();
		$admin->save();
	
        // $concepto = 'Liquidacion generada por un monto de '.$credito;

		$data = [
			'iduser' => $inversion->iduser,
			'idinversion' => $inversion->id,
			'concepto' => $concepto,
			'debito' => 0,
			'credito' => $credito,
			'balance' => $user->rentabilidad,
			'semana' => '',
			'year' => '',
			'fecha_retiro' => Carbon::now(),
			'descuento' => $request->mont_penalizacion,
		];

		$comisiones = new ComisionesController();
        $idWalletRentabilidad = $comisiones->sabeWalletRentabilidad($data);
        
        $wallet = DB::table('user_campo')->where('ID', '=', $request->iduser)->select('paypal')->first();
        $dataLiquidation = [
            'iduser' => $request->iduser,
            'total' => $request->total,
            'wallet_used' => $wallet->paypal,
            'process_date' => Carbon::now(),
            'status' => 0,
            'type_liquidation' => 'Inversion',
            'idinversion' => $idWalletRentabilidad,
            'monto_bruto' => $request->retirar,
            'feed' => $request->mont_penalizacion
		];
		$this->saveLiquidation($dataLiquidation);

		return redirect()->back()->with('msj', 'Liquidacion Procesada con exito');
    }
    
    /**
     * Permite procesar las liquidaciones ya una vez en estado de pendiente
     *
     * @param Request $request
     * @return void
     */
    public function updateLiquidation(Request $request)
    {
        if ($request->action == 'reversar') {
            $validate = $request->validate([
                'comentario' => 'required'
            ]);
        }else{
            $validate = true;
        }

        if ($validate) {
            $accion = '';
            if ($request->action == 'reversar') {
                $accion = 'Se reverso con exito la liquidacion '.$request->liquidacion;
                $this->reversarLiquidaciones($request->iduser, $request->liquidacion, $request->comentario);
            }else{
                $accion = 'Se aprobo con exito la liquidacion '.$request->liquidacion;
                $this->aprobarLiquidacion($request);
            }
            return redirect()->back()->with('msj', $accion);
        }
    }

    /**
     * Permite aprobar las liquidaciones
     *
     * @param object $data
     * @return void
     */
    public function aprobarLiquidacion(object $data)
    {
        try {
            $liquidacion = Liquidacion::find($data->liquidacion);
            $liquidacion->comment = $data->comentario;
            $liquidacion->hash = $data->hash;
            $liquidacion->status = 1;
            $liquidacion->save();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Permite reversar todas las liquidaciones procesadas
     *
     * @param integer $iduser
     * @param integer $idliquidacion
     * @param string $comentario
     * @return void
     */
    public function reversarLiquidaciones(int $iduser, int $idliquidacion, string $comentario)
    {
        try {
            $liquidacion = Liquidacion::find($idliquidacion);
            $user = User::find($iduser);
            if ($liquidacion->type_liquidation == 'Inversion') {
                $admin = User::find(1);
                $walletRentabilidad = WalletlogRentabilidad::find($liquidacion->idinversion);
                $inversion = OrdenInversion::find($walletRentabilidad->idinversion);
                $concepto = 'Reverso de la  liquidacion de '.$walletRentabilidad->credito.' de la inversion: '.$walletRentabilidad->idinversion;
                $debito = $walletRentabilidad->credito;
                if ($walletRentabilidad->descuento != 0) {
                    $user->rentabilidad = ($user->rentabilidad + $walletRentabilidad->credito);
                    $admin->rentabilidad = ($user->rentabilidad - $walletRentabilidad->descuento);
                    $inversion->invertido = ($inversion->invertido + $walletRentabilidad->credito);
                    $inversion->save();
                }else{
                    $user->rentabilidad = ($user->rentabilidad + $walletRentabilidad->credito);
                }
                
                $user->save();
                $admin->save();
                
                $data = [
                    'iduser' => $iduser,
                    'idinversion' => $walletRentabilidad->idinversion,
                    'concepto' => $concepto,
                    'debito' => $debito,
                    'credito' => 0,
                    'balance' => $user->rentabilidad,
                    'semana' => '',
                    'year' => '',
                    'fecha_retiro' => Carbon::now(),
                    'descuento' => 0,
                ];
        
                $comisiones = new ComisionesController();
                $comisiones->sabeWalletRentabilidad($data);
            }elseif($liquidacion->type_liquidation == 'Comisiones'){

                $concepto = 'Reverso de la liquidacion con un monto de '.$liquidacion->monto_bruto;
                $user->wallet_amount = ($user->wallet_amount + $liquidacion->monto_bruto);
                $dataWallet = [
                    'iduser' => $iduser,
                    'usuario' => $user->display_name,
                    'descripcion' => $concepto,
                    'descuento' => 0,
                    'debito' => $liquidacion->monto_bruto,
                    'credito' => 0,
                    'balance' => $user->wallet_amount,
                    'tipotransacion' => 3,
                    'status' => 0
                ];
                $user->save();
                $this->saveWallet($dataWallet);
                Commission::where('id_liquidacion', '=', $liquidacion->id)->update(['status' => 0, 'id_liquidacion' => '']);
            }
            $liquidacion->comment_reverse = $comentario;
            $liquidacion->status = 2;
            $liquidacion->save();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

}
