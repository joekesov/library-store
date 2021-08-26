<?php

require_once ('AbstractRepository.php');

class AuthorRepository extends AbstractRepository
{
    protected static function getTableName(): string
    {
        return 'author';
    }
}