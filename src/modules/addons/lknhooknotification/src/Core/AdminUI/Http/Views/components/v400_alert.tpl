<div class="col-md-12">
    <div
        class="alert"
        role="alert"
        style="border: 1px solid lightgray; box-shadow: 5px 5px 0px #c4cc00;"
    >
        <a
            class="close"
            href="?module=lknhooknotification&page=home&dimisv400-alert=1"
        >
            <span aria-hidden="true">×</span>
        </a>

        <h1 style="font-weight: bold;">{lkn_hn_lang text="Welcome to the version 4.0.0"}</h1>

        <p>
            {lkn_hn_lang text="We made a large effort to make sure all your current settings where migrated to this new version."}
        </p>

        <p>
            {lkn_hn_lang text="To confirm that everything was done successfully we ask you to take a few steps:"}
        </p>

        <br>

        <ul>
            <li>{lkn_hn_lang text="Review every module settings, such as platform credentials."}</li>
            <li>{lkn_hn_lang text="Review every active notification."}</li>
            <li class="text-danger">
                {lkn_hn_lang text="If you have custom notifications, you must refactor your implementation following the new standard:"}
                <a
                    href="https://github.com/LinkNacional/whmcs-whatsapp-api-notifications-open-source/wiki/How-to-create-a-notification-by-yourself"
                    target="_blank"
                >
                    {lkn_hn_lang text="Click here to view the new way for creating notifications."}
                </a>
            </li>
        </ul>

        <br>

        <h2>{lkn_hn_lang text="Main new features"}</h2>

        <ul>
            <li>
                <span class="label label-success">{lkn_hn_lang text="New"}</span>
                {lkn_hn_lang text="Improve notification view"}
            </li>
            <li>
                <span class="label label-success">{lkn_hn_lang text="New"}</span>
                {lkn_hn_lang text="Unified notification setup for Meta WhatsApp, Evolution API and Baileys"}
            </li>
            <li>
                <span class="label label-success">{lkn_hn_lang text="New"}</span>
                {lkn_hn_lang text="Improve notification sending reports."}
            </li>
            <li>
                <span class="label label-success">{lkn_hn_lang text="New"}</span>
                {lkn_hn_lang text="Bulk messaging"}
            </li>
            <li>
                <span class="label label-success">{lkn_hn_lang text="New"}</span>
                {lkn_hn_lang text="Multi-language support for notifications"}
            </li>
            <li>
                <span class="label label-success">{lkn_hn_lang text="New"}</span>
                {lkn_hn_lang text="Easier custom notification setup"}
            </li>
        </ul>
    </div>
</div>
