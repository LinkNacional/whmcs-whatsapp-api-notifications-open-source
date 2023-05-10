<?php

namespace Lkn\HookNotification\Custom\Platforms\WhatsApp\Hooks;

use Lkn\HookNotification\Domains\Platforms\WhatsApp\Abstracts\WhatsappHookFile;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\Events\ChatwootSendMessageAsPrivate;

final class OrderPending3days extends WhatsappHookFile
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
        $this->setCustomParser(function ($paramLabel) use ($hookData): mixed {
            return match ($paramLabel) {
                'client_first_name' => $this->getClientFirstName($hookData->clientId ?? $hookData->id),
                'client_full_name' => $this->getClientFullName($hookData->clientId ?? $hookData->id),
                'client_first_two_names' => $this->getClientFirstTwoName($hookData->clientId ?? $hookData->id),
                'invoice_id' => (string) ($hookData->invoiceId ?? $hookData->id),
                'invoice_due_date' => $hookData->dueDate ?? $this->getInvoiceDueDate($hookData->invoiceId ?? $hookData->id),
                'order_items_descrip' => $hookData->lineItems ?? $this->getOrderItemsDescrip($hookData->orderId ?? $hookData->id),
                'invoice_pdf_url' => $hookData->pdfUrl ?? $this->getInvoicePDFURL($hookData->invoiceId),
                'order_id' => $hookData->orderId,
                'order_id_and_product' => $hookData->orderIdAndProduct,
                'invoice_id_invoice_first_item' => $hookData->invoiceIdAndFirstItem,
            };
        });

        $targetPhone = self::getWhatsAppNumberForClient($hookData->clientId);

        $templateData = $this->getTemplateForHook('OrderPending3days');

        $templateName = $templateData['template'];

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

        if ($response['success']) {
            (new ChatwootSendMessageAsPrivate())->run(
                $hookData->clientId,
                'Mensagem sobre pedido #' . $hookData->orderId . ' pendente há 3 dias foi enviada para este cliente.'
            );
        }

        return true;
    }
}
