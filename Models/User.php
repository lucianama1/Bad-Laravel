<?php
namespace Models;

require_once "DB.php";

class User{
    public int $id;
    public string $name;
    public string $email;
    private string $password;
    private string $hashedPassword;
    
    public function __construct(){
        unset($this->password); 
    }

    static public function all():array{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select `id`,`name`,`email`,`password` from user");
        $stm->execute();
        $users = $stm->fetchAll(\PDO::FETCH_ASSOC);
        $stm->closeCursor();
        return $users;
    }

    static public function find($id):User{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from user where id=?");
        $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\User');
        $stm->execute([$id]);
        $user = $stm->fetch();
        $stm->closeCursor();
        return $user;
    }

    static public function update(\Request $request):User{
        $pdo = \DB::connect();
        $query = "UPDATE user SET " ;
        $arr = [];
        $parameters = [];
        if($request->name){
            array_push($parameters,'`name`=? ');
            array_push($arr,$request->name);
        }
        if($request->email){
            array_push($parameters,'`email`=? ');
            array_push($arr,$request->email);
        }
        $query .= implode(',',$parameters) . 'where `id`=?';
        array_push($arr,$request->id);
        $stm = $pdo->prepare($query);
        $stm->execute($arr);
        $stm->closeCursor();
        return self::find($request->id);
    }

    static public function delete(\Request $request):int{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Delete from user where id=?");
        $stm->execute([$request->id]);
        $stm->closeCursor();
        return $stm->rowCount();

    }

    static public function create(\Request $request):User{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("INSERT INTO user (`name`,`email`,`password`) VALUES (?,?,?)");
        $hash = password_hash($request->password, PASSWORD_DEFAULT);
        $stm->execute([$request->name,$request->email,$hash]);
        $stm->closeCursor();
        return self::find($pdo->lastInsertId());
    }

    public function login($email, $givenPassword):bool{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from user where email= :email");
        $result = $stm->execute(array(":email"=> $email));
        $dados = $stm->fetch(\PDO::FETCH_ASSOC);
        $stm->closeCursor();
        //Var_dump(session_status() == PHP_SESSION_ACTIVE);

        if (password_verify($givenPassword, $dados["password"])){
            session_start();
            if ($_SESSION["id"]===NULL){
                $_SESSION["id"] = $dados["id"];
                $_SESSION["email"] = $dados["email"];
                echo("Usuário logado:\n");
                return TRUE;
            }
            else{ //se não tem sessao ativa, inicia ela e define as variáveis
                echo("Sessão já existe");
                return FALSE;
            }
        }
        else{
            echo("Senha ou email inválido");
            return FALSE;
        }
    }

    static public function logout(){
        
        session_start();//Precisei iniciar a sessão para poder acessar as variáveis dela

        if ($_SESSION["id"]===NULL){    //se não tiver nenhuma variável, a sessão está vazia então ninguém fez login
            return FALSE;
        }
        else{
            session_destroy();
            unset($_SESSION["id"]);
            unset($_SESSION["email"]);
            //Var_dump(session_status() == PHP_SESSION_ACTIVE);
            return TRUE;
        } 
    }

    public function getLoggedInUser(){
        if(session_status() == PHP_SESSION_ACTIVE) {
            $id = $_SESSION["id"];
            return self::find($id);
        }
        if(empty($_SESSION["id"]))  //Provavelmente não vai acontecer por que acabou de passar pela função de login, mas por precaução estou checando se a variável está vazia
		{
            session_destroy();
            echo("Nenhuma sessão ativa");
		}
    }
}