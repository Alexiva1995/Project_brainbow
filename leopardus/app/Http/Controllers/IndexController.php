<?php

namespace App\Http\Controllers;

use App\OrdenInversion;
use App\Settings;
use App\User;
use App\WalletlogRentabilidad;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RangoController;

class IndexController extends Controller
{

    /**
     * Permite saber el estado del binario del usuario
     *
     * @param integer $id - id del usuario a revisar
     * @return boolean
     */
    public function statusBinary($id)
    {
        $result = false;
        $derecha = User::where([
            ['referred_id', '=', $id ],
            ['status', '=', 1],
            ['ladomatrix', '=', 'D']
        ])->get()->count('ID');
        $izquierda = User::where([
            ['referred_id', '=', $id ],
            ['status', '=', 1],
            ['ladomatrix', '=', 'I']
        ])->get()->count('ID');

        if ($derecha >= 1 && $izquierda >= 1) {
            $result = true;
        }
        return $result;
    }

    
    /**
     * Permite obtener la informacion para el arbol o matris
     *
     * @param integer $id - id del usuario a obtener sus hijos
     * @param string $type - tipo de estructura a general
     * @return void
     */
    public function getDataEstructura($id, $type)
    {
        $genealogyType = [
            'tree' => 'referred_id',
            'matriz' => 'position_id',
        ];
        
        $childres = $this->getData($id, 1, $genealogyType[$type]);
        $trees = $this->getChildren($childres, 2, $genealogyType[$type]);
        return $trees;
    }


    /**
     * Permite obtener a todos mis hijos y los hijos de mis hijos
     *
     * @param array $users - arreglo de usuarios
     * @param integer $nivel - el nivel en el que esta parado
     * @param string $typeTree - el tipo de arbol a usar
     * @return void
     */
    public function getChildren($users, $nivel, $typeTree)
    {
        if (!empty($users)) {
            foreach ($users as $user) {
                $user->children = $this->getData($user->ID, $nivel, $typeTree);
                $this->getChildren($user->children, ($nivel+1), $typeTree);
            }
            return $users;
        }else{
            return $users;
        }
    }

    /**
     * Se trare la informacion de los hijos 
     *
     * @param integer $id - id a buscar hijos
     * @param integer $nivel - nivel en que los hijos se encuentra
     * @param string $typeTree - tipo de arbol a usar
     * @return void
     */
    private function getData($id, $nivel, $typeTree) : object
    {
        $settings = Settings::first();
        $rango = new RangoController();
        $resul = User::where($typeTree, '=', $id)->get();
        foreach ($resul as $user) {
            $patrocinado = User::find($user->referred_id);
            $avatarTree = asset('avatar/'.$user->avatar);
            $inversion = OrdenInversion::where([
                ['iduser', '=', $user->ID],
                ['paquete_inversion', '!=', ''],
                ['status', '=', 1]
            ])->orderBy('id', 'decs')->first();
            
            if (!empty($inversion)) {
                $paquete = DB::table($settings->prefijo_wp.'posts as wp')->where('ID', $inversion->paquete_inversion)->first();
                if (!empty($paquete)) {
                    $avatarTree = asset('products/'.$paquete->post_excerpt);
                }
            }
            $userTemp = DB::table('user_campo')->where('ID', '=', $user->ID)->first();
            $user->fullname = $userTemp->firstname.' '.$user->lastname;
            $user->avatar = asset('avatar/'.$user->avatar);
            $user->avatarTree = $avatarTree;
            $user->avatar = asset('avatar/'.$user->avatar);
            $user->nivel = $nivel;
            $user->invertido = $rango->getTotalInvertion($user->ID);
            $user->ladomatriz = $user->ladomatrix;
            $user->patrocinador = $patrocinado->display_name;
        }
        return $resul;
    }

    /**
     * Permite tener la informacion de los hijos como un listado
     *
     * @param integer $parent - id del padre
     * @param array $array_tree_user - arreglo con todos los usuarios
     * @param integer $nivel - nivel
     * @param string $typeTree - tipo de usuario
     * @param boolean $allNetwork - si solo se va a traer la informacion de los directos o todos mis hijos
     * @return 
     */
    public function getChidrens2($parent, $array_tree_user, $nivel, $typeTree, $allNetwork) : array
    {   
        if (!is_array($array_tree_user))
        $array_tree_user = [];
    
        $data = $this->getData($parent, $nivel, $typeTree);
        if (count($data) > 0) {
            if ($allNetwork == 1) {
                foreach($data as $user){
                    if ($user->nivel == 1) {
                        $array_tree_user [] = $user;
                    }
                }
            }else{
                foreach($data as $user){
                    $array_tree_user [] = $user;
                    $array_tree_user = $this->getChidrens2($user->ID, $array_tree_user, ($nivel+1), $typeTree, $allNetwork);
                }
            }
        }
        return $array_tree_user;
    }

