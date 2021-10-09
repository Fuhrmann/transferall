<?php

namespace App\Services\Transaction;

class TransactionStatus
{
    public const PENDING = 1;
    public const APPROVED = 2;
    public const REPROVED = 3;

    /**
     * Return all available transaction statuses.
     *
     * @return string[]
     */
    public static function all() : array
    {
        return [
            self::PENDING  => 'Pending',
            self::APPROVED => 'Approved',
            self::REPROVED => 'Reproved',
        ];
    }

    /**
     * Return the string representation of a specific status.
     *
     * @param  int  $status
     *
     * @return string
     */
    public static function getStatus(int $status) : string
    {
        return self::all()[$status] ?? 'Unknow';
    }
}
