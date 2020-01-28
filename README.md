
# Requirements
- php >= 7.2.5
- OpenSSL

# Build
For build this project, run code above:
```
composer install
```

# Instructions
- Edit the DATABASE_URL in .env;
- Generate the SSH keys int project dir:

``` bash
$ mkdir -p config/jwt
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
- Assign permission to execute in the two files generate:
``` bash
$ chmod oug+x config/jwt/*
```

# Third-party libraries
- **Symfony**: faster and flexible web applications development and consome less memory then other frameworks;
- **Doctrine**: ORM with integration to Symfony;
- **Respect Validation** (https://github.com/Respect/Validation): package simple and powerful to validate various inputs;
- **JWT Authentication** (https://github.com/lexik/LexikJWTAuthenticationBundle): package implements JWT (Json Web Token) in authentication service of Symfony.
