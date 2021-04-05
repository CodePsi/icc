<?php


namespace Icc\Controller;


use Icc\Database\DBConnector;
use Icc\Response\Response;

class SQLExecutorController
{
    public static function executeNativeSqlCommand($data) {
        $queryResult = DBConnector::getInstance()->execute_query($data);
        $returnResult = [];
//        while ($row = $queryResult -> fetch_row()) {
//            array_push($returnResult, $row);
//        }
        if (gettype($queryResult)  == 'boolean') {
            Response::json(json_encode(['status' => $queryResult]));
        } else {
            Response::json(json_encode($queryResult->fetch_all()));
        }
    }
}