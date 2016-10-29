<div id="menu">
    {foreach from=$pages item="page"}
        <a class="button{if $page.active} active{/if}" href="index.php?page={$page.page}">{$translator->gt($page.key)}</a>
    {/foreach}
    <div class="clear"></div>
</div>