    /**
     * Se trare la informacion de los hijos 
     *
     * @param integer $id - id a buscar hijos
     * @param integer $nivel - nivel en que los hijos se encuentra
     * @param string $typeTree - tipo de arbol a usar
     * @return void
     */
    private function getDataSponsor($id, $nivel, $typeTree) : object
    {
        $resul = User::where($typeTree, '=', $id)->get();
        foreach ($resul as $user) {
            $user->avatar = asset('avatar/'.$user->avatar);
            $user->nivel = $nivel;
            $user->ladomatriz = $user->ladomatrix;
        }
        return $resul;
    }

    /**
     * Permite obtener a todos mis patrocinadores
     *
     * @param integer $child - id del hijo
     * @param array $array_tree_user - arreglo de patrocinadores
     * @param integer $nivel - nivel a buscar
     * @param string $typeTree - llave a buscar
     * @param string $keySponsor - llave para buscar el sponsor, position o referido
     * @return array
     */
    public function getSponsor($child, $array_tree_user, $nivel, $typeTree, $keySponsor): array
    {
        if (!is_array($array_tree_user))
        $array_tree_user = [];
    
        $data = $this->getDataSponsor($child, $nivel, $typeTree);
        if (count($data) > 0 && $nivel > 0) {
            foreach($data as $user){
                $array_tree_user [] = $user;
                $array_tree_user = $this->getSponsor($user->$keySponsor, $array_tree_user, ($nivel-1), $typeTree, $keySponsor);
            }
        }
        return $array_tree_user;
    }


    /**
     * Permite ordenar el arreglo primario con las claves de los arreglos segundarios
     * 
     * @access public
     * @param array $arreglo - el arreglo que se va a ordenar, string $clave - con que se hara la comparecion de ordenamiento,
     * stridn $forma - es si es cadena o numero
     * @return array
     */
    public function ordenarArreglosMultiDimensiones($arreglo, $clave, $forma)
    {
        usort($arreglo, $this->build_sorter($clave, $forma));
        return $arreglo;
    }

    /**
     * compara las clave del arreglo
     * 
     * @access private
     * @param string $clave - llave o clave del arreglo segundario a comparar
     * @return string
     */
    private function build_sorter($clave, $forma) {
        return function ($a, $b) use ($clave, $forma) {
            if ($forma == 'cadena') {
                return strnatcmp($a[$clave], $b[$clave]);
            } else {
                return $b[$clave] - $a[$clave] ;
            }
            
        };
    }

    /**
     * Permite obtener la informacion completa de las compras
     *
     * @param integer $iduser
     * @return void
     */
    public function getInforShopping($iduser) : array
    {
        $arreCompras = [];
        $compras = $this->getShopping($iduser);
        if (!empty($compras)) {
            foreach ($compras as $compra) {
                $detallesCompra = $this->getShoppingDetails($compra->post_id);
                if (!empty($detallesCompra)) {
                    $arregProducto = $this->getProductos($compra->post_id);
                    if (!empty($arregProducto)) {
                        $productos = [];
                        foreach ($arregProducto as $product) {
                            $idProducto = $this->getIdProductos($product->order_item_id);
                            $detalleProduct = $this->getProductDetails($idProducto);
                            $productos [] = [
                                'idproducto' => $idProducto,
                                'precio' => $this->getTotalProductos($product->order_item_id),
                                'nombre' => $detalleProduct->post_title,
                                'img' => asset('products/'.$detalleProduct->post_excerpt),
                                'duracion' => $detalleProduct->duration,
                                'rentabilidad' => $detalleProduct->rentabilidad,
                                'penalizacion' => $detalleProduct->penalizacion,
                            ];
                        }
                        $arreCompras [] = [
                            'idusuario' => $iduser,
                            'idcompra' => $compra->post_id,
                            'fecha' => $detallesCompra->post_date,
                            'productos' => $productos,
                            'total' => $this->getShoppingTotal($compra->post_id)
                        ];
                    }
                }
            }
        }
        return $arreCompras;
    }

    /**
     * Permite obtener las compras que hizo un usuario
     *
     * @param integer $user_id
     * @return void
     */
    public function getShopping($user_id) : object
    {
        $settings = Settings::first();
        $comprasID = DB::table($settings->prefijo_wp.'postmeta')
                    ->select('post_id')
                    ->where('meta_key', '=', '_customer_user')
                    ->where('meta_value', '=', $user_id)
                    ->get();
        return $comprasID;
    }

