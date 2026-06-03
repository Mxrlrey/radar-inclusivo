<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>

<style>
    [vw].enabled,
    [vw].enabled [vw-access-button],
    [vw].enabled [vw-plugin-wrapper] {
        z-index: 2147483647 !important;
    }
</style>

<script>
    window.initVLibras = function() {
        if (window.VLibras && !window.__vlibrasWidgetLoaded) {
            window.__vlibrasWidgetLoaded = true;
            new window.VLibras.Widget('https://vlibras.gov.br/app');
        }
    };
</script>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js" onload="window.initVLibras()"></script>
