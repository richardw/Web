</div>
<footer>
<div id="f">
<ul>
<li><strong>The company</strong></li>
<li><a href="/about">About Blackroc Technology</a></li>
<li><a href="/news">News</a></li>
<li><a href="/about/privacy">Privacy policy</a></li>
<li><a href="/about/terms">Terms &amp; conditions</a></li>
<li><a href="/about/returns">Returns</a></li>
<li><a href="/about/sitemap">Site map</a></li>
<li><a href="/contact">Contact us</a></li>
</ul>
<div id="fr">
<ul id="social">
<li id="fb" class="spr"><a href="#"><span>Facebook</span></a></li>
<li id="tw" class="spr"><a href="http://www.twitter.com/blackroctech"><span>Twitter</span></a></li>
<li id="li" class="spr"><a href="#"><span>Linked in</span></a></li>
<li id="rss" class="spr"><a href="#"><span>RSS Feed</span></a></li>
</ul><br class="clear" />
&copy; <?php echo date('Y'); ?> Blackroc Technology. All rights reserved | <a href="#">Contact us</a>
</div>
<br class="clear" />
</div>

</footer>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>
var newsid=0;var t;function changeNewsImg(){$('#news ul li a:eq('+newsid+')').removeClass('cur');if(newsid==2)newsid=0;else newsid++;$('#news ul li a:eq('+newsid+')').addClass('cur');t=setTimeout("changeNewsImg();",7000)}

$(document).ready(function(){
  if ((!$.browser.msie) || (($.browser.msie) && ($.browser.version.substr(0,1)>6))) {
    $('#news ul li a:eq(0)').addClass('cur');t=setTimeout("changeNewsImg();",7000)
  }
});
</script>
</body>
</html>