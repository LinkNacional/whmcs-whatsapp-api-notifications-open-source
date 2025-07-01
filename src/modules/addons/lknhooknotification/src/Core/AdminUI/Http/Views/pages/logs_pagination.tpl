<div>
    {assign "total_pages" value=ceil($page_params.total_reports / $page_params.reports_per_page)}
    {assign "page_link_tpl" value="?module=lknhooknotification&page=logs&filter={$page_params.filter}&pageN"}

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
