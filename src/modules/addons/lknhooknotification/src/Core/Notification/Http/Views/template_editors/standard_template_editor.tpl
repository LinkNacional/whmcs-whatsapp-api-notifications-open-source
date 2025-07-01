<div class="form-group">
    <div class="col-sm-12">
        <label class="control-label">
            <h2>{lkn_hn_lang text='Available Parameters'}</h2>
        </label>
    </div>
    <div class="col-sm-12">
        {foreach from=$page_params.editing_notification->parameters->params item=$param}
            <li
                class="clickable-param list-group-item"
                style="padding: 5px 10px; cursor: pointer;"
            >
                <i class="far fa-hand-pointer"></i>
                {literal}{{{/literal}{lkn_hn_lang text={$param->code}}{literal}}}{/literal}
            </li>
        {/foreach}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label
            for="template"
            class="control-label"
        >
            <h2>{lkn_hn_lang text='Template'}</h2>
        </label>
    </div>
    <div class="col-sm-12">
        <textarea
            name="template"
            id="template"
            class="notif-body-input"
            required
            style="border-radius: 12px; padding: 12px; border: 1px solid lightgray; width: 100%; height: 300px; overflow-y: auto; max-height: 300px; resize: none;"
            placeholder="{lkn_hn_lang text='Type here...'}"
        >{if $page_params.editing_template}{$page_params.editing_template->template}{/if}</textarea>
    </div>
</div>

<script type="text/javascript">
    const notifBodyInput = document.querySelector('.notif-body-input')


    document.querySelectorAll(".clickable-param").forEach(element => {
        element.addEventListener("click", function(event) {
            const textToInsert = event.target.textContent.trim() + ' ';

            const startPos = notifBodyInput.selectionStart;
            const endPos = notifBodyInput.selectionEnd;

            notifBodyInput.value = notifBodyInput.value.substring(0, startPos) +
                textToInsert +
                notifBodyInput.value.substring(endPos);

            notifBodyInput.focus();
            notifBodyInput.selectionStart = notifBodyInput.selectionEnd = startPos + textToInsert
                .length;
        });
    });
</script>
