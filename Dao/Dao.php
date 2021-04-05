<?php
namespace Icc\Dao;

interface Dao
{
    function get(int $id): object;
    function save(object $object): int;
    function delete(int $id): void;
    function update(object $object): bool;

    function getAll(): array;
    function where(array $fields, array $values, array $operators): array;

}