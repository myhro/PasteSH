<?php

// O resultado exibido sempre será um arquivo em texto plano.
header('Content-Type: text/plain; charset="UTF-8"');

// 'Conecta-se' ao banco de dados SQLite. É importante que sua pasta também tenha permissão de escrita.
$db = sqlite_open('pastesh.db', 644, $error);
if (!$db) die($error);

// Pega o parâmetro passado após a '/' na URL.
$urlparam = substr($_SERVER['REQUEST_URI'], 1);

// Se não havia parâmetros, a requisição só pode ser uma postagem nova ou um acesso direto.
if (!strlen($urlparam)) {
    // Se o conteúdo do arquivo estiver presente no POST, é gravada uma nova entrada.
   if (isset($_POST['file'])) {
        // O conteúdo é o arquivo sanitizado.
        $content = sqlite_escape_string($_POST['file']);
        // A data e hora atual serão salvas no registro.
        date_default_timezone_set('America/Sao_Paulo');
        $creation = date('Y/m/d H:i:s');
        // Para evitar os números sequenciais, o identificador do arquivo será os 4 primeiros dígitos do seu hash SHA1.
        $hash = substr(sha1($content), 0, 4); 
        while (true) {
            // Mas caso este hash já esteja presente no banco de dados, gera-se um novo hash a partir dele mesmo.
            if (sqlite_fetch_array(sqlite_query($db, "SELECT * FROM files WHERE hash='$hash'"))) $hash = substr(sha1($hash), 0, 4);
            else break;
        }
        // A entrada é salva no banco de dados e seu link é exibido para o usuário.
        $sqlcmd = "INSERT INTO files VALUES (NULL, '$hash', '$content', '0', '" . $_SERVER['REMOTE_ADDR'] . "', '$creation')";
        if (sqlite_exec($db, $sqlcmd, $error)) echo 'Link: http://' . $_SERVER['HTTP_HOST'] . '/' . $hash . PHP_EOL;
        else echo "Error: $error" . PHP_EOL;
    }
    // Se o conteúdo do arquivo não estava presente, ou este era muito grande ou tentou-se um acesso direto a página.
    else {
        header('Status: 301 Moved Permanently');
        header('Location: http://myhro.net/');
        echo 'Error: File too large or not present.' . PHP_EOL;
    }
}
else {
    // Se havia um parâmetro na URL, este será buscado no banco de dados.
    $hash = sqlite_escape_string($urlparam);
    $result = sqlite_fetch_array(sqlite_query($db, "SELECT * FROM files WHERE hash='$hash'"));
    // O usuário é informado se o registro não for encontrado.
    if (!$result) {
        header('Status: 404 Not Found');
        echo 'Error: 404 Not Found.' . PHP_EOL;
    }
    // Caso contrário, seu conteúdo será exibido e o contador de visualizações será incrementado.
    else {
        echo $result['content'] . PHP_EOL;
        $sqlcmd = "UPDATE files SET views = '" . ($result['views'] + 1) . "' WHERE hash ='$hash'";
        sqlite_exec($db, $sqlcmd);
    }
}

// 'Desconecta-se' do banco de dados SQLite.
sqlite_close($db);
