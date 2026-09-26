<?php
require_once __DIR__ . '/../bdconnect.php';
class Aluno{
    public string $nome;
    public string $datanas;
    public string $sexo;
    public int $matricula;

    public function __construct(string $nome, string $datanas, string $sexo, int $matricula = -1){
        $this->nome = $nome;
        $this->datanas = $datanas;
        $this->sexo = $sexo;
        $this->matricula = $matricula;
    }
    public function sqls(){
        if($this->matricula != -1) return ["message" => "Already saved!", "success" => false];
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("INSERT INTO aluno (nome, datanas, sexo) VALUES(:nome,:datanas, :sexo)");
        try{
            $sql->execute([':nome'=>$this->nome, ':datanas' => $this->datanas, ':sexo' => $this->sexo]);
            $this->matricula = (int) $PDO->lastInsertId();
            return ["message" => "success", "success" => true];
        }catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public static function getAll(){
        $sql = "SELECT * FROM aluno";
        $ret = [];
        $PDO = getPDO();
        if(is_null($PDO)) return ["message" => "Couldn't get a connection!", "success" => false];
        try{$query = $PDO->query($sql);}catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
        if($query->rowCount() > 0) while($line = $query->fetch(PDO::FETCH_ASSOC)) array_push($ret, new Aluno($line["nome"], $line["datanas"], $line["sexo"], $line["matricula"]));
        if(!$ret) return ["message" => "Couldn't find any", "success" => false];
        return ["message" => "success", "success" => true, "data" => $ret];
    }
    public static function findByMatricula($id){
        $PDO = getPDO();
        if($PDO === null) return null;
        $sql = $PDO->prepare("SELECT * FROM aluno where matricula=:id");
        $sql->execute(['id' => $id]);
        $dat = $sql->fetch(PDO::FETCH_ASSOC);
        if(!$dat) return null;
        return new Aluno($dat["nome"], $dat["datanas"], $dat["sexo"], $dat["matricula"]);
    }
    public function alter($nome, $datanas, $sexo){
        if($this->matricula == -1) return ["message" => "Hasn't been saved!", "success" => false];
        $this->nome = $nome;
        $this->datanas = $datanas;
        $this->sexo = $sexo;
        $PDO = getPDO();
        if($PDO === null) return null;
        $sql = $PDO->prepare("UPDATE aluno set nome=:nome, datanas=:datanas, sexo=:sexo where matricula=:matricula");
        try{
            $sql->execute([':nome' => $nome, ':datanas' => $datanas, ':sexo' => $sexo, ':matricula' => $this->matricula]);
            return ["message" => "success", "success" => true];
        } catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public function sqld(){
        if($this->matricula == -1) return ["message" => "Hasn't been saved!", "success" => false];
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("DELETE FROM aluno where matricula=:matricula");
        try{
            $sql->execute([':matricula'=> $this->matricula]);
            return ["message" => "success", "success" => true];
        }catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public static function getCount(){
        $sql = "SELECT Count(*) FROM aluno";
        $PDO = getPDO();
        if($PDO === null) return 0;
        return (int) $PDO->query($sql)->fetchColumn();
    }
}
?>