<?php

namespace App\Http\Controllers;
use App\Models\Pizza;
use App\Models\Ingrediente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PizzaController extends Controller
{
    //
    public function index(){
        if (auth()->guest()) {
            // Usuarios no autenticados solo ven pizzas activas
            $pizzas = Pizza::where('estado', 1)->withCount('ingredientes')->get();
        }else { 
           $user = auth()->user();
           if ($user->hasRole('superuser') || $user->hasRole('staff') ) {
                $pizzas = Pizza::all(); // Todas las pizzas
           } else {
                $pizzas = Pizza::where('estado', 1)->get(); // Solo pizzas activas
           }
       }
       return response()->json($pizzas, 200);


    }
    //
    public function store(Request $request){
         $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:pizzas',
            'precio' => ['required', 'int'],
            'estado' => ['required', 'int', Rule::in([0, 1])],
            'ingredientes' => 'required|array',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        //verifico si existen todos los ingredientes
        $ingredientesExistentes = Ingrediente::whereIn('id', $request->ingredientes)->pluck('id');
        $ingredientesFaltantes = array_diff($request->ingredientes, $ingredientesExistentes->toArray());
        if (!empty($ingredientesFaltantes)) {
            return response()->json(['message' => '  siguientes ingredientes no existen: ' . implode(', ', $ingredientesFaltantes)], 404);
        }
         $pizza = Pizza::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'estado' => $request->estado,
        ]);
        
       
        $pizza->ingredientes()->sync($request->ingredientes);
        return response()->json($pizza, 201);
    }
    //
    public function show($id)
    {
        try { 
            $pizza = Pizza::with('ingredientes')->findOrFail($id);
            return response()->json($pizza, 200);
        }catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Pizza no encontrada'], 404);
        }
    }
    //
    public function update(Request $request, $id)
    {
         $user = auth()->user();
         if ($user->hasRole('superuser') || $user->hasRole('staff') ) {
             $pizza = Pizza::find($id);
             if (!$pizza) {
                $data = [
                    'message' => 'Pizza no encontrada',
                    'status' => 404
                ];
                return response()->json($data, 404);
            }
             $validator = Validator::make($request->all(), [
                'nombre' => ['required' ,Rule::unique('pizzas','nombre')->ignore($pizza)],
                'precio' => ['required', 'int'],
                'estado' => ['required', 'int'],
            ]);
            if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validacion de datos',
                    'error' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }
            $pizza->update($request->all());
            return response()->json($pizza, 200);

        }else{
            return response()->json(['no posee los permisos para realizar esto'], 401);
        }
    }
    //
     public function actualizarIngredientes(Request $request, int $id )
    {
         $pizza = Pizza::find($id);
            if (!$pizza) {
            $data = [
                'message' => 'Pizza no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
         }
          $validator = Validator::make($request->all(), [
                'ingredientes' => 'required|array'
            ]);
        if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validacion de datos',
                    'error' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }
        //$request->validate(['ingredientes' => 'required|array']);
        //verificamos si existe
        $ingredientesExistentes = Ingrediente::whereIn('id', $request->ingredientes)->pluck('id');
        $ingredientesFaltantes = array_diff($request->ingredientes, $ingredientesExistentes->toArray());
        if (!empty($ingredientesFaltantes)) {
            return response()->json(['message' => '  siguientes ingredientes no existen: ' . implode(', ', $ingredientesFaltantes)], 404);
        }
        try {
            $pizza->ingredientes()->sync($request->ingredientes);
            return response()->json(['message' => 'Ingredientes actualizados correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

}
