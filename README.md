# globo-polen

---

Virtual Host
polen.globo

Login: polen@c9t.pw
Senha: #!Polen*#

## Configurando o Ambiente
Abra as configurações de hosts
```
sudo nano /etc/hosts
```
Aponte localhost para polen.globo colando o seguinte código:
```
127.0.0.1       polen.globo
```
Acesse a pasta conf e copie os seguintes arquivos:
```
conf/wordpress/.htaccess
```
```
conf/wordpress/wp-config
```
Cole os arquivos dentro da pasta do Wordpress
```
/wordpress
```
Suba o docker rodando o seguinte script
```
docker-compose up -d
```
Importe o seguinte arquivo sql dentro da base de dados que foi criada pelo Docker
```
sql/database.sql.gz
```
O projeto estará rodando no browser em:
```
polen.globo
```