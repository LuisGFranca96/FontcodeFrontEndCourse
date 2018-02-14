<?php

if($_GET){
    //var_dump($_GET);exit;
    //echo "<name>{$_GET['name']}</name>";
      //header("HTTP/1.0 404 Not Found");exit;

      $data = listAll();
      echo json_encode($data);exit;
}
if($_POST){

    $name =  $_POST['name'];
    $email =  $_POST['email']; 
    $tel=  $_POST['tel'];
    if($name==""){
        echo json_encode(["status"=>false,"msg"=>"Fill in name"]);exit;
    }
    if($email==""){
        echo json_encode(["status"=>false,"msg"=>"Fill in email"]);exit;
    }
    if($tel==""){
        echo json_encode(["status"=>false,"msg"=>"Fill in telephone"]);exit;
    }


    $id = save($_POST);

    if($id){
        $data = find($id);
        echo json_encode(["status"=>true,"msg"=>"Sucess! : Id:{$id}","contacts"=>$data]);exit;
    }else{
        echo json_encode(["status"=>false,"msg"=>"Fail! DB"]);exit;
    }
  
}

function conn(){
    $conn = new \PDO("mysql:host=localhost;dbname=mydb","root","root");
    return $conn;
}

function save($data){
    $db = conn();
    $query = "Insert into `contacts`(`name`,`email`,`tel`) VALUES (:name,:email,:tel)";
    $stmt= $db->prepare($query);
    $stmt->bindValue(':name',$data['name']);
    $stmt->bindValue(':email',$data['email']);
    $stmt->bindValue(':tel',$data['tel']);
    $stmt->execute();
    return $db->lastInsertID();
}

function listAll(){
    $db = conn();
    $query = "Select*from `contacts` order by id DESC";
    $stmt= $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function find($id){
    $db = conn();
    $query = "Select*from `contacts` where id=:id";
    $stmt= $db->prepare($query);
    $stmt->bindValue(':id',$id);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}