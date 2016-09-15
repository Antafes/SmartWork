<!DOCTYPE html>
<html lang="{$languageCode}">
    <head>
        <title>{$translator->gt('title')}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
        <script type="text/javascript">
            window.translations = {$translations};
        </script>
        {include_css}
        {include_js}
    </head>
    <body>
        <div id="logo">
            <img src="images/logo.png" alt="Logo" />
            <strong>{$translator->gt('title')}</strong>
        </div>
        <div id="head">
            {include file='menu.tpl'}
            <div class="clear"></div>
        </div>