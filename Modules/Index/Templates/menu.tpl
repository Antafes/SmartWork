<div id="menu">
    {foreach from=$pages item="page"}
        <a class="button{if $page.active} active{/if}" href="index.php?page={$page.page}">{$translator->gt($page.key)}</a>
    {/foreach}
    {if $useLanguages}
        <form method="get" id="languages">
            {foreach from=$getParameters item="value" key="key"}
                <input type="hidden" name="{$key}" value="{$value}" />
            {/foreach}
            <select name="language" onchange="$('#languages').submit();">
                {foreach from=$languages item="language"}
                    <option value="{$language->getLanguageId()}"{if $translator->getCurrentLanguage() == $language->getLanguageId()} selected=""{/if}>{$translator->gt($language->getLanguage())}</option>
                {/foreach}
            </select>
        </form>
    {/if}
    <div class="clear"></div>
</div>
