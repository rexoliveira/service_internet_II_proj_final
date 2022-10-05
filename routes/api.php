<?php

//API: INCLUI AS ROTAS DA APIs 'padrão-v1', 'servicos', 'usuarios' e 'auth'...


//INCLUI ROTAS PADROES DA API
include_once __DIR__ . '/api/v1/default.php';

//INCLUI ROTA DE SERVIÇOS
include_once __DIR__ . '/api/v1/services.php';

//INCLUI ROTA DE USUÁRIOS
include_once __DIR__ . '/api/v1/users.php';

//INCLUI ROTA DE AUTORIZAÇÃO
include_once __DIR__ . '/api/v1/auth.php';