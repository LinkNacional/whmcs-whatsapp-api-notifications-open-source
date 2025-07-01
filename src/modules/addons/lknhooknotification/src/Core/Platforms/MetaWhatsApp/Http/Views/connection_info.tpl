<div class="row">
    <div class="col-sm-12 text-center">
        {if $page_params.step === 1}
            <h1>
                {lkn_hn_lang text="Please, fill the setting on the side."}
            </h1>
        {elseif $page_params.step === 'error'}
            <div
                id="lkn-hn-alert"
                class="alert alert-danger alert-dismissible"
                role="alert"
            >
                {lkn_hn_lang text="An error occurred."}
                <pre>{$page_params.error}</pre>
            </div>
        {else}
            <h1>
                <i
                    class="fas fa-check-square"
                    style='color: #2A9E2A;'
                ></i>
                {lkn_hn_lang text="Connected to [1]" params=[{$page_params.connected_to_name}]}
            </h1>
            <p><a
                    href="?module=lknhooknotification&page=notifications/InvoiceReminder/templates/new"
                    target="_blank"
                > {lkn_hn_lang text="Notification Settings " params=[{$page_params.connected_to_name}]}<i
                        class="fas fa-external-link-alt"
                    ></i></a></p>
        {/if}
    </div>
</div>