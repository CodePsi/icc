<?php
//include_once "PDFGenerator.php";
//include_once "ControllerManager/Controller.php";
namespace Icc;

require __DIR__ . "/../vendor/autoload.php";
class WriteOffController
{


    public static function generateWriteOffAct(array $items, array $members) {
        $pdf = MPDFGenerator::getInstance();
        echo base64_encode($pdf -> writeOffAct($items, $members));
//        echo base64_encode($v -> generateRequest(0, '', '', '', '', '', '', '', 1, 'SomeTask'));
    }


}

//WriteOffController::generateWriteOffAct();
//echo "Test";
//echo 'window.location = "WriteOffController.php"';

//For correct passing data between a client and server there's a need to encode it to base64 and then pass it, because we could overflow buffer.
