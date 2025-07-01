<div class="panel panel-default">
    <div
        class="panel-heading"
        role="tab"
        id="headingFour"
    >
        <h4 class="panel-title">
            <a
                class="collapsed"
                role="button"
                data-toggle="collapse"
                data-parent="#accordion"
                href="#collapse4"
                aria-expanded="false"
                aria-controls="collapse4"
            >
                {lkn_hn_lang text="Progress report"}
            </a>
        </h4>
    </div>
    <div
        id="collapse4"
        class="panel-collapse collapse in"
        role="tabpanel"
        aria-labelledby="headingFour"
    >
        <div
            class="panel-body"
            style="padding: 0px;"
        >
            <div class="table-responsive">
                <table
                    class="table table-hover table-condensed"
                    style="margin-bottom: 0px;"
                >
                    <thead>
                        <tr>
                            <th>{lkn_hn_lang text="Status"}</th>
                            <th>{lkn_hn_lang text="Message"}</th>
                            <th>{lkn_hn_lang text="Client"}</th>
                            <th>{lkn_hn_lang text="Target"}</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        {foreach from=$page_params.bulk_notifications_list item=$queued}
                            <tr style="position: relative;">
                                <td scope="row">
                                    <span class="label
                                        {if $queued->status->value === 'error'}
                                            label-danger
                                        {elseif $queued->status->value === 'aborted'}
                                            label-warning
                                        {elseif $queued->status->value === 'waiting'}
                                            label-primary
                                        {else}
                                            label-success
                                        {/if}
                                    ">
                                        {lkn_hn_lang text="{$queued->status->label()}"}
                                    </span>
                                </td>
                                <td>
                                    {if $queued->reportData['msg']}
                                        {lkn_hn_lang text="{$queued->reportData['msg']}"}
                                    {else}
                                        -
                                    {/if}
                                </td>
                                <td>
                                    <a
                                        href="clientssummary.php?userid={$queued->clientId}"
                                        target="_blank"
                                    >
                                        #{$queued->clientId} - {$queued->clientData['full_name']}
                                    </a>
                                </td>
                                <td>
                                    {if $queued->reportData['target']}
                                        {$queued->reportData['target']}
                                    {else}
                                        -
                                    {/if}
                                </td>
                                <td>
                                    {if $queued->status->value === 'error' && $page_params.bulk->status->value === 'in_progress'}
                                        <button
                                            type="submit"
                                            name="resend-notification"
                                            class="btn btn-primary btn-sm"
                                            value="{$queued->id}"
                                        >
                                            {lkn_hn_lang text="Resend"}
                                        </button>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
