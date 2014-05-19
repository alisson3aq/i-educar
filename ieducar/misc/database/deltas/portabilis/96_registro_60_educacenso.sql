  -- //

  --
  -- Cria colunas necessárias para atender o registro 60 do Educacenso
  --
  -- @author   Lucas Schmoeller da Silva <lucas@portabilis.com.br>
  -- @license  @@license@@
  -- @version  $Id$

  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_aux_ledor SMALLINT;

  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_aux_transcricao SMALLINT;
  
  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_guia_interprete SMALLINT;
  
  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_interprete_libras SMALLINT;
  
  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_leitura_labial SMALLINT;
  
  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_prova_ampliada_16 SMALLINT;
  
  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_prova_ampliada_20 SMALLINT;
  
  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_prova_ampliada_24 SMALLINT;
  
  ALTER TABLE pmieducar.aluno ADD COLUMN recurso_prova_inep_prova_braille SMALLINT;
  

  -- //@UNDO
  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_aux_ledor;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_aux_transcricao;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_guia_interprete;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_interprete_libras;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_leitura_labial;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_prova_ampliada_16;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_prova_ampliada_20;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_prova_ampliada_24;

  ALTER TABLE pmieducar.aluno DROP COLUMN recurso_prova_inep_prova_braille;


  -- //