<?php


namespace Icc\Dao;


abstract class AbstractDao implements Dao
{

    public abstract function get(int $id): object;
    public abstract function save(object $object): int;
    public abstract function delete(int $id): void;
    public abstract function update(object $object): bool;

    public abstract function getAll(): array;
    public abstract function where(array $fields, array $values, array $operators): array;

    protected function buildAndClauses(array $fields, array $values, array $operators): string {
        $stringAndClausesBuilder = '';
        $arrayLength = count($operators);
        $operator = '';
        for ($i = 0; $i < count($fields); $i++) {
            if ($arrayLength == 1) {
                $operator = $operators[0];
            } else {
                $operator = $operators[$i];
            }
            $stringAndClausesBuilder .= "{$fields[$i]}$operator{$values[$i]}";
            if ($i != count($fields) - 1) {
                $stringAndClausesBuilder .= " AND ";
            }
        }

        return $stringAndClausesBuilder;
    }
}