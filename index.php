<?php

require_once "Class/db.php";
require_once "Class/ParseCSV.php";

try{

    $dataBase = null;

    $dataBase = db::getDB();

    if(!$dataBase->existTableUser()){

        $arrayParse = ParseCSV::parse('database.csv');

        $dataBase->createTableUser();

        $dataBase->insertTableUser($arrayParse);

    }

    $id = rand(1, $dataBase->countUserTable());

    $isBlocked = $dataBase->fetchRowTableUser($id)['isBlocked'];

    $dataBase->updateBan($id, $isBlocked ? 0 : 1);

    $user = $dataBase->fetchRowTableUser($id);

    echo implode(',', $user);

}catch (ExceptionDataBase $e){
    echo 'Error db: '.$e->getMessage();
}
catch (ExeptionFileNotFound $e){
    echo $e->getMessage();
}
