<!DOCTYPE html>
<html lang="{$languageCode}">
    <head>
        {block name="title"}
        <title>{$translator->gt('title')}</title>
        {/block}
        {block name="meta"}
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        {/block}
        <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
        <script type="text/javascript">
            window.translations = {$translations};
        </script>
        {include_css}
        {include_js}
    </head>
    <body>
        {block name="head"}
        <div id="logo">
            <img src="images/logo.png" alt="Logo" />
            <strong>{$translator->gt('title')}</strong>
        </div>
        <div id="head">
            {include file='menu.tpl' pages=$pages}
            <div class="clear"></div>
        </div>
        {/block}
        {block name="body"}{/block}
        {block name="footer"}{/block}
    </body>
</html>
