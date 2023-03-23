{include file="../modal.tpl"}

<div
    class="container-fluid"
    style="margin-top: 10px;"
>
    <div style="display: flex; align-items: center; justify-content: center; padding: 5px;">
        <select
            {if !$mustShowInvoiceReminderPdf}
                disabled
            {/if}
            required
            name="hook"
            id="hook-select"
            class="custom-select form-control"
            style="border: none; height: 39px;"
        >
            <option
                selected
                value="InvoiceReminder"
            >Enviar lembrete</option>
            {if $mustShowInvoiceReminderPdf}
                <option value="InvoiceReminderPdf">Enviar lembrete com fatura em PDF</option>
            {/if}
        </select>
        <button
            type="button"
            class="button btn btn-success"
            id="lknNotifyByWhatsappBtn"
            style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;"
        >
            <img
                src="{$moduleUrl}/src/assets/whatsapp.svg"
                width="25px"
            >
            Enviar via Whatsapp
        </button>

        <input
            name="action"
            type="hidden"
            value="send"
        >
    </div>
</div>

<script type="text/javascript">
    const configs = {
        apiUrl: '{$moduleUrl}/api.php',
        data: {
            invoiceId: {$invoiceId},
            userId: {$userId},
            subtotal: {$subtotal},
            tax: {$tax},
            tax2: {$tax2},
            credit: {$credit},
            total: {$total},
            balance: {$balance},
            taxRate: {$taxRate},
            taxRate2: {$taxRate2},
            paymentMethod: '{$paymentMethod}'
        }
    }

    const btnNotifyByWhatsapp = document.getElementById('lknNotifyByWhatsappBtn')
    const hookSelect = document.getElementById('hook-select')

    btnNotifyByWhatsapp.addEventListener('mousedown', event => {
        const body = {
            a: 'send-whatsapp-invoice-reminder',
            data: {
                hook: hookSelect.value,
                invoiceId: configs.data.invoiceId,
                userId: configs.data.userId,
                subtotal: configs.data.subtotal,
                tax: configs.data.tax,
                tax2: configs.data.tax2,
                credit: configs.data.credit,
                total: configs.data.total,
                balance: configs.data.balance,
                taxRate: configs.data.taxRate,
                taxRate2: configs.data.taxRate2,
                paymentMethod: configs.data.paymentMethod
            }
        }

        btnNotifyByWhatsapp.disabled = true

        console.log('hey')

        fetch(configs.apiUrl, {
                method: 'POST',
                body: JSON.stringify(body)
            })
            .then(res => res.json())
            .then((res) => {
                if (res.success) {
                    // eslint-disable-next-line no-undef
                    LknHookNotModal
                        .setTitle('Mensagem enviada com sucesso')
                        .setSimpleBody('Mensagem enviada com sucesso.')
                        .show()
                } else {
                    // eslint-disable-next-line no-undef
                    LknHookNotModal
                        .withErrors('Não foi possível criar a associação', res.data.errors)
                        .show()
                }
            })
            .catch(err => {
                // eslint-disable-next-line no-undef
                LknHookNotModal
                    .setTitle('Não foi possível enviar a mensagem')
                    .setSimpleBody(
                        'Verifique se o número de WhatsApp do cliente possui o DDD e o DDI. Consulte o log de módulos para mais informações.'
                    )
                    .show()
            })
            .finally(() => {
                btnNotifyByWhatsapp.disabled = false
            })

    })
</script>
