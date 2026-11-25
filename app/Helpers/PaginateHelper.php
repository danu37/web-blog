<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginateHelper
{
    /**
     * Get to paginate array.
     */
    public static function getPaginate(LengthAwarePaginator $paginator): array
    {
        return [
            'path' => self::path($paginator),
            'per_page' => self::perPage($paginator),
            'current_page' => self::currentPage($paginator),
            'total' => self::total($paginator),
            'last_page' => self::lastPage($paginator),
            'next_page_url' => self::nextPageUrl($paginator),
            'prev_page_url' => self::previousPageUrl($paginator),
            'from' => self::from($paginator),
            'to' => self::to($paginator),
        ];
    }

    /**
     * Get the current path.
     */
    public static function path(LengthAwarePaginator $paginator): string
    {
        return $paginator->path();
    }

    /**
     * Get the current per page.
     */
    public static function perPage(LengthAwarePaginator $paginator): int
    {
        return $paginator->perPage();
    }

    /**
     * Get the current page.
     */
    public static function currentPage(LengthAwarePaginator $paginator): int
    {
        return $paginator->currentPage();
    }

    /**
     * Get the total number of items.
     */
    public static function total(LengthAwarePaginator $paginator): int
    {
        return $paginator->total();
    }

    /**
     * Get the last page number.
     */
    public static function lastPage(LengthAwarePaginator $paginator): int
    {
        return $paginator->lastPage();
    }

    /**
     * Get the next page URL.
     */
    public static function nextPageUrl(LengthAwarePaginator $paginator): ?string
    {
        return $paginator->nextPageUrl();
    }

    /**
     * Get the previous page URL.
     */
    public static function previousPageUrl(LengthAwarePaginator $paginator): ?string
    {
        return $paginator->previousPageUrl();
    }

    /**
     * Get the starting item number of the current page.
     */
    public static function from(LengthAwarePaginator $paginator): ?int
    {
        return $paginator->firstItem();
    }

    /**
     * Get the ending item number of the current page.
     */
    public static function to(LengthAwarePaginator $paginator): ?int
    {
        return $paginator->lastItem();
    }
}