    /**
     * Permite obtener el id del usuario que hizo la compra
     *
     * @param integer $idpost
     * @return void
     */
    public function getIdUser($idpost) : object
    {
        $settings = Settings::first();
        $comprasID = DB::table($settings->prefijo_wp.'postmeta')
                    ->select('meta_value')
                    ->where('meta_key', '=', '_customer_user')
                    ->where('post_id', '=', $idpost)
                    ->first();
        return $comprasID->meta_value;
    }

    /**
     * Permite obtener informacion del estado y fecha de la compra
     *
     * @param integer $shop_id
     * @return void
     */
    public function getShoppingDetails($shop_id) : object
    {
        $settings = Settings::first();
		$datosCompra = DB::table($settings->prefijo_wp.'posts')
                        ->select('post_date')
                        ->where('ID', '=', $shop_id)
                        ->where('post_status', '=', 'wc-completed')
                        ->first();
        return $datosCompra;
    }

    /**
     * Permite obtener todos los productos de la compras
     *
     * @param integer $shop_id
     * @return void
     */
	public function getProductos($shop_id): array
	{
        $settings = Settings::first();
		$totalProductos = DB::table($settings->prefijo_wp.'woocommerce_order_items')
													->select('order_item_id')
													->where('order_id', '=', $shop_id)
													->get();
		return $totalProductos;
	}
    
    /**
     * Permite obtener el id de los productos
     *
     * @param integer $id_item
     * @return void
     */
	public function getIdProductos($id_item): int
	{
        $settings = Settings::first();
        $valor = 0;
		$IdProducto = DB::table($settings->prefijo_wp.'woocommerce_order_itemmeta')
													->select('meta_value')
													->where('order_item_id', '=', $id_item)
													->where('meta_key', '=', '_product_id')
													->first();
        if (!empty($IdProducto)) {
            $valor = $IdProducto->meta_value;
        }
		return $valor;
    }
    
    /**
     * Permite obtener informacion de los productos
     *
     * @param integer $shop_id
     * @return void
     */
    public function getProductDetails($shop_id)
    {
        $settings = Settings::first();
		$datosCompra = DB::table($settings->prefijo_wp.'posts')
                        ->select('post_excerpt', 'post_title', 'post_password as duration', 'post_content_filtered as rentabilidad', 'post_parent as penalizacion')
                        ->where('ID', '=', $shop_id)
                        ->first();
        return $datosCompra;
    }
    
    /**
     * Permite obtener el precio de los productos comprado
     *
     * @param integer $id_item
     * @return void
     */
	public function getTotalProductos($id_item) : float
	{
        $valor = 0;
        $settings = Settings::first();
		$IdProducto = DB::table($settings->prefijo_wp.'woocommerce_order_itemmeta')
													->select('meta_value')
													->where('order_item_id', '=', $id_item)
													->where('meta_key', '=', '_line_total')
													->first();
        if (!empty($IdProducto)) {
            $restante = $IdProducto->meta_value;
            $valor = $restante;
        }
		return $valor;
    }
    
	/**
     * Permite obtener el monto total de la compra realizada
     *
     * @param integer $shop_id
     * @return void
     */
    public function getShoppingTotal($shop_id): float
    {
        $settings = Settings::first();
		$totalCompra = DB::table($settings->prefijo_wp.'postmeta')
				        ->select('meta_value')
				        ->where('post_id', '=', $shop_id)
				        ->where('meta_key', '=', '_order_total')
				        ->first();
		return $totalCompra->meta_value;
    }
    

    /**
     * Permite obtener las inversiones realizadas por el usuario
     *
     * @param integer $iduser
     * @return array
     */
    public function getInversionesUserDashboard($iduser) : array
    {
        $fechaActual = Carbon::now();
        $arrayInversiones = [];
        $inversiones = OrdenInversion::where([
            ['iduser', '=', $iduser],
            ['paquete_inversion', '!=', ''],
            ['status', '=', 1]
        ])->get();
        foreach ($inversiones as $inversion) {
            $paquete = $this->getProductDetails($inversion->paquete_inversion);
            if ($paquete != null) {
                $rentabilidad = WalletlogRentabilidad::where([
                    ['iduser', '=', $iduser],
                    ['idinversion', '=', $inversion],
                ])->get()->sum('debito');
                $fecha_vencimiento = new Carbon($inversion->fecha_fin);
                $estado = ($fecha_vencimiento > $fechaActual) ? 'Activa' : 'Vencidad';
                $arrayInversiones [] = [
                    'img' => asset('products/'.$paquete->post_excerpt),
                    'inversion' => $inversion->invertido,
                    'rentabilidad' => $rentabilidad,
                    'fecha_venci' => $fecha_vencimiento,
                    'estado' => $estado
                ];
            }
        }

        return $arrayInversiones;
    }


}
