<?php
namespace Models;
require_once "DB.php";

class Book{
    public int $id;
    public int $ISBN;
    public string $aid;


    static public function all():array{ //
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select book.ISBN, book.aid, authors.name, authors.surname from book left join authors on (book.aid = authors.aid);");
        $stm->execute();
        $books = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $books;
    }

    static public function find($id):Book{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select book.ISBN, book.aid, authors.name, authors.surname from book inner join authors on (book.aid = authors.aid) where id=?");
        $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\Book');
        $stm->execute([$id]);
        $book = $stm->fetch();
        $stm->closeCursor();
        return $book;
    }

    static public function update(\Request $request):Book{
        $pdo = \DB::connect();
        $query = "UPDATE book SET " ;
        $arr = [];
        $parameters = [];
        if($request->aid){
            array_push($parameters,'`aid`=? ');
            array_push($arr,$request->aid);
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
        $stm = $pdo->prepare("Delete from book where id=?");
        $stm->execute([$request->id]);
        $stm->closeCursor();
        return $stm->rowCount();
    }
    static public function create(\Request $request):Book{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("INSERT INTO book (`ISBN`,`aid`) VALUES (?,?)");
        $stm->execute([$request->ISBN,$request->aid]);
        $stm->closeCursor();
        return self::find($pdo->lastInsertId());
    }

}