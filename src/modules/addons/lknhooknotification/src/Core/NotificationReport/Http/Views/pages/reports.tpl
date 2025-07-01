{extends "{$lkn_hn_layout_path}/layout/layout.tpl"}

{block "page_title"}
    {lkn_hn_lang text="Notification Reports" params=[$page_params.platform_title]}
{/block}

{block "page_content"}
    <style>
        .report-link {
            padding: 0px;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{lkn_hn_lang text="Status"}</th>
                                <th>{lkn_hn_lang text="Message"}</th>
                                <th>{lkn_hn_lang text="Date"}</th>
                                <th>{lkn_hn_lang text="Platform"}</th>
                                <th>{lkn_hn_lang text="Notification"}</th>
                                <th>{lkn_hn_lang text="Client"}</th>
                                <th>{lkn_hn_lang text="Category"}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$page_params.reports item=$report}
                                <tr>
                                    <th scope="row">{$report->id}</th>

                                    <td>
                                        <span
                                            class="label label-{if $report->status->value === 'error'}danger{elseif $report->status->value === 'not_sent'}warning{else}success{/if}"
                                        >
                                            {$report->status->label()}
                                        </span>
                                    </td>
                                    <td style="max-width: 200px;">
                                        {if !empty($report->msg)}
                                            <p
                                                {if strlen($report->msg) > 30}
                                                    data-toggle="popover"
                                                    data-animation="false"
                                                    data-placement="right"
                                                    data-html="true"
                                                    {if $report->platform->value === 'wp' && $report->status->value === 'error'}
                                                        data-content="
                                                        {htmlspecialchars($report->msg)}
                                                        <br>
                                                        <a href='https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/#error-codes' target='_blank'>WhatsApp Cloud API Error Codes <i class='fas fa-external-link-alt'></i></a>"
                                                    {else}
                                                        data-content="{htmlspecialchars($report->msg)}"
                                                    {/if}
                                                    data-trigger="click hover"
                                                {/if}
                                                class="text-muted"
                                                style="margin-bottom: 0px !important; width: fit-content; cursor: pointer;"
                                            >
                                                {if strlen($report->msg) > 30}
                                                    <i class="fas fa-question-circle"></i>
                                                    {substr($report->msg, 0, 30)}...
                                                {else}
                                                    {lkn_hn_lang text="{$report->msg}"}
                                                {/if}
                                            </p>
                                        {/if}
                                    </td>
                                    <td>{$report->createdAt->format('Y-m-d H:i:s')}</td>
                                    <td>
                                        {if $report->platform}
                                            <a
                                                class="btn btn-link report-link"
                                                href="?module=lknhooknotification&page=platforms/{$report->platform->value}/settings"
                                            >
                                                {$report->platform->label()}
                                            </a>
                                        {/if}
                                    </td>
                                    <td>
                                        {if !$report->platform || $report->platform->value === 'cw'}
                                            {lkn_hn_lang text="{$report->notificationCode}"}
                                        {else}
                                            <a
                                                class="btn btn-link report-link"
                                                href="?module=lknhooknotification&page=notifications/{$report->notificationCode}/templates/first"
                                            >
                                                {lkn_hn_lang text="{$report->notificationCode}"}
                                            </a>
                                        {/if}
                                    </td>
                                    <td>
                                        {if $report->clientId}
                                            <a
                                                target="_blank"
                                                href="clientssummary.php?userid={$report->clientId}"
                                            >
                                                #{$report->clientId}
                                                {if $report->target}
                                                    at +{$report->target}
                                                {/if}
                                            </a>
                                        {/if}
                                    </td>
                                    <td>
                                        {if !empty($report->category) && !empty($report->categoryId)}
                                            {if $report->category->value === 'invoice'}
                                                {assign "category_link" "invoices.php?action=edit&id={$report->categoryId}"}
                                            {elseif $report->category->value === 'order'}
                                                {assign "category_link" "orders.php?action=view&id={$report->categoryId}"}
                                            {elseif $report->category->value === 'ticket'}
                                                {assign "category_link" "supporttickets.php?action=view&id={$report->categoryId}"}
                                            {elseif $report->category->value === 'domain'}
                                                {assign "category_link" "clientsdomains.php?userid={$report->clientId}&id={$report->categoryId}"}
                                            {/if}

                                            <a
                                                target="_blank"
                                                href="{$category_link}"
                                            >
                                                {$report->category->label()} #{$report->categoryId}
                                            </a>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {assign "total_pages" value=ceil($page_params.total_reports / $page_params.reports_per_page)}
                {assign "page_link_tpl" value='?module=lknhooknotification&page=notification-reports&pageN'}

                {if $total_pages > 1}
                    <nav
                        aria-label="Page navigation"
                        style="text-align: center;"
                    >
                        <ul class="pagination">
                            {if $page_params.current_page > 1}
                                <li>
                                    <a href="{$page_link_tpl}=1">
                                        {lkn_hn_lang text="First Page"}
                                    </a>
                                </li>
                            {/if}
                            <li
                                {if $page_params.current_page == 1}
                                    class="disabled"
                                {/if}
                            >
                                <a
                                    {if $page_params.current_page > 1}
                                        href="{$page_link_tpl}={$page_params.current_page - 1}"
                                    {/if}
                                    aria-label="Previous"
                                >
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            {if $total_pages >= 15}


                                {for $page=$page_params.current_page - 8 to $page_params.current_page}
                                    {if $page > 0}
                                        <li
                                            {if $page == $page_params.current_page}
                                                class="active"
                                            {/if}
                                        >
                                            <a href="{$page_link_tpl}={$page}">{$page}</a>
                                        </li>
                                    {/if}
                                {/for}

                                {for $page=$page_params.current_page + 1 to $page_params.current_page + 8}
                                    {if $page < $total_pages}
                                        <li
                                            {if $page == $page_params.current_page}
                                                class="active"
                                            {/if}
                                        >
                                            <a href="{$page_link_tpl}={$page}">{$page}</a>
                                        </li>
                                    {/if}
                                {/for}


                            {else}
                                {for $page=1 to $total_pages}
                                    <li
                                        {if $page == $page_params.current_page}
                                            class="active"
                                        {/if}
                                    ><a href="{$page_link_tpl}={$page}">{$page}</a></li>
                                {/for}
                            {/if}

                            <li
                                {if $page_params.current_page >= $total_pages}
                                    class="disabled"
                                {/if}
                            >
                                <a
                                    {if $page_params.current_page < $total_pages}
                                        href="{$page_link_tpl}={$page_params.current_page + 1}"
                                    {/if}
                                    aria-label="Next"
                                >
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>

                            {if $page_params.current_page <= $total_pages - 1}
                                <li>
                                    <a href="{$page_link_tpl}={$total_pages}">
                                        {lkn_hn_lang text="Last Page"}
                                    </a>
                                </li>
                            {/if}
                        </ul>
                    </nav>
                {/if}
            </div>
        </div>
    </div>
{/block}
