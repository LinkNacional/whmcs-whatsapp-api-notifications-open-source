<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button
                type="button"
                class="navbar-toggle collapsed"
                data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1"
                aria-expanded="false"
            >
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a
                class="navbar-brand"
                href="https://www.linknacional.com.br/whmcs/"
                target="_blank"
                style="display: flex; font-size: 14px; color: gray; gap: 4px;"
            >
                <img
                    alt="Link Nacional"
                    title="Link Nacional"
                    style="height: 20px"
                    src="{$lkn_hn.system_url}/modules/addons/lknhooknotification/logo.png"
                >

            </a>
        </div>

        <div
            class="collapse navbar-collapse"
            id="bs-example-navbar-collapse-1"
        >
            <ul class="nav navbar-nav">
                {foreach from=$lkn_hn.navbar.left item=$navitem}
                    {if isset($navitem['show']) && !$navitem['show']}

                    {elseif isset($navitem['items'])}
                        <li class="dropdown">
                            <a
                                href="#"
                                class="dropdown-toggle"
                                data-toggle="dropdown"
                                role="button"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="{$navitem['icon']}"></i>
                                {$navitem['label']}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                {foreach from=$navitem['items'] item=$subitem key=$key}
                                    {if isset($subitem['divisor'])}
                                        {if $key !== 0}
                                            <li
                                                role="separator"
                                                class="divider"
                                            ></li>
                                        {/if}

                                        <li class="dropdown-header">
                                            {$subitem['title']}
                                        </li>
                                    {else}
                                        {if isset($subitem['block']) && $subitem['block']}
                                            <li>
                                                <a href="https://www.linknacional.com/whmcs/whatsapp/">
                                                    <i class="{$subitem['icon']}"></i>
                                                    {$subitem['label']}
                                                    <strong>{lkn_hn_lang text="(PRO)"}</strong>
                                                </a>
                                            </li>
                                        {else}
                                            <li
                                                {if $lkn_hn.current_endpoint === $subitem['endpoint']}
                                                    class="active"
                                                {/if}
                                            >
                                                <a href="?module=lknhooknotification&page={$subitem['endpoint']}">
                                                    <i class="{$subitem['icon']}"></i>
                                                    {$subitem['label']}
                                                </a>
                                            </li>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </ul>
                        </li>
                    {else}
                        <li
                            {if $lkn_hn.current_endpoint === $navitem['endpoint']}
                                class="active"
                            {/if}
                        >
                            <a href="?module=lknhooknotification&page={$navitem['endpoint']}">
                                <i class="{$navitem['icon']}"></i>
                                {$navitem['label']}
                            </a>
                        </li>
                    {/if}
                {/foreach}
            </ul>

            <ul class="nav navbar-nav navbar-right">
                {foreach from=$lkn_hn.navbar.right item=$navitem}
                    {if isset($navitem['items'])}
                        <li class="dropdown">
                            <a
                                href="#"
                                class="dropdown-toggle"
                                data-toggle="dropdown"
                                role="button"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="{$navitem['icon']}"></i>
                                {$navitem['label']}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                {foreach from=$navitem['items'] item=$subitem key=$key}
                                    {if isset($subitem['divisor'])}
                                        {if $key !== 0}
                                            <li
                                                role="separator"
                                                class="divider"
                                            ></li>
                                        {/if}

                                        <li class="dropdown-header">
                                            <i class="{$subitem['icon']}">
                                            </i> {$subitem['title']}
                                        </li>
                                    {else}
                                        <li>
                                            <a
                                                {if isset($subitem['endpoint'])}
                                                    href="?module=lknhooknotification&page={$subitem['endpoint']}"
                                                {else}
                                                    href="{$subitem['url']}"
                                                {/if}
                                            >
                                                <i class="{$subitem['icon']}"></i>
                                                {$subitem['label']}
                                            </a>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </li>
                    {else}
                        <li>
                            <a
                                {if isset($navitem['endpoint'])}
                                    href="?module=lknhooknotification&page={$navitem['endpoint']}"
                                {else}
                                    href="{$navitem['url']}"
                                {/if}
                                href="?module=lknhooknotification&page={$navitem['endpoint']}"
                            >
                                <i class="{$navitem['icon']}"></i>
                                {$navitem['label']}
                            </a>
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
</nav>