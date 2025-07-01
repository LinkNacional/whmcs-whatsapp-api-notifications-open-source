<div class="row">
    <div class="col-sm-12 text-center">
        <img
            src="{$page_params.profilePicUrl}"
            width="100px"
            height="100px"
            style="border-radius: 100px;"
        />
        <br>
        <br>
    </div>
    <div class="col-sm-12 text-center">
        <h1>
            <i class="fas fa-check-square"></i>
            {lkn_hn_lang text="Connected to +[1]" params=[{$page_params.connectedPhoneNumber}]}
        </h1>
    </div>
    <div class="col-sm-12 text-center">
        <button
            class="btn btn-danger btn-sm"
            type="submit"
            name="disconnect-instance"
            onclick="return confirmSubmit('{lkn_hn_lang text='Are you sure? Notifications assined to Evolution API will stop sending.'}')"
        >{lkn_hn_lang text="Disconnect instance"}</button>
    </div>
</div>