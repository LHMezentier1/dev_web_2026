<?php
require_once __DIR__ . '/../bdconnect.php';
Class Livro{
    public string $nome;
    public string $editora;
    public string $edicao;
    public string $autor;
    public int $estoque;
    public int $id;
    public function __construct(string $nome, string $editora, string $edicao, string $autor, int $estoque, int $id = -1) {
        $this->nome = $nome;
        $this->editora = $editora;
        $this->edicao = $edicao;
        $this->autor = $autor;
        $this->estoque = $estoque;
        $this->id = $id;
    }
    public function sqls(){
        if($this->id != -1) return ["message" => "Already saved!", "success" => false];
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("INSERT INTO livro (nome, editora, edicao, autor, estoque) VALUES (:nome,:editora, :edicao, :autor, :estoque)");
        try{
            $sql->execute([':nome'=> $this->nome,':editora' => $this->editora, ':edicao'=>$this->edicao, ':autor' => $this->autor, ':estoque' => $this->estoque]);
            $this->id = (int) $PDO->lastInsertId();
            return ["message" => "success", "success" => true];
        }catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public static function getAll(){
        $sql = "SELECT * FROM livro";
        $ret = [];
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        try{$query = $PDO->query($sql);}catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
        if($query->rowCount() > 0) while($line = $query->fetch(PDO::FETCH_ASSOC)) array_push($ret, new Livro($line["nome"], $line["editora"], $line["edicao"], $line["autor"], (int)$line["estoque"], (int) $line["id"]));
        if(!$ret) return ["message" => "Couldn't find any", "success" => false];
        return ["message" => "success", "success" => true, "data" => $ret];
    }
    public static function findById($id){
        $sql = "SELECT * FROM livro WHERE id = :id";
        $PDO = getPDO();
        if($PDO === null) return null;
        try {
            $stmt = $PDO->prepare($sql);
            $stmt->execute([':id' => $id]);
            $dat = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$dat) return null;
            return new Livro($dat["nome"], $dat["editora"], $dat["edicao"], $dat["autor"], $dat["estoque"], $dat["id"]);
        } catch(PDOException $e) {return null;}
    }
    public function alter($nome, $editora, $edicao, $autor, $estoque){
        $this->nome = $nome;
        $this->editora = $editora;
        $this->edicao = $edicao;
        $this->autor = $autor;
        $this->estoque = $estoque;
        $PDO = getPDO();
        if(is_null($PDO)) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("UPDATE livro set nome = :nome, editora= :editora, edicao= :edicao, autor= :autor, estoque= :estoque where id=:id");
        try{
            $sql->execute([':nome' => $nome,':editora' => $editora, ':edicao' => $edicao, ':autor' => $autor, ':estoque' => $estoque, ':id' => $this->id]);
            return ["message" => "success", "success" => true];
        } catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public function sqld(){
        if($this->id == -1) return ["message" => "Hasn't been saved!", "success" => false];
        $PDO = getPDO();
        if(is_null($PDO)) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("DELETE FROM livro where id=:id");
        try{
            $sql->execute([':id'=> $this->id]);
            return ["message" => "success", "success" => true];
        }catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public static function getCount(){
        $sql = "SELECT Count(*) FROM livro";
        $PDO = getPDO();
        if($PDO === null) return 0;
        return (int) $PDO->query($sql)->fetchColumn();
    }
}
?>