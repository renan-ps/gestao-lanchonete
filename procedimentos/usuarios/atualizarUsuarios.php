<?php 

require_once "../../classes/conexao.php";
require_once "../../classes/usuarios.php";

$obj = new usuarios();

$dados=array(
	$_POST['idUsuarioU'],
	$_POST['nomeU'],
	$_POST['userU'],
	$_POST['emailU'],
	$_POST['cargoU']
);

echo $obj->atualizar($dados);

?>