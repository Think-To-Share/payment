<?php

namespace ThinkToShare\Payment\Utils;

class QueryString
{
    public static function queryToArray(string $query): array
    {
        parse_str($query, $values);

        return collect($values)->map(function ($item) {
            return ($item === "null" || $item === "NA" || $item === "") ? null : $item;
        })->toArray();
    }

    public static function arrayToQuery(array $array): string
    {
        return rawurldecode(http_build_query($array));
    }
}
