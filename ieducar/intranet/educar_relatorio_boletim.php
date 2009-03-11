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
require_once ("include/clsCadastro.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/pmieducar/geral.inc.php" );
require_once ("include/clsPDF.inc.php");

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Boletim" );
		$this->processoAp = "664";
	}
}

class indice extends clsCadastro
{


	/**
	 * Referencia pega da session para o idpes do usuario atual
	 *
	 * @var int
	 */
	var $pessoa_logada;


	var $ref_cod_instituicao;
	var $ref_cod_escola;
	var $ref_cod_serie;
	var $ref_cod_turma;

	var $ano;
	var $mes;

	var $nm_escola;
	var $nm_instituicao;
	var $ref_cod_curso;
	var $sequencial;
	var $pdf;
	var $pagina_atual = 1;
	var $total_paginas = 1;
	var $nm_professor;
	var $nm_turma;
	var $nm_serie;
	var $nm_disciplina;
	var $curso_com_exame = 0;
	var $ref_cod_matricula;

	var $page_y = 135;

	var $nm_aluno;
	var $array_modulos = array();
	var $nm_curso;
	var $get_link = false;
	//var $cursos = array();

	var $total;

	//var $array_disciplinas = array();

	var $ref_cod_modulo;

	var $meses_do_ano = array(
							 "1" => "JANEIRO"
							,"2" => "FEVEREIRO"
							,"3" => "MAR&Ccedil;O"
							,"4" => "ABRIL"
							,"5" => "MAIO"
							,"6" => "JUNHO"
							,"7" => "JULHO"
							,"8" => "AGOSTO"
							,"9" => "SETEMBRO"
							,"10" => "OUTUBRO"
							,"11" => "NOVEMBRO"
							,"12" => "DEZEMBRO"
						);


	function Inicializar()
	{
		$retorno = "Novo";
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$obj_permissoes = new clsPermissoes();
		//if($obj_permissoes->nivel_acesso($this->pessoa_logada) > 7)
			//header("location: index.php");

		return $retorno;
	}

	function Gerar()
	{

		$obj_permissoes = new clsPermissoes();
		$nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);

		//if(!$nivel_usuario)
			//header("location: index.php");

		if($_POST){
			foreach ($_POST as $key => $value) {
				$this->$key = $value;

			}
		}

		$this->ano = $ano_atual = date("Y");
		$this->mes = $mes_atual = date("n");
		/*
		$lim = 5;
		for($a = date('Y') ; $a < $ano_atual + $lim ; $a++ )
				$anos["{$a}"] = "{$a}";

		$this->campoLista( "ano", "Ano",$anos, $this->ano,"",false );
		*/
		$this->campoNumero( "ano", "Ano", $this->ano, 4, 4, true );

		$this->campoCheck("em_branco","Relat�rio em branco","");
		$this->campoNumero("numero_registros","N&uacute;mero de linhas","",3,3);

		//$this->campoLista( "mes", "M&ecirc;s",$this->meses_do_ano, $this->mes,"",false );

		$get_escola = true;
		//$obrigatorio = true;
		$exibe_nm_escola = true;
//		$get_escola_curso = true;
		$get_curso = true;
		$get_escola_curso_serie = true;
		$escola_obrigatorio = false;
		$curso_obrigatorio = false;
		$instituicao_obrigatorio = true;

		include("include/pmieducar/educar_campo_lista.php");

		$this->campoLista("ref_cod_turma","Turma",array('' => 'Selecione'),'',"",false,"","",false,false);

		if($this->ref_cod_escola)
			$this->ref_ref_cod_escola = $this->ref_cod_escola;
		$this->campoLista( "ref_cod_matricula", "Aluno",array(''=>'Selecione'), "","",false,"Campo n�o obrigat�rio","",false,false );
		if($this->get_link)
			$this->campoRotulo("rotulo11", "-", "<a href='$this->get_link' target='_blank'>Baixar Relat�rio</a>");

		$this->url_cancelar = "educar_index.php";
		$this->nome_url_cancelar = "Cancelar";

