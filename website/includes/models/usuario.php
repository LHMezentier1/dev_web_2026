<?php
require_once __DIR__ . '/../bdconnect.php';
class Usuario{

private string $email;
public string $password_hash;
private string $nome;
private int $id;

public function __construct(string $nome, string $password, string $email, int $id=-1){
    $this->email = $email;
    $this->nome = $nome;
    $this->password_hash = $password;
    $this->id = $id;
}

public function sqls(){
    if($this->id != -1) return ["message" => "already registered!", "success" => false];
    $PDO = getPDO();
    if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
    $sql = $PDO->prepare("INSERT INTO usuario (nome, email, password_hash) VALUES(:nome, :email, :passh)");
    try{
        $sql->execute([':nome'=>$this->nome, ':email' => $this->email, ':passh' => $this->password_hash]);
        $this->id = (int) $PDO->lastInsertId();
        return ["message" => "success", "success" => true];
    }catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
}
public static function findByEmail($email){
        $PDO = getPDO();
        if($PDO === null) return null;
        $sql = $PDO->prepare("SELECT * FROM usuario where email=:email");
        $sql->execute(['email' => $email]);
        $dat = $sql->fetch(PDO::FETCH_ASSOC);
        if(!$dat) return null;
        return new Usuario($dat["nome"], $dat["password_hash"], $dat["email"], $dat["id"]);
}
}
?>