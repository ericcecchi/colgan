    <!--BEGIN: footer -->
	<!--<div id="main_content_footer">
    	<div id="content">
        	<div class="column3 subcontent">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer") ) : ?>
			<?php endif; ?>
			</div>
		</div>
	</div>-->
	<div id="footer">		
		<p><span>Disclaimer: futures and commodities trading  involves significant risk and is not suitable for every investor. This information is strictly the opinion of its author and is intended for informational purposes and is not to be construed as an offer to sell or a solicitation to buy or trade in any commodity or security mentioned herein. Information is obtained from sources believed reliable, but is in no way guaranteed. The author may have positions in the market mentioned including at times positions contrary to the advice quoted herein. Opinions, market data and recommendations are subject to change at any time. Past results are not indicative of future results.</span></p>
		
	</div>
    <!--END: footer -->
  </div>
  <!--END: wrap -->
</div>
<!--END: page -->

<?
	GLOBAL $shortname;
	$analytics_code = get_option($shortname.'_analytics_code');
	if ($analytics_code){
?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));

</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("<? echo $analytics_code; ?>");
pageTracker._trackPageview();
</script>

<?
	}
?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	$("a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
});
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1537569-58']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript">
window.onload=function(){
  var key = "47c42f17-d8d3-4b32-9115-e48177699dba";
  var host = "http://logs.loggly.com";
  castor = new loggly({ url: host+'/inputs/'+key+'?rt=1', level: 'log'});
  castor.log("url="+window.location.href + " browser=" + castor.user_agent + " height=" + castor.browser_size.height);
}
</script>
<script type="text/javascript" src="http://d3eyf2cx8mbems.cloudfront.net/js/loggly-0.1.0.js"></script>
</body>
</html>
