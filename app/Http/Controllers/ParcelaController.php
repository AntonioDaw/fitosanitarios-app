<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParcelaRequest;
use App\Http\Resources\ParcelaResource;
use App\Models\Parcela;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ParcelaController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'id'); // columna por defecto
        $sortDir = $request->input('sort_dir', 'asc'); // dirección por defecto

        $query = Parcela::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('numero_parcela', 'like', "%{$search}%");
            })
                ->orWhere(function ($q) use ($search) {
                    // Si el $search es un número, podrías intentar comparar el count de sectores
                    if (is_numeric($search)) {
                        $q->has('sectors', '=', (int)$search); // Busca parcelas con un número exacto de sectores
                    }
                });
        }


        // Validar sortDir para evitar valores inválidos
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $query->orderBy($sortBy, $sortDir);
        $perPage = $request->input('per_page', 10);
        $parcelasPaginadas = $query->paginate($perPage);
        $parcelasResource = ParcelaResource::collection($parcelasPaginadas);

        return $this->paginatedResponse($parcelasResource, $parcelasPaginadas);
    }

    public function show($id)
    {
        $parcela = Parcela::find($id);

        if (!$parcela) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parcela no encontrado'
            ], 404); // Not Found
        }

        return response()->json([
            'status' => 'success',
            'data' => new  ParcelaResource($parcela)
        ], 200); // OK
    }

    // Metodo para crear un nuevo Tipo
    public function store(ParcelaRequest $request)
    {
        $validated = $request->validated();

        $parcela = Parcela::create([
            'nombre' => $validated['nombre'],
            'numero_parcela' => $validated['numero_parcela'],
            'area' => $validated['area'],
        ]);

        // Crear sectores si se indicó 'n_sectores'
        if (!empty($validated['n_sectores']) && is_numeric($validated['n_sectores'])) {
            for ($i = 1; $i <= $validated['n_sectores']; $i++) {
                $parcela->sectors()->create([
                    'numero_sector' => $i,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => new ParcelaResource($parcela) // incluir sectores en la respuesta
        ], 201);
    }

    public function update(ParcelaRequest $request, $id)
    {
        // Encontramos el parcela por su ID
        $parcela = Parcela::find($id);

        // Si no se encuentra el parcela, devolvemos un error
        if (!$parcela) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parcela no encontrada'
            ], 404); // Not Found
        }

        // Validamos los datos a través de ParcelaRequest
        $validated = $request->validated();

        // Actualizamos los campos del parcela con los datos validados
        $parcela->nombre = $validated['nombre'];
        $parcela->numero_parcela = $validated['numero_parcela'];
        $parcela->area = $validated['area'];

        // Guardamos el parcela actualizado en la base de datos
        $parcela->save();

        // Devolvemos el parcela actualizado
        return response()->json([
            'status' => 'success',
            'data' => $parcela
        ], 200);
    }

    public function delete($id){
        // Buscar la parcela antes de eliminarla
        $parcela = Parcela::find($id);

        // Verificar si la parcela existe
        if (!$parcela) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parcela no encontrada.'
            ], 404); // 404 Not Found
        }

        // Eliminar la parcela
        $parcela->delete();

        // Retornar la respuesta con la parcela eliminada
        return response()->json([
            'status' => 'success',
            'data' => $parcela
        ], 200); // 200 OK
    }
}
