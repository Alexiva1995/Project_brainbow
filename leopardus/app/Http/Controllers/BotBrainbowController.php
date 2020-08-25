<?php

namespace App\Http\Controllers;

use App\Botbrainbow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\BotImport;
use Maatwebsite\Excel\Facades\Excel;

class BotBrainbowController extends Controller
{
    /**
     * Lleva a la vista para agregar los registro del bot
     *
     * @return void
     */
    public function index()
    {
        view()->share('title', 'Bot Brainbow');
        $botbrainbow = Botbrainbow::orderBy('fecha_numerica', 'desc')->get();
        foreach ($botbrainbow as $bot) {
            $dt = Carbon::now();
            $dt->timestamp = $bot->fecha_numerica;
            $bot->fecha_bot = $dt;
        }
        return view('admin.botbrainbow', compact('botbrainbow'));
    }

    /**
     * Permite guardar los registro del bot
     *
     * @param Request $request
     * @return void
     */
    public function saveBotBrainbow(Request $request)
    {
        $validate = $request->validate([
            'fecha' => ['required'],
            'hora' => ['required'],
            'abierto' => ['required', 'numeric'],
            'alto' => ['required', 'numeric'],
            'bajo' => ['required', 'numeric'],
            'cerrado' => ['required', 'numeric'],
        ]);
        if ($validate) {
            $data = $request->all();
            $data['fecha'] = $data['fecha'].' '.$data['hora'];
            $fechaNumerica = new Carbon($data['fecha']);
            $data['fecha_numerica'] = $fechaNumerica->timestamp;
            $data['post_nega'] = $this->getSubioBajo($data['fecha_numerica'], $data['cerrado']);
            $this->updateBotBrainbow($data, true, 0);

            return redirect()->back()->with('msj', 'Registro Exitoso');
        }
    }

    public function updateBotBrainbow($data, $new, $idbotbrainbow)
    {
        if ($new) {
            Botbrainbow::create($data);
        }else{
            Botbrainbow::where('id', $idbotbrainbow)->update($data);
        }
    }

    /**
     * Permite saber si el nuevo registro es subio o bajo con respecto al ultimo registro
     *
     * @param string $fecha
     * @param float $valor
     * @return integer
     */
    public function getSubioBajo($fecha, $valor) : int
    {
        $botbrainbow = Botbrainbow::where('fecha_numerica', '<', $fecha)->first();
        $resul = 1;
        if (!empty($botbrainbow)) {
            if ($botbrainbow->cerrado > $valor) {
                $resul = 0;
            }
        }
        return $resul;
    }

    /**
     * Permite Actualizar los valores de los bot
     *
     * @param Request $request
     * @return void
     */
    public function updateBot(Request $request)
    {
        $validate = $request->validate([
            'abierto' => ['required', 'numeric'],
            'alto' => ['required', 'numeric'],
            'bajo' => ['required', 'numeric'],
            'cerrado' => ['required', 'numeric'],
        ]);
        if ($validate) {
            $botbrainbow = Botbrainbow::find($request->idbot);
            $data = [
                'abierto' => $request->abierto,
                'alto' => $request->alto,
                'bajo' => $request->bajo,
                'cerrado' => $request->cerrado,
            ];
            $data['post_nega'] = $this->getSubioBajo($botbrainbow->fecha_numerica, $data['cerrado']);
            $this->updateBotBrainbow($data, false, $request->idbot);

            return redirect()->back()->with('msj', 'Actualizar Registro Bot');
        }
    }

    /**
     * Permite obtener la grafica de brainbow
     *
     * @return void
     */
    public function getBotBrainbow()
    {
        $botBrainbow = Botbrainbow::orderBy('fecha_numerica', 'desc')->get();
		$dataGrafica = [];
		foreach ($botBrainbow as $bot) {
			$valores = [
				$bot->abierto,
				$bot->alto,
				$bot->bajo,
				$bot->cerrado
            ];
            $dt = Carbon::now();
            $dt->timestamp = $bot->fecha_numerica;
            $fecha = [
                "year" => $dt->year,
                "month" => $dt->month,
                "day" => $dt->day,
                "hour" => $dt->hour,
                "minute" => $dt->minute,
                "second" => $dt->second,
            ];
			$dataGrafica [] = [
				"fecha" => $fecha,
				"valores" => $valores
			];
        }
        return json_encode($dataGrafica);
    }

    /**
     * Permite guardar la informacion por lotes de botbrainbow
     *
     * @param Request $request
     * @return void
     */
    public function saveBotExcel(Request $request)
    {
        $validate = $request->validate([
            'lote' => ['required', 'file', 'mimes:xls,xlsx,csv']
            ]);
        try {
            if ($validate) {
                Excel::import(new BotImport, $request->file('lote'));
    
                return redirect()->back()->with('msj', 'Informacion Agregada con exito');
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
