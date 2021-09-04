<?php

namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    use ApiResponseSystem;

    public function showOne(
        $status,
        $statusMsg,
        $data,
        $code
    ) {
        return $this->successResponce($data, $code, $status, $statusMsg,);
    }

    public function showAll(
        $status,
        $statusMsg,
        $collection,
        $code
    ) {
        if (empty($collection)) {
            return $this->successResponce($collection, $code, $status,
                $statusMsg);
        }
        if (!$collection instanceof Illuminate\Support\Collection) {
            try {
                $collection = collect($collection);
                if ($collection->isEmpty()) {
                    return $this->successResponce($collection, $code, $status,
                        $statusMsg,);
                }
            } catch (\Exception $exception) {
                return $this->errorResponce(null);
            }
        }

        $collection = $this->filterCollection($collection);
        $collection = $this->sortCollection($collection);
        $collection = $this->paginateCollection($collection);

        return $this->successResponce($collection, $code, $status, $statusMsg,);
    }

    public function showError(
        $status,
        $statusMsg,
        $data,
        $code
    ) {
        return $this->errorResponce($data, $code, $status, $statusMsg,);
    }

    public function sortCollection($collection)
    {
        $sortByValue = request()->query('sortBy');

        $orderByValue = request()->query('orderBy');
        if ($orderByValue !== 'desc' and $orderByValue !== 'asc') {
            $orderByValue = 'desc';
        }

        $collection = $collection->sortBy([
            [
                $sortByValue ? $sortByValue : 'id',
                $orderByValue ? $orderByValue : 'desc',
            ],
        ]);

        return $collection->values();
    }

    public function filterCollection($collection)
    {
        $filterColumn = request()->query('filterColumn');
        $filterValue  = request()->query('filterValue');

        if (!empty($filterColumn) or !empty($filterValue)) {
            $collection = $collection->where($filterColumn, $filterValue);

            return $collection;
        }

        return $collection;
    }

    public function paginateCollection($collection)
    {
        if (\request()->query('paginate') !== 'true') {
            return $collection;
        }
        request()->validate([
            'perPage' => ['integer', 'min:5'],
            'page'    => ['integer', 'min:1'],
        ]);
        $requestedPerPage = \request()->has('perPage');

        $perPage = $requestedPerPage ? \request()->query('perPage') : 10;

        $requestedPage = request()->query('page');
        //$currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPage = $requestedPage ? $requestedPage : 1;

        $results = $collection->slice($perPage * ($currentPage - 1),
            $perPage)->values();

        $paginatedData = [
            'current_page'   => $currentPage,
            'num_of_pages'   => ceil($collection->count() / $perPage),
            'num_of_resutls' => $collection->count(),
        ];

        $paginated = collect(['details' => $results, 'meta' => $paginatedData]);

        /* $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $currentPage, [
             'path' => LengthAwarePaginator::resolveCurrentPath()
         ]);

         $paginated->appends(\request()->query());*/

        return $paginated;
    }
}
