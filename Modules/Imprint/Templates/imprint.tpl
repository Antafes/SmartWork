{extends file="base.tpl"}
{block name="body"}
<div id="imprint">
    <div class="row">
        <h2>{$translator->gt('imprint')}</h2>
        {foreach from=$imprints item="imprint"}
            <div class="imprintBlock">
                <p>
                    {$imprint.name}<br>
                    {$imprint.street} {$imprint.number}<br>
                    {$imprint.zip} {$imprint.city}
                </p>
                <p>
                    <a href="mailto:{$imprint.email|escape: 'hex'}">{$translator->gt('email')}</a>
                </p>
            </div>
        {/foreach}
    </div>
</div>
{/block}
