# FREE Addon WHMCS WhatsApp Notifications for Meta, Evolution API, Baileys

Free Addon for WhatsApp [WHMCS](https://www.linknacional.com.br/whmcs/) Notification Module for Meta, Evolution API and Baileys. Send Free WhatsApp Message Automatic by Hook, Manual or Custom.

## Requirements

- PHP: 8.1+;
- WHMCS: 8.6+
- IonCube: 12+
- Data Base SQL with permissions
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

## Installation Mode

1. Download the module .zip file [here](https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/releases).
2. After downloading, extract the .zip and locate the lknhooknotification folder at: notifications/modules/addons/lknhooknotification.
3. Compress only the lknhooknotification folder into a new .zip by right-clicking and then clicking "Compress".
4. Access cPanel, then go to the File Manager and enter your WHMCS folder: whmcs/modules/addons/.
5. Upload the .zip of the lknhooknotification folder and, after uploading, extract the contents and remove the remaining .zip.
6. Go to the WHMCS admin panel, select Options > Addon Modules, find the module and, once found, click "Activate".

## Update Mode

1. Before starting, save a copy of your site files and database — this ensures safety in case something goes wrong.
2. Access the WHMCS admin panel and deactivate the module.
3. Go to this [link](https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/releases) and download the .zip file of the latest module release.
4. In the cPanel File Manager, go to modules/addons and delete the old lknhooknotification folder.
5. Follow the same installation steps starting from step 3 (compress the new folder, upload, extract, and activate).

## Usage and Configuration

1. Go to the WHMCS addon modules list page.
2. Find and activate the "WhatsApp and Chatwoot" module.
3. Grant access control to the groups that should have permission to configure the module.
4. Go to the "WhatsApp and Chatwoot Notification" page under the "Addon" menu in WHMCS.
5. Fill in the necessary information for the basic operation of the module:
   1. Add your credentials for the WhatsApp and Chatwoot APIs.
   2. Configure the notifications.

## Documentation

For types documentation, follow https://phpstan.org/writing-php-code/phpdoc-types.

### New Version Release Process

1. Generate stubs
2. Update the hardcoded current version
3. Generate documentation

### Manual Stub Generation

You need to run php stubs_generator.php to generate the code stubs, then copy the contents of `stubs.php` and paste them into the [stubs.php](https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/blob/refactor-3.0.0/stubs.php) file in the notifications repository.

Additionally, the dependency used to generate stubs does not support Enums, so it's necessary to manually copy the contents of the files under the /Config directory and paste them into `stubs.php`.

### Documentation Generation

By running the command below, both internal and public documentation will be generated:

`php phpDocumentor.phar --config=phpdoc.private.xml && php phpDocumentor.phar --config=phpdoc.public.xml`

You need to push the /docs/public folder to the notifications repository on the main branch.

To view the internal documentation page, simply open the index.html file in /docs/public in your browser.

## Documentation for Development

- WHMCS Addon Modules: https://developers.whmcs.com/addon-modules/
- WHMCS Hook Index: https://developers.whmcs.com/hooks/hook-index/
- Bootstrap 3.4: https://getbootstrap.com/docs/3.4/components/
- Bootstrap 3.4: https://getbootstrap.com/docs/3.4/css/
- Bootstrap 3.4: https://getbootstrap.com/docs/3.4/javascript/
- FontAwesome 5: https://fontawesome.com/v5/search

### Integrations with the Platforms

#### WhatsApp API

- [Graph API WhatsApp](https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates)
- [Understanding the MessageTemplateParser class](https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages)
- [Understanding the Request to the WhatsApp API](https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages)
- [Send message template](https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-message-templates#text-based)
- [List message template](https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/#retrieve-templates)

#### Chatwoot

**How to test the integration**

- Create a free test account here. Look: https://www.chatwoot.com/docs/user-guide/setup-your-account/create-an-account/#i-am-using-the-cloud-version
- https://www.chatwoot.com/docs/product/channels/api/send-messages/
- About the Chatwoot APIs: https://www.chatwoot.com/docs/contributing-guide/chatwoot-apis

The one used by the module to send messages is the Application API.

##### Live Chat

- https://www.chatwoot.com/developers/api/#tag/Custom-Attributes/operation/add-new-custom-attribute-to-account
- https://www.chatwoot.com/hc/user-guide/articles/1677580558-website-live-chat-settings-explained
- https://www.chatwoot.com/hc/user-guide/articles/1677502327-how-to-create-and-use-custom-attributes

### Notification Types

#### Automatic Trigger

These are the notifications that are automatically executed along with a WHMCS hook.

#### Manual Trigger

Manual trigger notifications require an administrator action to be executed.
They typically use a WHMCS hook ending in Output to display a button that, when clicked, triggers the notification.

In this case, the module simply provides a way to integrate notifications that use the same output hook.
Currently, there are only two notifications of this type: the InvoiceReminder notifications, which use the AdminInvoicesControlsOutput hook.

#### Custom Notifications

They are placed here: `/modules/addons/lknhooknotification/src/Notifications/Custom`

#### Notificações que rodam nos hooks de cron

They inherit the class. `AbstractCronNotification`.

## Testing the Integrations

### Evolution API

[Evolution API Docs](https://doc.evolution-api.com/v2/api-reference/get-information)

To test, just set up locally and use ngrok:

```bash
docker run --net=host -it -e NGROK_AUTHTOKEN={NGROK_AUTHTOKEN} ngrok/ngrok:latest http 8080
```

Copy the given URL and place it in the Evolution API configuration in the module.

### Baileys API

- https://github.com/WhiskeySockets/Baileys
- https://baileys.wiki/docs/intro/

To test, just set up locally and use ngrok:

```bash
docker run --net=host -it -e NGROK_AUTHTOKEN={NGROK_AUTHTOKEN} ngrok/ngrok:latest http 8080
```

Copy the given URL and place it in the Baileys API in the module.

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
