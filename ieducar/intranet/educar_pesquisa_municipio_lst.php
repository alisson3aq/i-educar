<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	*																	     *
	*	@author Prefeitura Municipal de Itaja�								 *
	*	@updated 29/03/2007													 *
	*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
	*																		 *
	*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
	*						ctima@itajai.sc.gov.br					    	 *
	*																		 *
	*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
	*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
	*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
	*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
	*																		 *
	*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
	*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
	*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
	*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
	*																		 *
	*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
	*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
	*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
	*	02111-1307, USA.													 *
	*																		 *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
require_once ("include/clsBase.inc.php");
require_once ("include/clsListagem.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/Geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Municipio" );
		$this->processoAp = "0";
		$this->renderBanner = false;
		$this->renderMenu = false;
		$this->renderMenuSuspenso = false;
	}
}

class indice extends clsListagem
{
	/**
	 * Referencia pega da session para o idpes do usuario atual
	 *
	 * @var int
	 */
	var $pessoa_logada;

	/**
	 * Titulo no topo da pagina
	 *
	 * @var int
	 */
	var $titulo;

	/**
	 * Quantidade de registros a ser apresentada em cada pagina
	 *
	 * @var int
	 */
	var $limite;

	/**
	 * Inicio dos registros a serem exibidos (limit)
	 *
	 * @var int
	 */
	var $offset;

	var $idmun;
	var $nome;
	var $sigla_uf;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		$_SESSION["campo1"] = $_GET["campo1"] ? $_GET["campo1"] : $_SESSION["campo1"];
		//$_SESSION["campo2"] = $_GET["campo2"] ? $_GET["campo2"] : $_SESSION["campo2"];
		session_write_close();

		$this->titulo = "Municipio - Listagem";

		foreach( $_GET AS $var => $val ) // passa todos os valores obtidos no GET para atributos do objeto
			$this->$var = ( $val === "" ) ? null: $val;

		//$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->addCabecalhos( array(
			"Cidade",
			"Estado"
		) );


		$obj_uf = new clsUf(false, false, 1);
		$lst_uf = $obj_uf->lista(false, false, 1, false, false, "sigla_uf");
		$array_uf = array('' => 'Todos');
		foreach ($lst_uf as $uf)
		{
			$array_uf[$uf['sigla_uf']] = $uf['nome'];
		}
		if(!isset($this->sigla_uf))
		{
			$this->sigla_uf = "SC";
		}




		// outros Filtros

		$this->campoLista("sigla_uf", "UF", $array_uf, $this->sigla_uf, "", false, "","", $disabled);
		$this->campoTexto( "nome", "Cidade", $this->nome, 30, 255, false );
	//	$this->campoTexto( "sigla_uf", "Sigla Uf", $this->sigla_uf, 30, 255, false );


		// Paginador
		$this->limite = 20;
		$this->offset = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$this->limite-$this->limite: 0;

		$obj_municipio = new clsMunicipio();
		//$obj_municipio->setOrderby( "nome ASC" );
		//$obj_municipio->setLimite( $this->limite, $this->offset );

		$lista = $obj_municipio->lista($this->nome,$this->sigla_uf,null,null,null,null,null,null,null,$this->offset,$this->limite,"nome ASC");

		$total = $obj_municipio->_total;

		// monta a lista
		if( is_array( $lista ) && count( $lista ) )
		{
			foreach ( $lista AS $registro )
			{


				if( class_exists( "clsUf" ) )
				{
					$obj_sigla_uf = new clsUf($registro["sigla_uf"]->sigla_uf);
					$det_sigla_uf = $obj_sigla_uf->detalhe();
					$registro["sigla_uf"] = $det_sigla_uf["nome"];
				}
				else
				{
					$registro["sigla_uf"] = "Erro na geracao";
					echo "<!--\nErro\nClasse nao existente: clsUf\n-->";
				}

				$script = " onclick=\"addSel1('{$_SESSION['campo1']}','{$registro['idmun']}','{$registro['nome']}'); fecha();\"";
				$this->addLinhas( array(
					"<a href=\"javascript:void(0);\" {$script}>{$registro["nome"]}</a>",
					"<a href=\"javascript:void(0);\" {$script}>{$registro["sigla_uf"]}</a>"
				) );
			}
		}
		$this->addPaginador2( "educar_pesquisa_municipio_lst.php", $total, $_GET, $this->nome, $this->limite );

		$this->largura = "100%";
	}
}
// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na clsBase
$pagina->addForm( $miolo );
// gera o html
$pagina->MakeAll();
?>
<script>
function addSel1( campo, valor, texto )
{
	obj = window.parent.document.getElementById( campo );
	novoIndice = obj.options.length;
	obj.options[novoIndice] = new Option( texto );
	opcao = obj.options[novoIndice];
	opcao.value = valor;
	opcao.selected = true;
	setTimeout( "obj.onchange", 100 );
}

function addVal1( campo,valor )
{

	obj =  window.parent.document.getElementById( campo );
	obj.value = valor;
}

function fecha()
{
	window.parent.fechaExpansivel('div_dinamico_'+(parent.DOM_divs.length*1-1));
	//window.parent.document.forms[0].submit();
}
</script>