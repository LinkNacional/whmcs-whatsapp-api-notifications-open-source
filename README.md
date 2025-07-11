# FREE Addon WHMCS WhatsApp Notifications for Meta, Evolution API, Baileys
Free Addon for WhatsApp [WHMCS](https://www.linknacional.com.br/whmcs/) Notification Module for Meta, Evolution API and Baileys. Send Free WhatsApp Message Automatic by Hook, Manual or Custom.
## Requisitos

- PHP: 8.1+;
- WHMCS: 8.6+
- IonCube: 12+
- Banco de Dados SQL com permissões
    - ALTER
    - CREATE
    - DELETE
    - INDEX
    - LOCK TABLES
    - SELECT
    - DROP
    - INSERT
    - REFERENCES
    - UPDATE

## Modo de instalação

1. Baixar o .zip do módulo.
2. Descompacte e envie os arquivos da nova versão para o seu WHMCS para a pasta /modules/addons/.
3. Os Arquivos devem ficar na pasta modules/addons/lknhooknotification
4. Faça o download da última release das notificações em: https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/releases
5. Descompacte e envie os arquivos de notificação para o seu WHMCS, pasta: /modules/addons/lknhooknotification/src/Notifications/ (duas pastas Chatwoot e WhatsApp)

## Modo de atualização

1. Faça backups dos arquivos e principalmente das notificações personalizadas.
2. Baixar o .zip do módulo.
3. Exclua a pasta modules/addons/lknhooknotification
4. Descompacte e envie os arquivos da nova versão para o seu WHMCS para a pasta /modules/addons/.
5. Faça o download da última release das notificações [aqui](https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/releases).
6. Descompacte e envie os arquivos de notificação para o seu WHMCS, pasta: `/modules/addons/lknhooknotification/src/Notifications`

## Modo de uso e configuração

1. Acessa a página de lista de módulos addons do WHMCS.
2. Procure e ative o módulo "WhatsApp e Chatwoot"
3. Conceda controle de acesso aos grupos que terão acesso as configurações.
4. Acessa a página "Notificação WhatsApp e Chatwoot" no item "Addon" do menu do WHMCS.
2. Preencha as informações necessárias para o funcionamento básico do módulo:
    1. Adicionar as suas credenciais para a API do WhatsApp e do Chatwoot.
    2. Configurar as notificações.

## Documentação

Para documentação de tipos, seguir https://phpstan.org/writing-php-code/phpdoc-types.

### Processo de Lançamento de Nova Versão

1. Gerar stubs
2. Atualizar versão atual hardcoded
3. Gerar documentação

### Geração manual de stubs

É necessário rodar `php stubs_generator.php` para gerar os stubs do código e copiar o conteúdo de `stubs.php` e colar no [stubs.php](https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/blob/refactor-3.0.0/stubs.php) do repositório de notificações.

Além disso, a dependência que gera stubs não suporta gerar para Enums, então é necessário copiar os conteúdos dos arquivos sob /Config e colocar em `stubs.php` manualmente.

### Geração de documentação

Rodando o comoando abaixo, são geradas as documentações internas e públicas.

```php phpDocumentor.phar --config=phpdoc.private.xml && php phpDocumentor.phar --config=phpdoc.public.xml```

É necessário enviar a pasta /docs/public ao repositório de notificações, na branch main.

Para visualizar a página da documentação interna, basta acessar o index.html em /docs/public pelo navegador.

## Documentações para o desenvolvimento

- WHMCS Addon Modules: https://developers.whmcs.com/addon-modules/
- WHMCS Hook Index: https://developers.whmcs.com/hooks/hook-index/
- Bootstrap 3.4: https://getbootstrap.com/docs/3.4/components/
- Bootstrap 3.4: https://getbootstrap.com/docs/3.4/css/
- Bootstrap 3.4: https://getbootstrap.com/docs/3.4/javascript/
- FontAwesome 5: https://fontawesome.com/v5/search

### Integrações com as plataformas

#### WhatsApp API

- [Graph API WhatsApp](https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates)
- [Entender classe MessageTemplateParser](https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages)
- [Entender a requisição para a API do WhatsApp](https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages)
- [Enviar message template](https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-message-templates#text-based)
- [Listar message templates](https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/#retrieve-templates)

#### Chatwoot

**Como testar a integração**

- Criar conta gratuita de teste em. Ver: https://www.chatwoot.com/docs/user-guide/setup-your-account/create-an-account/#i-am-using-the-cloud-version
- https://www.chatwoot.com/docs/product/channels/api/send-messages/
- Sobre as APIs do Chatwoot: https://www.chatwoot.com/docs/contributing-guide/chatwoot-apis

A utlizada pelo módulo para enviar mensagens é a Application API.

##### Live Chat

- https://www.chatwoot.com/developers/api/#tag/Custom-Attributes/operation/add-new-custom-attribute-to-account
- https://www.chatwoot.com/hc/user-guide/articles/1677580558-website-live-chat-settings-explained
- https://www.chatwoot.com/hc/user-guide/articles/1677502327-how-to-create-and-use-custom-attributes

### Tipos de notificações

#### Disparo automático

São as notificações que são executadas automaticamente junto com um hook do WHMCS.

#### Disparo manual

Notificações de disparo manual necessitam de uma ação do administrador para serem executados.
Geralmente, utilizam um hook de terminado em Output do WHMCS para exibir um botão que, ao ser clicado, dispara a notificação.

Nesse caso, o módulo apenas disponibiliza uma forma de integrar notificações que utilizam um mesmo hook de output.
Atualmente, existe apenas duas notificações desse tipo: as de InvoiceReminder, que usam o hook AdminInvoicesControlsOutput.

#### Notificações custom

São colocadas aqui: `/modules/addons/lknhooknotification/src/Notifications/Custom`

#### Notificações que rodam nos hooks de cron

Herdam a classe `AbstractCronNotification`.

## Testando integrações

### Evolution API

[Evolution API Docs](https://doc.evolution-api.com/v2/api-reference/get-information)

Para testar, basta fazer o setup localmente e utilizar o ngrok:

```bash
docker run --net=host -it -e NGROK_AUTHTOKEN={NGROK_AUTHTOKEN} ngrok/ngrok:latest http 8080
```

Copiar a URL dada e colocar na configuração do Evolution API, no módulo.

### Baileys API

- https://github.com/WhiskeySockets/Baileys
- https://baileys.wiki/docs/intro/

Para testar, basta fazer o setup localmente e utilizar o ngrok:

```bash
docker run --net=host -it -e NGROK_AUTHTOKEN={NGROK_AUTHTOKEN} ngrok/ngrok:latest http 8080
```

Copiar a URL dada e colocar na configuração do Baileys, no módulo.

[Using ngrok with Docker](https://ngrok.com/docs/using-ngrok-with/docker/)
ping

```php
[
    {
        "messagename": "Password Reset Validation",
        "relid": 1,
        "mergefields": {
            "user_first_name": "Bruno",
            "user_last_name": "Ferreira",
            "user_email": "ferreira.bruno@linknacional.com",
            "reset_password_url": "https://whmcs.linknacional.com/index.php?rp=/password/reset/redeem/c96b9b7e2248870f4e8933009399232ce5d06a5e649cba2c6e6fd9c56f402c7f",
            "company_name": "Link Nacional",
            "companyname": "Link Nacional",
            "company_domain": "http://whmcs.linknacional.com",
            "company_logo_url": "",
            "company_tax_code": null,
            "whmcs_url": "https://whmcs.linknacional.com/",
            "whmcs_link": "<a href=\"https://whmcs.linknacional.com/\">https://whmcs.linknacional.com/</a>",
            "signature": "---<br />\r\nLink Nacional<br />\r\nhttp://whmcs.linknacional.com",
            "date": "Tuesday, 20th May 2025",
            "time": "10:22am",
            "charset": "utf-8"
        }
    }
]
```
