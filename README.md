# WheelchairBasketballApp-API

This project is backend app for [WheelchairBasketballApp](https://github.com/arowinsky/WheelchairBasketballApp)

## In CLI

Install dependencies:
```bash
composer install
```
Creacte diretory in config
```bash
mkdir config\jwt
```
Set JWT_PASSPHRASE in .env

Next create private and public key
When generating the key, you must enter the same password as JWT PASSPHRASE
```bash
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```
```bash
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
Run dev server (on port 8000):
```bash
symfony serve
```
