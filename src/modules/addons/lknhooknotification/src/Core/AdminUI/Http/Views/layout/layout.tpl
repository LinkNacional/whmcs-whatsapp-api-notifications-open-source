<script type="text/javascript">
    function confirmSubmit(text) {
        return confirm(text);
    }
</script>

<style>
    #lkn-hn-layout {
        max-width: 1280px;
        margin: 0 auto 0;
    }

    #lkn-hn-layout * {
        text-rendering: optimizeLegibility;
    }

    #lkn-hn-layout p {
        margin-bottom: 0px;
    }

    #lkn-hn-layout>div>div>h1 {
        margin-bottom: 0px !important;
    }

    #lkn-hn-layout .popover-content {
        overflow: auto;
    }

    #lkn-hn-layout select:disabled {
        appearance: none;
    }

    #sidebar {
        display: none !important;
    }

    #contentarea {
        margin: 0px !important;
    }

    #contentarea>div>h1 {
        display: none !important;
    }

    #lkn-hn-alert {
        margin: 0px;
        margin-top: 10px;
        margin-bottom: 30px;
    }

    #lkn-hn-alert pre {
        margin: 20px 0px;
        background-color: transparent;
        border-color: #00000014;
    }

    .popover {
        max-width: none !important;
    }

    .popover-content,
    .popover-body {
        overflow: visible !important;
        padding: 10px;
        align-items: center;
        color: #737373;
        font-weight: 400;
        display: flex;
        flex-direction: column;
        gap: 30px;

    }

    .popover-content p,
    .popover-body p {
        text-align: start;
    }
</style>

<div id="lkn-hn-layout">
    {include "{$lkn_hn_layout_path}/layout/navbar.tpl"}

    <div class="container-fluid">
        {include "{$lkn_hn_layout_path}/layout/main.tpl"}
    </div>

    {include "{$lkn_hn_layout_path}/layout/footer.tpl"}
</div>