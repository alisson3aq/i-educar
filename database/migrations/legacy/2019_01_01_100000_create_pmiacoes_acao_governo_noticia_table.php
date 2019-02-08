<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreatePmiacoesAcaoGovernoNoticiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            '
                SET default_with_oids = true;
                
                CREATE TABLE pmiacoes.acao_governo_noticia (
                    ref_cod_acao_governo integer NOT NULL,
                    ref_cod_not_portal integer NOT NULL,
                    ref_funcionario_cad integer NOT NULL,
                    data_cadastro timestamp without time zone NOT NULL
                );
                
                ALTER TABLE ONLY pmiacoes.acao_governo_noticia
                    ADD CONSTRAINT acao_governo_noticia_pkey PRIMARY KEY (ref_cod_acao_governo, ref_cod_not_portal);
            '
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pmiacoes.acao_governo_noticia');
    }
}