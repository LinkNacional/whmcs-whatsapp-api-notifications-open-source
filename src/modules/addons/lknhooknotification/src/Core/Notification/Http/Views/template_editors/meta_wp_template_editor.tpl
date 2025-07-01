<input
    name="message-template-lang"
    type="hidden"
    value="{$page_params.editing_message_template['language']}"
>

<div class="form-group">
    <div class="col-sm-12">
        <label
            for="message-template"
            class="control-label"
        >
            <h2>{lkn_hn_lang text='Message Template'}</h2>
        </label>
    </div>

    <div class="col-sm-12">
        <select
            id="message-template"
            name="message-template"
            class="form-control"
            onchange="(document.getElementById('notification-form') ?? document.getElementById('lkn-hn-new-bulk-form')).submit()"
            {if $page_params.editing_message_template_name}
                readonly
            {/if}
        >
            <option value="">{lkn_hn_lang text="Select a platform"}</option>

            {foreach from=$page_params.message_templates_options item=$value key=$label}
                <option
                    value="{$value}"
                    {if $page_params.editing_message_template_name === $value}
                        selected
                    {/if}
                >
                    {$label}
                </option>
            {/foreach}
        </select>
    </div>

    {if $page_params.editing_message_template_name}
        <div class="col-sm-12">
            {if !$page_params.disable_template_editor_changes}

                <button
                    id="btn-enable-message-template-change"
                    type="button"
                    class="btn btn-link btn-sm"
                >
                    <i class="fas fa-exchange-alt"></i>
                    {lkn_hn_lang text="Change message template"}
                </button>
            {/if}

            <script type="text/javascript">
                const btnEnableMessageTemplateChange = document.getElementById('btn-enable-message-template-change')

                btnEnableMessageTemplateChange.addEventListener('click', () => {
                    btnEnableMessageTemplateChange.style.display = 'none'

                    const messageTemplateSelect = document.getElementById('message-template')

                    messageTemplateSelect.readonly = false
                    messageTemplateSelect.showPicker();
                })
            </script>
        </div>
    {/if}
</div>

{if $page_params.editing_message_template_name}
    <div class="form-group">
        <div class="col-sm-12">
            <div
                class="alert alert-info text-center"
                role="alert"
            >
                <i class="fas fa-caret-right"></i>
                {lkn_hn_lang text="Indicate for the notification what to put in the parameters of the message template created in Meta."}
            </div>
        </div>
    </div>
{else}
    <div
        class="alert alert-info"
        role="alert"
        style="margin-top: 40px;"
    >
        {lkn_hn_lang text="Please, choose a message template."}
    </div>

{/if}

<br>

<style>
    #lkn-hn-msg-tpl-select-cont select {
        border: 1px solid lightgray;
        border-radius: 6px;
    }
</style>

<div
    id="lkn-hn-msg-tpl-select-cont"
    class="form-group"
>
    <div class="col-sm-12">
        {foreach from=$page_params.editing_message_template['components'] item=$component}
            <div class="panel panel-default">
                <div class="panel-heading">
                    {lkn_hn_lang text=$component['type']}

                    {if !empty($component['format'])}
                        - {lkn_hn_lang text=$component['format']}
                    {/if}
                </div>

                <div
                    class="panel-body text-center"
                    style="display: flex; flex-direction: column; gap: 12px; align-items: center;"
                >
                    {if $component['type'] === 'HEADER'}

                        <input
                            type="hidden"
                            name="header-format"
                            value="{$component['format']}"
                        />

                        {if $page_params.editing_template_header_view === null}
                            {lkn_hn_lang text="This header type is not supported by the module."} ({$component['format']}).

                            <a
                                class="btn btn-link btn-sm"
                                href="https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/issues/new?template=feature_request.md"
                                target="_blank"
                            >
                                {lkn_hn_lang text="Request this feature"} <i class="far fa-external-link-alt"></i>
                            </a>
                        {else}
                            {$page_params.editing_template_header_view}
                        {/if}

                    {elseif $component['type'] === 'BODY'}

                        <p
                            class="text-left"
                            style="margin-bottom: 0px !important; line-height: 36px;"
                        >
                            {$page_params.editing_template_body_view}
                        </p>

                    {elseif $component['type'] === 'FOOTER'}

                        {$component['text']}

                    {elseif $component['type'] === 'BUTTONS'}

                        {$page_params.editing_template_buttons_view}

                    {/if}
                </div>
            </div>
        {/foreach}
    </div>
</div>
