<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponser
{
    /**
     * Devuelve una respuesta JSON paginada con formato personalizado
     *
     * @param ResourceCollection $resourceCollection
     * @param LengthAwarePaginator $paginator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function paginatedResponse(ResourceCollection $resourceCollection, LengthAwarePaginator $paginator)
    {
        return response()->json([
            'status' => 'success',
            'data' => $resourceCollection,
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


