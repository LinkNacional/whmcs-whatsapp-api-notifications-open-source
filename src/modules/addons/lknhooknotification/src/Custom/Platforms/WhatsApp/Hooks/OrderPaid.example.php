<?php

namespace Lkn\HookNotification\Custom\Platforms\WhatsApp\Hooks;

use Lkn\HookNotification\Config\Hooks;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\Abstracts\WhatsappHookFile;

/**
 * In order to have access to the message template parser, you must inherit the class
 * Lkn\HookNotification\Domains\Platforms\WhatsApp\Abstracts\WhatsappHookFile
 */
final class OrderPaid extends WhatsappHookFile
{
    /**
     * @since 2.0.0
     *
     * @param \Lkn\HookNotification\Domains\Platform\Abstracts\HookDataParser $hookData
     *
     * @return bool
     */
    public function run($hookData): bool
    {
        // Note: on free plan you can have only 3 total hook files under /Custom/Platforms/*/Hooks.
        /**
         * If you do not have any custom things to do, you can call this method
         * and it will send the message template.
         */
        $this->sendMessageTemplate(Hooks::ORDER_PAID, $hookData);

        /**
         * For more custom things, you should read and use this.
         *
         * Message template parsing: how the module replaces {{1}} and {{2}} from
         * a message template with the real values.
         *
         * Each $hookData has a built-in parser that only supports the built-in hooks.
         * If you wish to add more param types, you can do so by adding them to the file
         * whatsapp_message_template_params_labels.php and set the parser of
         * this parameter using a custom parser as shown below.
         *
         * If you do not set the custom parser, the code you use the built-in parser and
         * it will throw a error in case your message template use a param that the parser cannot handle
         * (those that not came originally with the module).
         * So keep in mind to use the built-in parser only when you know it can fully support all params
         * in whatsapp_message_template_params_labels.php.
         */
        $this->setCustomParser(function ($paramLabel) use ($hookData): mixed {
            /**
             * Here, you can use the built-in methods to fetch the info you need
             * or make use of your own methods since you have access to $hookData
             * with essential data like an ID of an invoice or an ID of an order.
             *
             * Or you can access $this->hookData->raw, that holds hook data coming directly from WHMCS
             * and does not have any validation provided by the OrderFactory.
             *
             * This class is an example of an OrderPaid hook and the id of the order is $this->hookData->id
             * not $this->hookData->orderId since the main domain of this hook is the Order.
             *
             * If you would like to access the invoice related to the order, you must
             * access it by $this->hookData->invoiceId.
             */

            return match ($paramLabel) {
                'client_first_name' => $this->getClientFirstName($hookData->clientId ?? $hookData->id),
                'client_full_name' => $this->getClientFullName($hookData->clientId ?? $hookData->id),
                'client_first_two_names' => $this->getClientFirstTwoName($hookData->clientId ?? $hookData->id),
                'invoice_id' => (string) ($hookData->invoiceId ?? $hookData->id),
                'invoice_due_date' => $hookData->dueDate ?? $this->getInvoiceDueDate($hookData->invoiceId ?? $hookData->id),
                'order_items_descrip' => $hookData->lineItems ?? $this->getOrderItemsDescrip($hookData->orderId ?? $hookData->id),
                'invoice_pdf_url' => $hookData->pdfUrl ?? $this->getInvoicePDFURL($hookData->invoiceId),
            };
        });

        $targetPhone = self::getWhatsAppNumberForClient($hookData->clientId);
        /**
         * Getting the template associated to the OrderPaid hook. You defined this
         * in the "Adicionar associação" page.
         */
        $templateData = $this->getTemplateForHook(Hooks::INVOICE_REMINDER);

        /**
         * The name of the message template.
         */
        $templateName = $templateData['template'];

        /**
         * If you are not using a custom parser, you must pass $hookData as parameter.
         */
        $components = $this->parseMessageTemplateComponents($templateData['components'], $hookData);

        $requestBody = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $targetPhone,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'pt_BR'],
                'components' => $components
            ]
        ];

        $response = $this->apiRequest('POST', 'messages', $requestBody);

        /**
         * When you finish your implementation, you should see /Custom/hooks.php
         * and call this hook file there, using the Dispatcher.
         */

        return true;
    }
}
