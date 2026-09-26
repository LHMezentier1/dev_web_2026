<?php
require_once __DIR__ . '/../bdconnect.php';
require_once 'livro.php';
require_once 'aluno.php';
class Emprestimo{
    public Livro $livro;
    public Aluno $aluno;
    public string $dataemp;
    public string $datadev;
    public int $id;

    public function __construct(Livro $livro, Aluno $aluno, string $dataemp, string $datadev, int $id = -1){
        $this->livro = $livro;
        $this->aluno = $aluno;
        $this->dataemp = $dataemp;
        $this->datadev = $datadev;
        $this->id = $id;
    }
    
    public function sqls(){
        if($this->id != -1) return ["message" => "Already registered!", "success" => false];
        $aid = $this->aluno->matricula;
        $lid = $this->livro->id;
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("INSERT into emprestimo(ida, idl, datemp, datdev) VALUES (:aid, :lid, :dataemp, :datadev)");
        try{
            $sql->execute([':aid' => $aid, ':lid' => $lid, ':dataemp' => $this->dataemp, ':datadev' => $this->datadev]);
            $this->id = (int) $PDO->lastInsertId();
            return ["message" => "Inserted into the database!", "success" => true];
        } catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public static function getAll(){
        $sql = "Select * from emprestimo";
        $ret = [];
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        try{$query = $PDO->query($sql);}catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
        if($query->rowCount() > 0) while($line = $query->fetch(PDO::FETCH_ASSOC)){
            $livro = Livro::findById($line['idl']);
            $aluno = Aluno::findByMatricula($line['ida']);
            if($aluno === null || $livro === null) continue;
            array_push($ret, new Emprestimo($livro, $aluno, $line['datemp'], $line['datdev'], $line['id']));
        }
        if(!$ret) return ["message" => "Couldn't find any", "success" => false];
        return ["message" => "success", "success" => true, "data" => $ret];
    }
    public static function getById($id){
        $PDO = getPDO();
        if($PDO === null) return null;
        $sql = $PDO->prepare("SELECT * FROM emprestimo where id=:id");
        $sql->execute(['id'=>$id]);
        $dat = $sql->fetch(PDO::FETCH_ASSOC);
        if(!$dat) return null;
        $livro = Livro::findById($dat['idl']);
        $aluno = Aluno::findByMatricula($dat['ida']);
        if($aluno === null || $livro === null) return null;
        return new Emprestimo($livro, $aluno, $dat["datemp"], $dat["datdev"], $dat['id']);
    }
    public function alter($aluno, $livro, string $dataemp, string $datadev){
        $this->aluno = $aluno;
        $this->livro = $livro;
        $this->dataemp = $dataemp;
        $this->datadev = $datadev;
        $ida = $aluno->matricula;
        $idl = $livro->id;
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("UPDATE emprestimo set ida=:ida, idl=:idl, datemp = :datemp, datdev = :datdev where id=:id");
        try{
            $sql->execute([':ida' => $ida, ':idl' => $idl, ':datemp' => $dataemp, ':datdev' => $datadev, ':id' => $this->id]);
            return ["message" => "success", "success" => true];
        } catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public function sqld(){
        if($this->id == -1) return ["message" => "Hasn't been saved!", "success" => false];
        $PDO = getPDO();
        if($PDO === null) return ["message" => "Couldn't get a connection!", "success" => false];
        $sql = $PDO->prepare("DELETE FROM emprestimo where id=:id");
        try{
            $sql->execute([':id'=> $this->id]);
            return ["message" => "success", "success" => true];
        }catch(PDOException $e){return ["message" => $e->getMessage(), "success" => false];}
    }
    public static function getCount(){
        $sql = "SELECT Count(*) FROM emprestimo";
        $PDO = getPDO();
        if($PDO === null) return 0;
        return (int) $PDO->query($sql)->fetchColumn();
    }
    public static function getCountLate(){
        $PDO = getPDO();
        if($PDO === null) return 0;
        try{
        $sql = $PDO->prepare("SELECT Count(*) FROM emprestimo where datdev <=:dat");
        $sql->execute([':dat' => date('Y-m-d')]);
        return (int) $sql->fetchColumn();
        }catch(PDOException $e){return 0;}
    }
}