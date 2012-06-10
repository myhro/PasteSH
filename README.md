PasteSH
===========

Você sempre quis um jeito fácil de copiar arquivos-texto entre servidores (remotos ou não) sem se preocupar em usar o clipboard (o que nem sempre é possível ou cômodo) ou um protocolo com o SCP? Acredita que por mais espetacular que seja o [Gist](https://gist.github.com/) as vezes você só queria algo mais simples? Pois seus problemas acabaram!
## Instalação

Basta criar um banco de dados SQLite, importando o schema disponível no arquivo `pastesh.sql` e disponibilizar o arquivo `index.php` em qualquer servidor HTTP com suporte ao interpretador PHP. Em seguida, basta redirecionar a página de erro 404 do servidor para o mesmo (exemplo utilizável no `.htaccess` do [Apache](http://httpd.apache.org/)):

    ErrorDocument 404 /index.php

Ou criar uma simples regra de rewrite (exemplo utilizável no [nginx](http://nginx.org/)):

    if (!-e $request_filename) {
        rewrite ^.*$ /index.php last;
    }

## Uso

Não há uma página com um formulário onde se possa enviar os arquivos, apenas para visualizá-los posteriormente. O processo de envio é realizado com alguma ferramenta de linha de comando que possa enviar comandos POST a um servidor, como o [cURL](http://curl.haxx.se/) (mude apenas o nome do arquivo e a URL, mas não o parâmetro "file"):

    curl -F 'file=receita_de_bolo_de_fuba.txt' http://p.myhro.net/

Este comando retorna o link único referente ao arquivo que foi enviado (ou um erro específico caso algo algo dê errado no processo).

## License

The source code is licensed under the GNU General Public License Version 2 (GPLv2). It is available in both [HTML](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html) and [TXT](https://www.gnu.org/licenses/gpl-2.0.txt) formats.

## Autor

Tiago "Myhro" Ilieve
