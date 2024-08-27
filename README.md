# WHMCS WhatsApp API Notifications: custom hooks

This is a repository dedicated to developers who want to collaborate in the growth of the [WHMCS WhatsApp Cloud API module - Download WHMCS Addon](https://cliente.linknacional.com.br/dl.php?type=d&id=34), [See more information about the module:](https://www.linknacional.com/whmcs/whatsapp/) 

[Informações em Português:](https://www.linknacional.com.br/whmcs/whatsapp/).

You can fork this repository, build your custom hooks, and then open a pull request to the dev branch. We will evaluate your code, ensure it runs on the latest version of the module, and add it to the main branch for others to use your features.

If your feature is requested by many users, it can be adapted and integrated natively within the module.

## Rules and recommendations
- Code style **must** follow PSR-12. You can achieve this by using the `.php-cs-fixer.php` file.
- We encourage contributors to write commits, code comments, issues, and other documentation in English, if possible.
- The code should be compatible with PHP 8.1 and the latest version of WHMCS.

## How to contribute
Take a look on the wiki about [How to create a customized hook](https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-custom/wiki/How-to-create-a-customized-hook).

## Development environment


For fast setup, we strongly recommend the use of Docker, VS Code and Dev Container.
The settings and extensions are defined on [.devcontainer/devcontainer.json](.devcontainer/devcontainer.json) you do not need to edit it.

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop/).
2. Install [VS Code](https://code.visualstudio.com/download).
3. Install the [Dev Container extensions](https://www.docker.com/products/docker-desktop/) for VS Code.
4. Clone this repository and open it on VS Code.
5. Make sure Docker is running.
6. Then, press CTRL + Shift + P and type "Rebuild Without Cache" and press enter for Dev Container.
7. Now, VS Code will automatically setup PHP 8.1 and the necessary extensions and you can start coding.

Optionally, you can setup [SFTP](https://marketplace.visualstudio.com/items?itemName=Natizyskunk.sftp) for uploading a file to your WHMCS when you make changes to it. Take a look at [.vscode/sftp.example.json](.vscode/sftp.example.json).

Now, you must test your implementation and submit a pull request to the `dev` branch.

Keep in mind that on free plan you will be able to have only 3 custom hook files. Otherwise, the module will not run any hook.
