<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;
    
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
class IngredienteController extends Controller
{
    //
     public function index()
    {
        $ingredientes = Ingrediente::all();
        if ($ingredientes->isEmpty()) {
	        return response()->json(['error' => 'No hay Ingredientes '], 404);
        }
        return response()->json($ingredientes, 404);

    }
    //
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:ingredientes',
            'categoria' => ['required', 'string', Rule::in(['basico', 'premium'])]
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
            
        
        try {
            $ingrediente = Ingrediente::create($request->all());
            return response()->json($ingrediente, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el ingrediente'], 500);
        }
        
    }
    //
    public function update(Request $request, $id)
    {
        $ingrediente = Ingrediente::find($id);
         if (!$ingrediente) {
            $data = [
                'message' => 'Ingrediente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nombre' => ['required' ,Rule::unique('ingredientes','nombre')->ignore($ingrediente)],
            'categoria' => ['required', 'string', Rule::in(['basico', 'premium'])]
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $ingrediente->update($request->all());
        return response()->json($ingrediente, 200);

    }
    //
     public function destroy($id)
    {
       $ingrediente = Ingrediente::find($id);
         if (!$ingrediente) {
            $data = [
                'message' => 'Ingrediente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        if ($ingrediente->pizzas->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar el ingrediente. Está asociado a una o más pizzas.'], 400);
        }

        $ingrediente->delete();

        return response()->json(['message' => 'Ingrediente eliminado correctamente'], 200);
    }
}
