<?php
namespace Controllers;
require_once "DB.php";
require_once "Request.php";
require_once "Models/User.php";
require_once "JsonResponse.php";
use Models\User;
use Request;
use JsonResponse;

class UserController{
    
    static public function index(Request $request){
        $users = User::all();
        $headers = ["Accept" => "application/json"];
        response($users,200,$headers)->send();
    }

    static public function show(Request $request){
        //echo 'sadasd';
        $user = User::find($request->id);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($user,200,$headers)->send();
    }

    static public function update(Request $request){
        //echo 'sadasd';
        $user = User::update($request);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($user,200,$headers)->send();
    }

    static public function delete(Request $request){

        $deleted = User::delete($request);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($deleted,200,$headers)->send();
    }
    
    static public function create(Request $request){
        $user = User::create($request);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($user,201,$headers)->send();  
    }

    static public function login(Request $request){
        $headers = ["Accept" => "application/json"];

        if(User::login($request->email, $request->password)){
            $user = User::getLoggedInUser();
            response($user,201,$headers)->send();
        }

    }

    static public function logout(){
        $headers = ["Accept" => "application/json"];
        if(User::logout()){
            response('Deslogado', 200, $headers)->send();
        }
        else{
            response('Nenhuma sessão ativa', 200, $headers)->send();
        }

    }
  
} 