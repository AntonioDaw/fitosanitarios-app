<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    /**
     * Devuelve una respuesta JSON paginada con datos crudos o resource.
     *
     * @param mixed $data Una colección simple o una ResourceCollection
     * @param LengthAwarePaginator $paginator
     * @return JsonResponse
     */
    protected function paginatedResponse($data, LengthAwarePaginator $paginator)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'next_page' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
                'prev_page' => $paginator->currentPage() > 1 ? $paginator->currentPage() - 1 : null,
            ]
        ]);
    }
}


