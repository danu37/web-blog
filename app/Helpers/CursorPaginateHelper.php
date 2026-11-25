<?php

namespace App\Helpers;

use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;

class CursorPaginateHelper
{
    /**
     * Get to paginate array.
     */
    public static function getPaginate(CursorPaginator $paginator): array
    {
        return [
            'path' => self::path($paginator),
            'per_page' => self::perPage($paginator),
            'next_cursor' => self::nextCursor($paginator),
            'next_page_url' => self::nextPageUrl($paginator),
            'prev_cursor' => self::previousCursor($paginator),
            'prev_page_url' => self::previousPageUrl($paginator),
        ];
    }

    /**
     * Get the current path.
     */
    public static function path(CursorPaginator $paginator): string
    {
        return $paginator->path();
    }

    /**
     * Get the current per page.
     */
    public static function perPage(CursorPaginator $paginator): int
    {
        return $paginator->perPage();
    }

    /**
     * Get the next cursor.
     */
    public static function nextCursor(CursorPaginator $paginator): ?string
    {
        return $paginator->hasMorePages() ? $paginator->nextCursor()->encode() : null;
    }

    /**
     * Get the next page URL.
     */
    public static function nextPageUrl(CursorPaginator $paginator): ?string
    {
        return $paginator->hasMorePages() ? $paginator->nextPageUrl() : null;
    }

    /**
     * Get the previous cursor.
     */
    public static function previousCursor(CursorPaginator $paginator): ?Cursor
    {
        return $paginator->hasMorePages() ? $paginator->previousCursor() : null;
    }

    /**
     * Get the previous page URL.
     */
    public static function previousPageUrl(CursorPaginator $paginator): ?string
    {
        return $paginator->hasMorePages() ? $paginator->previousPageUrl() : null;
    }
}
