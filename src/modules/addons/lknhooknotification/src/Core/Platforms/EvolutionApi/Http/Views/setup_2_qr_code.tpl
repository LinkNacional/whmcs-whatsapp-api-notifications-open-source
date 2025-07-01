<div class="row">
    <div class="col-sm-12 text-center">
        <h2>
            <i class="fas fa-caret-right"></i>
            {lkn_hn_lang text='Please, scan the QR Code below with your WhatsApp app.'}
        </h2>
    </div>
    <div class="col-sm-12">
        <img
            class="center-block"
            src="{$page_params.qr_code_base64}"
            width="300px"
            height="300px"
        >
    </div>

    <div class="col-sm-12">
        <br>
        <br>
        <br>
    </div>

    <div class="col-sm-12 text-center">
        <button
            type="submit"
            class="btn btn-success"
            onclick="window.location.reload()"
        >
            {lkn_hn_lang text='I already scanned!'}
        </button>
    </div>
</div>
