<?php echo '<?xml version="1.0" encoding="ISO-8859-1" ?>'; ?>

<!-- RSS generated by PHPBoost on {DATE} -->

<rss version="2.0">
	<channel>
		<title>{TITLE_RSS}</title>
		<link>{HOST}</link>
		<description>{DESC}</description>
		<copyright>&copy; 2005-2008 PHPBoost</copyright>
		<language>{LANG}</language>
		<generator>PHPBoost</generator>

		# START rss #
		<item>
			<title>{rss.TITLE}</title>
			<link>{rss.LINK}</link>
			<description>{rss.DESC}</description>
			<pubDate>{rss.DATE}</pubDate>
		</item>		
		# END rss #
		
	</channel> 
</rss>