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
require_once ("include/clsDetalhe.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/pmieducar/geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Infra Predio" );
		$this->processoAp = "567";
		$this->addEstilo("localizacaoSistema");
	}
}

class indice extends clsDetalhe
{
	/**
	 * Titulo no topo da pagina
	 *
	 * @var int
	 */
	var $titulo;
	
	var $cod_infra_predio;
	var $ref_usuario_exc;
	var $ref_usuario_cad;
	var $ref_cod_escola;
	var $nm_predio;
	var $desc_predio;
	var $endereco;
	var $data_cadastro;
	var $data_descricao;
	var $ativo;
	
	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		//** Verificacao de permissao para cadastro
		$obj_permissao = new clsPermissoes();
		
		if($obj_permissao->permissao_cadastra(567, $this->pessoa_logada,7))
		{	
			$this->url_novo = "educar_tipo_usuario_cad.php";
			$this->url_editar = "educar_tipo_usuario_cad.php?cod_tipo_usuario={$registro["cod_tipo_usuario"]}";
		}
		//**
				
		$this->titulo = "Infra Predio - Detalhe";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->cod_infra_predio=$_GET["cod_infra_predio"];

		$tmp_obj = new clsPmieducarInfraPredio( $this->cod_infra_predio );
		$registro = $tmp_obj->detalhe();
		
		if( ! $registro )
		{
			header( "location: educar_infra_predio_lst.php" );
			die();
		}
		
		if( class_exists( "clsPmieducarEscola" ) )
		{
			$obj_ref_cod_escola = new clsPmieducarEscola( $registro["ref_cod_escola"] );
			$det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
			$registro["ref_cod_escola"] = $det_ref_cod_escola["nm_escola"];
		}
		else
		{
			$registro["ref_cod_escola"] = "Erro na geracao";
			echo "<!--\nErro\nClasse nao existente: clsPmieducarEscola\n-->";
		}

		if( $registro["cod_infra_predio"] )
		{
			$this->addDetalhe( array( "Infra Predio", "{$registro["cod_infra_predio"]}") );
		}
		if( $registro["ref_cod_escola"] )
		{
			$this->addDetalhe( array( "Escola", "{$registro["ref_cod_escola"]}") );
		}
		if( $registro["nm_predio"] )
		{
			$this->addDetalhe( array( "Nome Predio", "{$registro["nm_predio"]}") );
		}
		if( $registro["desc_predio"] )
		{
			$this->addDetalhe( array( "Descri&ccedil;&atilde;o Pr&eacute;dio", "{$registro["desc_predio"]}") );
		}
		if( $registro["endereco"] )
		{
			$this->addDetalhe( array( "Endere&ccedil;o", "{$registro["endereco"]}") );
		}

		//** Verificacao de permissao para cadastro
		$obj_permissao = new clsPermissoes();
		
		if($obj_permissao->permissao_cadastra(567, $this->pessoa_logada,7))
		{	
			$this->url_novo = "educar_infra_predio_cad.php";
			$this->url_editar = "educar_infra_predio_cad.php?cod_infra_predio={$registro["cod_infra_predio"]}";
		}
		//**		

		$this->url_cancelar = "educar_infra_predio_lst.php";
		$this->largura = "100%";

		$localizacao = new LocalizacaoSistema();
	    $localizacao->entradaCaminhos( array(
	         $_SERVER['SERVER_NAME']."/intranet" => "In&iacute;cio",
	         "educar_index.php"                  => "M&oacute;dulo Escola",
	         ""        => "Detalhe do pr&eacute;dio"
	    ));
	    $this->enviaLocalizacao($localizacao->montar());
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