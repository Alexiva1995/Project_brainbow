<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\Settings;
use App\OrdenInversion;


use App\Http\Controllers\IndexController;
use App\Http\Controllers\RangoController;
use App\Http\Controllers\LiquidationController;

class AdminController extends Controller

{

	function __construct()

	{

        // TITLE

		view()->share('title', 'Balance General');

	}



    public function index()
    {

        $funcionesIndex = new IndexController();
        $liquidacionindex = new LiquidationController();
        $rango = new RangoController();
        $rango->ValidarRango(Auth::user()->ID);
        $inversiones = $funcionesIndex->getInversionesUserDashboard(Auth::user()->ID);
        $data = [
            'inversiones' => $inversiones,
            'rangoinfo' => $rango->chechPuntoDashboard(Auth::user()->ID)
        ];
        if (Auth::user()->rol_id == 0) {
            $data = [
                'InversionesActivas' => $funcionesIndex->getInversionesActivaAdmin(),
                'totalInvertido' => $funcionesIndex->getTotalInvertidoAdmin(),
                'totalEntrada' => $funcionesIndex->getEntradaMesAdmin(),
                'divisiones' => $funcionesIndex->getDivisionPaquete(),
                'listadoOrdenes' => $funcionesIndex->getInversionesAdminDashboard(),
                'totalusers' => $funcionesIndex->getUserRegistrado(),
            ];
        }
        
        view()->share('title', 'Balance General');
        return view('dashboard.index', compact('data'));
    }


    /**
     * Lleva a la vista de los usuarios directos
     *
     * @return void
     */
    public function direct_records(){

        // TITLE
        view()->share('title', 'Usuarios Directos');
        // DO MENU
        view()->share('do', collect(['name' => 'network', 'text' => 'Red de Usuarios']));
        $rango = new RangoController();
        $referidosDirectos = User::where('referred_id', '=', Auth::user()->ID)
                                ->orderBy('created_at', 'DESC')
                                ->get();
        foreach ($referidosDirectos as $referido) {
            $referido->inversion = $rango->getTotalInvertion($referido->ID);
        }
        return view('dashboard.directRecords')->with(compact('referidosDirectos'));
    }

    /**
     * Permite saber los direcctos de un usuario en especifico
     *
     * @param Request $request
     * @return void
     */
    public function directoAdmin(Request $request)
    {
        $base = User::find($request->id);
        if (empty($base)) {
            return redirect()->route('directrecords')->with('msj2', 'El ID '.$request->id.', no se encuentra registrado');
        }
        // TITLE
        view()->share('title', 'Usuarios Directos del usuario: '.$base->display_name.' -  ID : '.$request->id);
        $rango = new RangoController();
        $referidosDirectos = User::where('referred_id', '=', $request->id)
                                ->orderBy('created_at', 'DESC')
                                ->get();
        foreach ($referidosDirectos as $referido) {
            $referido->inversion = $rango->getTotalInvertion($referido->ID);
        }
        return view('dashboard.directRecords')->with(compact('referidosDirectos'));
    }

    
    /**
     * Permite filtrar a los usuarios directos por fecha
     *
     * @return void
     */
    public function buscardirectos(){
        // TITLE
        view()->share('title', 'Usuarios Directos');
        // DO MENU
        view()->share('do', collect(['name' => 'network', 'text' => 'Red de Usuarios']));
        $rango = new RangoController();
        $primero = new Carbon($_POST["fecha1"]);
        $segundo = new Carbon($_POST["fecha2"]);
        $referidosDirectos =User::whereDate("created_at",">=",$primero)
            ->whereDate("created_at","<=",$segundo)
            ->where('referred_id', '=', Auth::user()->ID)
            ->orderBy('created_at', 'DESC')
            ->get();
        foreach ($referidosDirectos as $referido) {
            $referido->inversion = $rango->getTotalInvertion($referido->ID);
        }
        return view('dashboard.directRecords')->with(compact('referidosDirectos'));

    }

    

