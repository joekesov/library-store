<?php

require_once ('AbstractRepository.php');

class BookRepository extends AbstractRepository
{
    protected static function getTableName(): string
    {
        return 'book';
    }
}