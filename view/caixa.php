<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>


	<!DOCTYPE html>
	<html>
	<head>
		<title>Entrada de produtos</title>
		<?php require_once "menu.php"; ?>
		<?php require_once "../classes/conexao.php"; 
		$c= new conectar();
		$conexao=$c->conexao();
		?>


		<script type="text/javascript" src="../js/jquery.maskMoney.min.js" ></script>
		<script type="text/javascript">
				$(document).ready(function(){
					$("input.dinheiro").maskMoney({showSymbol:true, symbol:"R$", decimal:",", thousands:"."});
				});
		</script>

	</head>
	<body>
		<div class="container">
			<h1>Gestão de Caixa</h1>
			<h4>Fechar caixa diário</h4>
			<div class="row">
				<div class="col-sm-4">
					<form id="frmCaixa" enctype="multipart/form-data">
						<label for="produtoSelect">Data</label>
						<input class="form-control input-sm" type="date" name="dataCaixa" id="dataCaixa">
						<label>Valor</label>
						<input type="text" class="form-control input-sm dinheiro" id="preco" name="preco">
						<p></p>
						<span id="btnAddCaixa" class="btn btn-primary">Adicionar</span>
					</form>
				</div>


				<div class="col-sm-8">
					<div id="tabelaCaixaLoad"></div>
				</div>
			</div>
		</div>

		<!-- Button trigger modal -->
		
		<!-- Modal -->
		<div class="modal fade" id="abremodalUpdateCompra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Editar compra</h4>
					</div>


					<div class="modal-body">
						<form id="frmCaixaU" enctype="multipart/form-data">
						<label for="produtoSelect">Data</label>
						<input class="form-control input-sm" type="date" name="dataCaixaU" id="dataCaixaU">
						<label>Valor</label>
						<input type="text" class="form-control input-sm dinheiro" id="precoU" name="precoU">
						<p></p>
					</form>
					</div>
					<div class="modal-footer">
						<button id="btnAtualizarCompra" type="button" class="btn btn-warning" data-dismiss="modal">Editar</button>

					</div>
				</div>
			</div>
		</div>

	</body>
	</html>

	<script type="text/javascript">
		function addDadosCaixa(idcaixa){
			$.ajax({
				type:"POST",
				data:"idcaixa=" + idcaixa,
				url:"../procedimentos/financeiros/obterDadosCaixa.php",
				success:function(r){
					console.log(r);
					dado=jQuery.parseJSON(r);
					$('#dataCaixaU').val(dado['dataCaixa']);
					$('#precoU').val(dado['preco']);

				}
			});
		}

		function eliminarProduto(idCompra, nomeProduto,fornecedor){
			alertify.confirm('Deseja excluir esta compra: <strong>' + nomeProduto + '</strong> em <strong>' + fornecedor + '</strong>?', function(){ 
				$.ajax({
					type:"POST",
					data:"idcompra=" + idCompra,
					url:"../procedimentos/financeiros/eliminarCompras.php",
					success:function(r){
						if(r==1){
							$('#tabelaCaixaLoad').load("financeiros/tabelaCaixa.php");
							alertify.success("Produto excluido com sucesso.");
						}else{
							alertify.error("Não excluido.");
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
			$('#btnAtualizarCompra').click(function(){

				dados=$('#frmCompraU').serialize();
				$.ajax({
					type:"POST",
					data:dados,
					url:"../procedimentos/financeiros/atualizarCompra.php",
					success:function(r){
						alert(r);
						if(r==1){
							$('#tabelaCaixaLoad').load("financeiros/tabelaCaixa.php");
							alertify.success("Editado com sucesso.");
						}else{
							alertify.error("Erro ao editar produto.");
						}
					}
				});
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#tabelaCaixaLoad').load("financeiros/tabelaCaixa.php");

			$('#btnAddCaixa').click(function(){

				vazios=validarFormVazio('frmCaixa');

				if(vazios > 0){
					alertify.alert("Preencha todos os campos!");
					return false;
				}

				var formData = new FormData(document.getElementById("frmCaixa"));

				$.ajax({
					url: "../procedimentos/financeiros/inserirCaixa.php",
					type: "post",
					dataType: "html",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,

					success:function(r){
						
						if(r == 1){
							$('#frmCaixa')[0].reset();
							$('#tabelaCaixaLoad').load("financeiros/tabelaCaixa.php");
							alertify.success("Adicionado com sucesso!");
						}else{
							alertify.error("Falha ao adicionar");
						}
					}
				});
				
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#produtoSelect').select2();
			$('#fornecedorSelect').select2();
		});

		
	</script>

	<?php 
}else{
	header("location:../index.php");
}
?>