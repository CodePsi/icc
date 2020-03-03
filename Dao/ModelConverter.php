<?php


namespace Icc\Dao;


use mysqli_result;

interface ModelConverter
{
    function convertMysqlResultToModel(mysqli_result $mysqliResult): object;
}