<?php

require_once "Class/db.php";
require_once "Class/ParseCSV.php";

try{

    $dataBase = db::getDB();

    if(!$dataBase->existTableUser()){

        $dataBase->createTableUser();

        $dataBase->insertTableUser(ParseCSV::parse('database.csv'));

    }

    $id = rand(1, $dataBase->countUserTable());

    $isBlocked = $dataBase->fetchRowTableUser($id)['isBlocked'];

    $dataBase->updateBan($id, $isBlocked ? 0 : 1);

    $user = $dataBase->fetchRowTableUser($id);

    echo implode(',', $user);

}catch (ExeptionFileNotFound $e){
    echo 'File not found...';
}catch (ExceptionDataBase $e){
    echo 'Error db: ' + $e->getMessage();
}