		$this->acao_enviar = 'acao2()';
		$this->acao_executa_submit = false;

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


document.getElementById('ref_cod_escola').onchange = function()
{
	setMatVisibility();
	getEscolaCurso();
	var campoTurma = document.getElementById( 'ref_cod_turma' );
	getTurmaCurso();
}

document.getElementById('ref_cod_curso').onchange = function()
{

	getEscolaCursoSerie();
	getTurmaCurso();
}

document.getElementById('ano').onkeyup = function()
{

	setMatVisibility();
	getAluno();
}

document.getElementById('ref_ref_cod_serie').onchange = function()
{

	var campoEscola = document.getElementById( 'ref_cod_escola' ).value;
	var campoSerie = document.getElementById( 'ref_ref_cod_serie' ).value;

	var xml1 = new ajax(getTurma_XML);
	strURL = "educar_turma_xml.php?esc="+campoEscola+"&ser="+campoSerie;
	xml1.envia(strURL);
}

function getTurma_XML(xml)
{


	var campoSerie = document.getElementById( 'ref_ref_cod_serie' ).value;

	var campoTurma = document.getElementById( 'ref_cod_turma' );

	var turma = xml.getElementsByTagName( "turma" );

	campoTurma.length = 1;
	campoTurma.options[0] = new Option( 'Selecione uma Turma', '', false, false );
	for ( var j = 0; j < turma.length; j++ )
	{

		campoTurma.options[campoTurma.options.length] = new Option( turma[j].firstChild.nodeValue, turma[j].getAttribute('cod_turma'), false, false );

	}
	if ( campoTurma.length == 1 && campoSerie != '' ) {
		campoTurma.options[0] = new Option( 'A s�rie n�o possui nenhuma turma', '', false, false );
	}

	setMatVisibility();

}

function getTurmaCurso()
{
	var campoCurso = document.getElementById('ref_cod_curso').value;
	var campoInstituicao = document.getElementById('ref_cod_instituicao').value;

	var xml1 = new ajax(getTurmaCurso_XML);
	strURL = "educar_turma_xml.php?ins="+campoInstituicao+"&cur="+campoCurso;

	xml1.envia(strURL);
}

function getTurmaCurso_XML(xml)
{
	var turma = xml.getElementsByTagName( "turma" );
	var campoTurma = document.getElementById( 'ref_cod_turma' );
	var campoCurso = document.getElementById('ref_cod_curso');

	campoTurma.length = 1;
	campoTurma.options[0] = new Option( 'Selecione uma Turma', '', false, false );

	for ( var j = 0; j < turma.length; j++ )
	{

		campoTurma.options[campoTurma.options.length] = new Option( turma[j].firstChild.nodeValue, turma[j].getAttribute('cod_turma'), false, false );

	}
	/*if ( campoTurma.length == 1 && campoCurso != '' ) {
		campoTurma.options[0] = new Option( 'O curso n�o possui nenhuma turma', '', false, false );
	}*/
	setMatVisibility();
}


document.getElementById('ref_cod_turma').onchange = function()
{
	getAluno();
	var This = this;
	setMatVisibility();

}

function setMatVisibility()
{
	var campoTurma = document.getElementById('ref_cod_turma');
	var campoAluno = document.getElementById('ref_cod_matricula');

	campoAluno.length = 1;

	if (campoTurma.value == '')
	{
		setVisibility('tr_ref_cod_matricula',false);
		setVisibility('ref_cod_matricula',false);
	}
	else
	{
		setVisibility('tr_ref_cod_matricula',true);
		setVisibility('ref_cod_matricula',true);
	}
}
function getAluno()
{

	var campoTurma = document.getElementById('ref_cod_turma').value;
	var campoAno = document.getElementById('ano').value;

	var xml1 = new ajax(getAluno_XML);
	strURL = "educar_matricula_turma_xml.php?tur="+campoTurma+"&ano="+campoAno;

	xml1.envia(strURL);
}

function getAluno_XML(xml)
{
	var aluno = xml.getElementsByTagName( "matricula" );
	var campoTurma = document.getElementById( 'ref_cod_turma' );
	var campoAluno = document.getElementById('ref_cod_matricula');

	campoAluno.length = 1;
	//campoAluno.options[0] = new Option( 'Selecione uma Turma', '', false, false );

	for ( var j = 0; j < aluno.length; j++ )
	{

		campoAluno.options[campoAluno.options.length] = new Option( aluno[j].firstChild.nodeValue, aluno[j].getAttribute('cod_matricula'), false, false );

	}
	/*if ( campoTurma.length == 1 && campoCurso != '' ) {
		campoTurma.options[0] = new Option( 'O curso n�o possui nenhuma turma', '', false, false );
	}*/
}


setVisibility('tr_ref_cod_matricula',false);
var func = function(){document.getElementById('btn_enviar').disabled= false;};
if( window.addEventListener ) {
		//mozilla
	  document.getElementById('btn_enviar').addEventListener('click',func,false);
	} else if ( window.attachEvent ) {
		//ie
	  document.getElementById('btn_enviar').attachEvent('onclick',func);
	}

function acao2()
{

	var em_branco = document.getElementById( "em_branco" );

	if(em_branco.checked)
	{

	}
	else
	{
		if(!acao())
			return;
		else
		{
			 if (!(/[^ ]/.test( document.getElementById("ref_cod_instituicao").value )))
			{
				mudaClassName( 'formdestaque', 'obrigatorio' );
				document.getElementById("ref_cod_instituicao").className = "formdestaque";
				alert( 'Preencha o campo \'Institui��o\' corretamente!' );
				document.getElementById("ref_cod_instituicao").focus();
				return false;
			}
			 if (!(/[^ ]/.test( document.getElementById("ref_cod_curso").value )))
			{
				mudaClassName( 'formdestaque', 'obrigatorio' );
				document.getElementById("ref_cod_curso").className = "formdestaque";
				alert( 'Preencha o campo \'Curso\' corretamente!' );
				document.getElementById("ref_cod_curso").focus();
				return false;
			}


			 if (!(/[^ ]/.test( document.getElementById("ref_cod_turma").value )))
			{
				mudaClassName( 'formdestaque', 'obrigatorio' );
				document.getElementById("ref_cod_turma").className = "formdestaque";
				alert( 'Preencha o campo \'Turma\' corretamente!' );
				document.getElementById("ref_cod_turma").focus();
				return false;
			}
		}
	}

	showExpansivelImprimir(400, 200,'',[], "Boletim");

	document.formcadastro.target = 'miolo_'+(DOM_divs.length-1);

	document.getElementById( 'btn_enviar' ).disabled =false;

	document.formcadastro.submit();

}

document.formcadastro.action = 'educar_relatorio_boletim_proc.php';

document.getElementById('em_branco').onclick = function()
{
	if(this.checked)
	{
		$('ref_cod_instituicao').disabled = true;
		$('ref_cod_escola').disabled = true;
		$('ref_cod_curso').disabled = true;
		$('ref_ref_cod_serie').disabled = true;
		$('ref_cod_turma').disabled = true;
		$('ref_cod_matricula').disabled = true;
	}
	else
	{
		$('ref_cod_instituicao').disabled = false;
		$('ref_cod_escola').disabled = false;
		$('ref_cod_curso').disabled = false;
		$('ref_ref_cod_serie').disabled = false;
		$('ref_cod_turma').disabled = false;
		$('ref_cod_matricula').disabled = false;
	}
}
</script>
