<?php

//API: Simula dados vindos de um banco com as informações de uma organização
namespace  App\Model\Entity;


class Organization{

    /**
     * ID da organização
     * @var integer
     */
    public $id = 1;

    /**
     * Nome da organização
     * @var string
     */
    public $name = 'Canal de Servições Públicos de SmartCamaqua';

    /**
     * Site da Organização
     */
    public $site = '<a href="http://smartcamaqua.herokuapp.com/app/home_dash.php" target="_blank">http://smartcamaqua.herokuapp.com/app/home_dash.php</a>';

    /**
     * Descrição da organização
     * @var string
     */
    public $description = '<h1>
        Seu governo com serviços digitais em plataforma
        única e transformação real na vida de todos
    </h1><br><p><strong>É mais que mobilidade</strong>. Cidade Inteligente traz inovação na maneira como a Prefeitura trabalha, promove a justiça fiscal e social e se comunica com todos os cidadãos. Sua plataforma traz diversos serviços públicos digitais e informação em portal único, conforme padrão Gov.br, além de eliminar o uso de papel, acabar com filas e romper a burocracia</p><br><p><strong>É simples para todos.</strong> Em formato web e aplicativo, cria perfil do cidadão e oferece experiência de uso prática, serviços disponíveis 24 horas, linguagem clara e com recursos de inclusão e acessibilidade digital. Tudo personalizado de acordo com as necessidades da sua gestão e com especial atenção ao tratamento de dados.</p><br><p><strong>É apresentada por serviços.</strong> Além de integrar todas as secretarias, a plataforma organiza e apresenta seus serviços por categorias de maneira simples e prática para o cidadão. Coloque sua gestão como referência e assegure resultados em um Governo Digital.</p>
    ';
}