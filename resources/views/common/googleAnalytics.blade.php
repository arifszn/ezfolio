@if (!empty($portfolioConfig['googleAnalyticsId']) && $portfolioConfig['googleAnalyticsId'] != '')
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src={{"https://www.googletagmanager.com/gtag/js?id=".$portfolioConfig['googleAnalyticsId']}}></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{$portfolioConfig["googleAnalyticsId"]}}');
</script>
@endif