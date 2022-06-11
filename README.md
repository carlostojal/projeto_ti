# Projeto TI
## Projeto de Tecnologias de Internet - Instituto Politécnico de Leiria

Dadas as dependências necessárias para correr o projeto, e de forma a facilitar este processo e otimizar a colaboração entre os membros do grupo, decidimos utilizar Docker como solução de containerização para evitar perder tempo com problemas específicos do ambiente, criando um ambiente de desenvolvimento e testes completamente uniforme e estanque.

### Pre-requisitos
- Docker

### Como correr?
- Dentro do diretório atual, compilar a imagem com o comando `docker build -t projeto_ti:latest .`.
- Iniciar um container da imagem com o comando `docker run -d --name projeto_ti -p 80:80 --mount type=bind,source="$(pwd)",target=/var/www/html projeto_ti`. Desta forma a porta 80 do host será binded à porta 80 do container, ou seja, os pedidos feitos à porta 8080 do host serão reencaminhados para a porta 80 do container.
- Para terminar o container, correr o comando `docker rm -f projeto_ti`.
- Quando não for mais necessário, eliminar a imagem com o comando `docker rmi projeto_ti`.