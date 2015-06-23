<script src="common/js/jquery-1.11.1.min.js"></script>
<script src="common/js/jquery.meanmenu.js"></script>
<script src="common/js/smoothscroll.js"></script>
<script>
jQuery(document).ready(function () {
jQuery('header#header-sp').meanmenu();
});
</script>
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