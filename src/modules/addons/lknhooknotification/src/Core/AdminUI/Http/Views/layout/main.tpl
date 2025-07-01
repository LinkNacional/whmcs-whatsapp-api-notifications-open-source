{* https://getbootstrap.com/docs/3.4/css/#grid-example-basic *}
<style>
    #whmcsdevbanner {
        display: none !important;
    }

    #lkn-hn-layout-page-content {
        min-height: 50vh;
    }
</style>

<div class="row">
    <div class="col-md-12">
        {include "{$lkn_hn_layout_path}/components/alert.tpl"}
    </div>
</div>

{if !$is_homepage}
    <div class="row">
        <div
            class="col-md-6"
            style="display: flex; align-items: baseline;"
        >
            <h1 style="margin-bottom: 0px; letter-spacing: 0px;">{block "page_title"}{/block}</h1>
            {block "title_right_side"}{/block}
        </div>

        <div class="col-md-6">
            {block "title_right_side_float"}{/block}
        </div>
    </div>

    <hr>
{/if}

<div
    id="lkn-hn-layout-page-content"
    class="row"
>
    <div class="col-md-12">
        {block "page_content"}{/block}
    </div>
</div>
