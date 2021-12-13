# globo-polen

---

Virtual Host
polen.globo

Login: polen@c9t.pw
Senha: #!Polen*#

## Configurando o Ambiente
1 - Clone o projeto
2 - Abra as configurações de hosts
```
sudo nano /etc/hosts
```
3 - Aponte localhost para polen.globo colando o seguinte código:
```
127.0.0.1       polen.globo
```
3 - Acesse a pasta conf e copie os seguintes arquivos:
```
conf/wordpress/.htaccess
```
```
conf/wordpress/wp-config
```
4 - Cole os arquivos dentro da pasta do Wordpress
5 - Suba o docker rodando o seguinte script
```
docker-compose up -d
```
6 - Importe o seguinte arquivo sql dentro da base de dados que foi criada pelo Docker
```
sql/database.sql.gz
```
6 - O projeto estará rodando no browser em:
```
polen.globo
```