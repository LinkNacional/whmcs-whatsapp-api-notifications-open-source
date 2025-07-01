<div class="row">
    <div class="col-sm-12 text-center">
        <div
            class="alert alert-danger"
            role="alert"
        >
            <div
                id="lkn-hn-alert"
                class="alert alert-danger alert-dismissible"
                role="alert"
            >
                <i class="fas fa-exclamation-square"></i>
                {lkn_hn_lang text="An error occurred."}
                <pre>{$page_params.error}</pre>
            </div>

            {if !empty($page_params.link)}
                <a
                    target="_blank"
                    href="{$page_params.link['url']}"
                >{$page_params.link['label']}</a>
            {/if}
        </div>
    </div>
</div>