    public function buscarnetwork(Request $request){

        // TITLE
        view()->share('title', 'Usuarios en Red');
        view()->share('do', collect(['name' => 'network', 'text' => 'Red de Usuarios']));
        $funcionesIndex = new IndexController();
        $rango = new RangoController();
        $allReferido = [];
        $fecha1 = new Carbon($request->fecha1);
        $fecha2 = new Carbon($request->fecha2);
        $allReferidotmp = $funcionesIndex->getChidrens2(Auth::user()->ID, [], 1, 'position_id', 0);
        foreach ($allReferidotmp as $referido) {
            $fechaIngreso = new Carbon($referido->created_at);
            if ($fechaIngreso >= $fecha1 && $fechaIngreso <= $fecha2) {
                $referido->inversion = $rango->getTotalInvertion($referido->ID);
                $allReferido [] = $referido;
            }
        }

        return view('dashboard.networkRecords')->with(compact('allReferido'));

    }

    public function buscarnetworknivel(Request $request)
    {
                // TITLE
                $funcionesIndex = new IndexController();
                $rango = new RangoController();
                view()->share('title', 'Usuarios en Red');

                // DO MENU
        
                view()->share('do', collect(['name' => 'network', 'text' => 'Red de Usuarios']));
                
                $allReferidotmp = $funcionesIndex->getChidrens2(Auth::user()->ID, [], 1, 'position_id', 0);
                $allReferido = [];
                foreach ($allReferidotmp as $user ) {
                    $user->inversion = $rango->getTotalInvertion($user->ID);
                    if ($request->nivel > 0) {
                        if ($user['nivel'] == $request->nivel) {
                            $allReferido [] = $user;
                        }
                    } else {
                            $allReferido [] = $user;
                    }
                    
                }
                return view('dashboard.networkRecords')->with(compact('allReferido'));
    }


    /**
     * LLeva a la vista de todos mis usuarios en red
     *
     * @return void
     */
    public function network_records(){

        // TITLE
        view()->share('title', 'Usuarios en Red');
        $funcionesIndex = new IndexController();
        $rango = new RangoController();
        // DO MENU
        view()->share('do', collect(['name' => 'network', 'text' => 'Red de Usuarios']));
        $allReferido = $funcionesIndex->getChidrens2(Auth::user()->ID, [], 1, 'position_id', 0);
        foreach ($allReferido as $referido) {
            $referido->inversion = $rango->getTotalInvertion($referido->ID);
        }
        return view('dashboard.networkRecords')->with(compact('allReferido'));
    }

    /**
     * Permite Ver los usuario en red de un usuario determinado
     *
     * @param Request $request
     * @return void
     */
    public function networkAdmin(Request $request)
    {
        // TITLE
        $base = User::find($request->id);
        if (empty($base)) {
            return redirect()->route('networkrecords')->with('msj2', 'El ID '.$request->id.', no se encuentra registrado');
        }
        // TITLE
        view()->share('title', 'Red de usuarios del usuario: '.$base->display_name.' -  ID : '.$request->id);
        $funcionesIndex = new IndexController();
        $rango = new RangoController();
        $allReferidotmp = $funcionesIndex->getChidrens2($request->id, [], 1, 'position_id', 0);
        $allReferido = [];
        foreach ($allReferidotmp as $referido) {
            if ($referido->nivel <= 10) {
                $referido->inversion = $rango->getTotalInvertion($referido->ID);
                $allReferido [] = $referido;
            }
        }

        return view('dashboard.networkRecords')->with(compact('allReferido'));
    }

    /**

     * Permite Borrar a todos los usuarios del sistema menos al admin

     *

     * @return void

     */

    public function deleteTodos()

