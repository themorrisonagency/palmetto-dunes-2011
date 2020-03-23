<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">	
    <xsl:output method="html"/>
	<xsl:template match="/content">
	
		<html>
		<head>
			<xsl:value-of select="//output/content/meta" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
			<meta charset="utf-8" />
			<style type="text/css" media="screen">@import "{caliban:system:activeTemplatePathRel}css/smartphone.css";</style>
			<xsl:call-template name="js" />
			<script type="text/javascript" src="{caliban:system:activeTemplatePathRel}js/jquery.js"></script>
			<script type="text/javascript" src="{caliban:system:activeTemplatePathRel}js/main.js"></script>
			<script type="text/javascript" src="{caliban:system:activeTemplatePathRel}js/jquery.cycle.js"></script>
			<script type="text/javascript">
				var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
				document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
            </script>
            <link rel="apple-touch-icon" href="{caliban:system:activeTemplatePathRel}images/icon.png"/>
		</head>
		<body>
		
			<div id="masthead">
				<a href="{caliban:system:APP_WEB}{caliban:system:pathSiteRoot}index.php">
					<img src="{caliban:system:activeTemplatePathRel}images/smartphone/masthead/masthead.jpg" alt="Return to Home" border="0"/>
				</a>
			</div>

			<nav class="toolbar">
				<a class="back" href="{caliban:system:APP_WEB}{caliban:system:pathSiteRoot}index.php/nav">Menu</a>
			</nav>

			<section class="wrapper">
				<article id="events" class="page current" style="top: 0px;">
					<header>
						<h1>{caliban:system:activeHeader}</h1>
					</header>
					<xsl:apply-templates select="module/events" />
					<nav class="nav-utility">
						<xsl:apply-templates select="nav" />
					</nav>		
				</article>
                <xsl:call-tempalte name="footer" />
			</section>	
			<script type="text/javascript">
				function showHide(obj){
					if(obj.innerHTML=='view details'){
						obj.innerHTML = 'hide details';
						obj.parentNode.parentNode.getElementsByTagName('div')[0].style.display='block';
					}else{
						obj.innerHTML = 'view details';		
						obj.parentNode.parentNode.getElementsByTagName('div')[0].style.display='none';					
					}
				}

				$('#eventscalendar').cycle({ 
					fx:     'scrollHorz', 
					speed:  500, 
					timeout: 0, 
					next:   '#next', 
					prev:   '#prev' ,
					nowrap: 1,
					after: function(){
						document.getElementsByTagName('monthname')[0].innerHTML = this.title;
					},
					prevNextClick: function (isNext, zeroBasedSlideIndex, slideElement) {
						numItems = document.getElementById('eventscalendar').getElementsByTagName('article').length-1;
						document.getElementById('prev').className = (zeroBasedSlideIndex==0 ? 'off' : 'on');
						document.getElementById('next').className = (zeroBasedSlideIndex==numItems ? 'off' : 'rotate on');
					}
				});	

			</script>
		</body>
		</html>

	</xsl:template>				
	{caliban:import(activeTemplatePathAbs,template-library.xsl)}
	{caliban:import(activeTemplatePathAbs,js-header.xsl)}			
</xsl:stylesheet>	
