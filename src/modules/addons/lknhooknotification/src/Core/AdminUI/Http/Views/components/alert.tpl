{* https://getbootstrap.com/docs/3.4/javascript/#alerts *}
{if isset($page_alerts) && count($page_alerts) > 0}
    {foreach from=$page_alerts item=$page_alert}
        <div
            id="lkn-hn-alert"
            class="alert alert-{$page_alert.type} alert-dismissible"
            role="alert"
            style="margin: 0px; margin-top: 10px; margin-bottom: 30px;"
        >
            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>
            {$page_alert.msg}

            {if $page_alert.error}
                <div
                    id="lkn-hn-alert"
                    class="alert alert-danger alert-dismissible"
                    role="alert"
                >
                    <i class="fas fa-exclamation-square"></i>
                    <pre>{$page_alert.error}</pre>
                </div>
            {/if}
        </div>
    {/foreach}
{/if}
