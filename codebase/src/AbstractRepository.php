<?php

require_once ('DBConnector.php');

abstract class AbstractRepository
{
    abstract protected static function getTableName(): string;

    public static function find(array $arguments)
    {
        $where = '';
        $queryArguments = [];
        if (count($arguments) > 0) {
            $where = 'WHERE';
            $whereArguments = [];
            foreach ($arguments as $columnName => $columnValue) {
                $whereArguments[] = sprintf(' %s = :%s ', $columnName, $columnName);
                $queryArguments[':'. $columnName] = $columnValue;
            }

            $where .= implode (' AND ', $whereArguments);
        }

        $sql = sprintf('
            SELECT 
                   * 
            FROM 
                 %s 
            %s
        ', static::getTableName(), $where);

        $sth = DBConnector::getConnection()
            ->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute($queryArguments);

        return $sth->fetchObject();
    }

    public static function create(array $arguments)
    {
        $columnsNames = implode(', ', array_keys($arguments));
        $columnsNamesArguments = [];
        foreach ($arguments as $columnName => $columnValue) {
            $columnsNamesArguments[':'. $columnName] = $columnValue;
        }

        $sql = sprintf('
            INSERT INTO %s
                (%s)
                VALUES (%s)
        ', static::getTableName(), $columnsNames, implode(', ', array_keys($columnsNamesArguments)));

        DBConnector::getConnection()
            ->prepare($sql)
            ->execute($columnsNamesArguments);
    }

    public static function firstOrCreate(array $arguments)
    {
        $author = self::find($arguments);

        if (!empty($author)) {
            return $author;
        }

        self::create($arguments);

        return self::find($arguments);
    }


}