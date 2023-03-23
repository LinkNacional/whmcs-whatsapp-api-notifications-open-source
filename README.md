# WHMCS WhatsApp API Notifications: custom hooks

This is a repository didicated to developers who want to collaborate in the growth of the module.

You can fork this repository, build your custom hooks and then open a pull request to the `dev` branch. We will evaluate your code, ensure it runs on the latest version of the module and add it to the `main` branch for others to use your features.

If your feature is requested by many users, it can be adapted and turned into native, inside the module.

## Rules and recommendations
- Code style **must** follow the PSR-12. You can achieve this by using the `.php-cs-fixer.php` file.
- We encourage contributors to write commits, code comments, issues, and other documentation in English if possible.
- Compatibility with PHP 8.1 and with the latest versin of WHMCS.

## How to contribute
- Fork this repository.
- Clone your forked repository on your machine.
- To know how to create a custom hook, read the template files: [this for Chatwoot hooks](src/modules/addons/lknhooknotification/src/Custom/Platforms/Chatwoot/Hooks/OrderPaid.example.php) and [this for WhatsApp hooks](src/modules/addons/lknhooknotification/src/Custom/Platforms/WhatsApp/Hooks/OrderPaid.example.php).

These two template files have comments that will teach you how to create a custom hook for your use case.

Please, read them carefully.

- Once you're done, open a pull request to this repository targeting the `dev` branch.

You can find useful VS Code extensions in [extensions.json](.vscode/extensions.json).

For an improved development experience, we recommend using the following VS Code extensions:
- [Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
- [php cs fixer](https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer)

These extensions will optimize your workflow and help ensure your code follows our recommended style.
