{extends "layout/layout.tpl"}

{block "page_title"}
    {lkn_hn_lang text="Changelog"}
{/block}

{block "page_content"}
    <div style="max-width: 720px; margin: 0 auto 0;">
        {foreach from=$page_params.changelog item=entry}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{$entry.version}</strong> - <em>{$entry.date}</em>
                </div>
                <div class="panel-body">
                    <ul>
                        {foreach from=$entry.changes item=change}
                            <li>{$change}</li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        {/foreach}
    </div>
{/block}
