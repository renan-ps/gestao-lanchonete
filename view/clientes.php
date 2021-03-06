<?php 
session_start();
if(isset($_SESSION['usuario'])){

	?>


	<!DOCTYPE html>
	<html>
	<head>
		<title>Clientes</title>
		<?php require_once "menu.php"; ?>
	</head>
	<body>
		<div class="container">
			<h1>Gestão de clientes</h1>
			<div class="row">
				<div class="col-sm-4">
					<form id="frmClientes">
						<label>Nome</label>
						<input type="text" class="form-control input-sm" id="nome" name="nome">
						<label>Setor</label>
						<input type="text" class="form-control input-sm" id="setor" name="setor">
						<label>Email</label>
						<input type="text" class="form-control input-sm" id="email" name="email">
						<label>Telefone</label>
						<input type="text" class="form-control input-sm phone" id="telefone" name="telefone">
						<label>CPF</label>
						<input type="text" class="form-control input-sm cpf" id="cpf" name="cpf">
						<label>Observações</label>
						<textarea style="resize: none" class="form-control input-sm" id="obs" name="obs" rows="2">-</textarea>
						<p></p>
						<button class="btn btn-primary" id="btnAdicionarCliente">Salvar</button>
					</form>
				</div>
				<div class="col-sm-8">
					<div id="tabelaClientesLoad"></div>
				</div>
			</div>
		</div>

		<!-- Button trigger modal -->


		<!-- Modal -->
		<div class="modal fade" id="abremodalClientesUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Atualizar cliente</h4>
					</div>
					<div class="modal-body">
						<form id="frmClientesU">
							<input type="text" hidden="" id="idclienteU" name="idclienteU">
							<label>Nome</label>
							<input type="text" class="form-control input-sm" id="nomeU" name="nomeU">
							<label>Setor</label>
							<input type="text" class="form-control input-sm" id="setorU" name="setorU">
							<label>Email</label>
							<input type="text" class="form-control input-sm" id="emailU" name="emailU">
							<label>Telefone</label>
							<input type="text" class="form-control input-sm phone" id="telefoneU" name="telefoneU">
							<label>CPF</label>
							<input type="text" class="form-control input-sm cpf" id="cpfU" name="cpfU">
							<label>Observações</label>
							<textarea style="resize: none" class="form-control input-sm" id="obsU" name="obsU" rows="2"></textarea>
						</form>
					</div>
					<div class="modal-footer">
						<button id="btnAdicionarClienteU" type="button" class="btn btn-primary" data-dismiss="modal">Atualizar</button>

					</div>
				</div>
			</div>
		</div>

	</body>
	</html>

	<script type="text/javascript">
		function adicionarDado(idcliente){

			$.ajax({
				type:"POST",
				data:"idcliente=" + idcliente,
				url:"../procedimentos/clientes/obterDadosCliente.php",
				success:function(r){

					dado=jQuery.parseJSON(r);


					$('#idclienteU').val(dado['id_cliente']);
					$('#nomeU').val(dado['nome']);
					$('#setorU').val(dado['setor']);
					$('#emailU').val(dado['email']);
					$('#telefoneU').val(dado['telefone']);
					$('#cpfU').val(dado['cpf']);
					$('#obsU').val(dado['obs']);

				}
			});
		}

		function eliminarCliente(idcliente, nomeCliente){
			alertify.confirm('Deseja excluir este cliente: <b>' + nomeCliente + '</b>?', function(){ 
				$.ajax({
					type:"POST",
					data:"idcliente=" + idcliente,
					url:"../procedimentos/clientes/eliminarClientes.php",
					success:function(r){


						if(r==1){
							$('#tabelaClientesLoad').load("clientes/tabelaClientes.php");
							alertify.success("Excluido com sucesso!");
						}else{
							alertify.error("Não foi possível excluir");
						}
					}
				});
			}, function(){ 
				alertify.error('Cancelado.')
			});
		}
	</script>

	<script type="text/javascript">
		$(document).ready(function(){

			$('#tabelaClientesLoad').load("clientes/tabelaClientes.php");

			$('#btnAdicionarCliente').click(function(){

				vazios=validarFormVazio('frmClientes');

				if(vazios > 0){
					alertify.alert("Preencha todos os campos.");
					return false;
				}

				dados=$('#frmClientes').serialize();

				$.ajax({
					type:"POST",
					data:dados,
					url:"../procedimentos/clientes/adicionarClientes.php",
					success:function(r){

						if(r==1){
							$('#frmClientes')[0].reset();
							$('#tabelaClientesLoad').load("clientes/tabelaClientes.php");
							alertify.success("Cliente adicionado");
						}else{
							alertify.error("Não foi possível adicionar o cliente");
						}
					}
				});
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#btnAdicionarClienteU').click(function(){
				dados=$('#frmClientesU').serialize();

				$.ajax({
					type:"POST",
					data:dados,
					url:"../procedimentos/clientes/atualizarClientes.php",
					success:function(r){

						if(r==1){
							$('#frmClientes')[0].reset();
							$('#tabelaClientesLoad').load("clientes/tabelaClientes.php");
							alertify.success("Cliente atualizado com sucesso!");
						}else{
							alertify.error("Não foi possível atualizar cliente");
						}
					}
				});
			})
		})
	</script>
	<script src="../js/jquery.maskedinput.js"></script>
	<script>
			$(function($){
				$(".date").mask("99/99/9999");
				$(".phone").mask("(99) 99999-9999");
				$(".cpf").mask("999.999.999-99");
				$(".ssn").mask("999-99-9999");
			});
		</script>


	<?php 
}else{
	header("location:../index.php");
}
?>