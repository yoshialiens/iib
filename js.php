<script src="/common/js/jquery-1.11.1.min.js"></script>
<script src="/common/js/jquery.meanmenu.js"></script>
<script src="/common/js/smoothscroll.js"></script>
<script>
jQuery(document).ready(function () {
jQuery('header#header-sp').meanmenu();
});
</script>

<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: 'ja'}
</script>
<script type="text/javascript" src="https://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-43489254-1', 'i-i-b.jp');
ga('send', 'pageview');</script>

<script type="text/javascript">jQuery(function() {
    jQuery("a").click(function(e) {
        var ahref = jQuery(this).attr('href');
        if (ahref.indexOf("i-i-b.jp") != -1 || ahref.indexOf("http") == -1 ) {
            ga('send', 'event', '内部リンク', 'クリック', ahref);}
        else {
            ga('send', 'event', '外部リンク', 'クリック', ahref);}
        });
    });
</script>