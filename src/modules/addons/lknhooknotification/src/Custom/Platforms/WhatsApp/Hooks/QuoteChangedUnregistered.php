<?php

namespace Lkn\HookNotification\Custom\Platforms\WhatsApp\Hooks;

use Lkn\HookNotification\Domains\Platforms\WhatsApp\Abstracts\WhatsappHookFile;
use Lkn\HookNotification\Domains\Platforms\WhatsApp\Events\ChatwootSendMessageAsPrivate;

final class QuoteChangedUnregistered extends WhatsappHookFile
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
                'quote_subject' => $hookData->quoteSubject,
                'quote_first_name' => $hookData->firstName,
                'quote_email' => $hookData->quoteEmail,
                'quote_id' => $hookData->quoteId,
            };
        });

        $targetPhone = $hookData->phoneNumber;

        $templateData = $this->getTemplateForHook('QuoteChangedUnregistered');

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

        if (isset($response['messages'][0]['id'])) {
            (new ChatwootSendMessageAsPrivate())->run(
                $hookData->firstName,
                'Mensagem sobre orçamento #' . $hookData->quoteId . ' alterado foi enviada para este cliente não registrado.'
            );
        }

        return true;
    }
}