        {

            $usuario = User::All();



		foreach ($usuario as $usuari) {

			if ($usuari->ID != 1) {

            $user = User::find($usuari->ID);

            DB::table('user_campo')->where('ID', $usuari->ID)->delete();

            $user->delete();  

            }

		}

            return redirect('mioficina/admin/userrecords')->with('msj', 'Todos los usuarios han sidos borrados menos el Administrador');

        }

    
        /**
         * lleva a la vista de las compras personales del usuario
         *
         * @return void
         */
    public function personal_orders(){
          // TITLE
          view()->share('title', 'Ordenes Personales');
        $ordenes = OrdenInversion::where('iduser', '=', Auth::user()->ID)->get();

        return view('dashboard.personalOrders')->with(compact('ordenes'));
    }

    /**
     * Permite filtrar por fechas las compras
     *
     * @return void
     */
     public function buscarpersonalorder(){

          // TITLE

          view()->share('title', 'Ordenes Personales');

        $settings = Settings::first();

        $primero = new Carbon($_POST['fecha1']);

        $segundo = new Carbon($_POST['fecha2']);

        $ordenes = OrdenInversion::where('iduser', '=', Auth::user()->ID)
                                ->whereDate('created_at', '>=', $primero)
                                ->whereDate('created_at', '<=', $segundo)->get();

        return view('dashboard.personalOrders')->with(compact('ordenes'));
    }

    /**

     * Genera todas las ordenes de red de usuarios

     * 

     * @access public

     * @return view - vista de transacciones

     */

    public function network_orders(){

        view()->share('title', 'Ordenes de Red');
        $funcionesIndex = new IndexController();
        $TodosUsuarios = $funcionesIndex->getChidrens2(Auth::user()->ID, [], 1, 'position_id', 0);
        $compras = array();
        if (!empty($TodosUsuarios)) {
            foreach($TodosUsuarios as $user){
                $ordenes = OrdenInversion::where('iduser', '=', $user->ID)->get();
                foreach ($ordenes as $orden){
                    $orden->usuario = $user->display_name;
                    $compras [] = $orden;
                }
            }
        }
        return view('dashboard.networkOrders')->with(compact('compras'));
    }

    

    

     public function buscarnetworkorder(){
          // TITLE
          view()->share('title', 'Ordenes de Red');
          $funcionesIndex = new IndexController();

          $TodosUsuarios = $funcionesIndex->getChidrens2(Auth::user()->ID, [], 1, 'position_id', 0);
         $settings = Settings::first();
        $compras = array();

        $fecha = [
            'primero' => new Carbon($_POST['fecha1']),
            'segundo' => new Carbon($_POST['fecha2'])
        ];
        if (!empty($TodosUsuarios)) {
            foreach($TodosUsuarios as $user){
                $ordenes = OrdenInversion::where('iduser', '=', $user->ID)
                                            ->whereDate('created_at', '>=', $fecha['primero'])
                                            ->whereDate('created_at', '<=', $fecha['segundo'])->get();
                foreach ($ordenes as $orden){
                    $orden->usuario = $user->display_name;
                    $compras [] = $orden;
                }
            }
        }
        return view('dashboard.networkOrders')->with(compact('compras'));

    }

    

    public function buscar(Request $request){

          // TITLE

          view()->share('title', 'Buscar Usuario');



      

     return view('admin.buscar');

    }

    

     public function vista(Request $request){

          // TITLE

          view()->share('title', 'Buscar Usuario');



       $user=User::search($request->get('user_email'))->orderBy('id','ASC')->paginate(1);
        return view('admin.vista')->with('user',$user);

    }


    // /**
    //  * Permite eliminar las ordenes del postmetas
    //  *
    //  * @return void
    //  */
    // public function eliminarOrdenPostmetas()
    // {
    //     $sql = "SELECT * FROM `wp_postmeta` WHERE meta_value like 'wc_order%' ";
    //     $postmetas = DB::select($sql);
    //     foreach ($postmetas as $post) {
    //         if ($post->post_id < 5505) {
    //             DB::statement("DELETE FROM `wp_postmeta` WHERE post_id =".$post->post_id);
    //         }
            
    //     }
    // }

}

