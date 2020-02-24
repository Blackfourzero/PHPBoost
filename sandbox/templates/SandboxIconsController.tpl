<style>
	.code {margin-bottom: 5px;}
	.fa:before {margin-right: 3px}
</style>

<section>
	# INCLUDE SANDBOX_SUB_MENU #
	<header>
		<h1>{@sandbox.module.title} - {@title.icons}</h1>
	</header>

	<article class="content">
		<header>
			<h2>{@font.icons.fa}</h2>
		</header>
			<h3>{@font.icons.sample}</h3>
		<div>
			<table class="table">
				<caption>{@font.icons.social}</caption>
				<thead>
					<tr>
						<th>{@font.icons.icon}</th>
						<th>{@font.icons.name}</th>
						<th>{@font.icons.code}</th>
					</tr>
				</thead>
				<tbody>
					# START social #
						<tr>
							<td><i class="{social.PREFIX} fa-{social.FA} fa-lg"></i></td>
							<td><span>{social.FA}</span></td>
							<td>{social.CODE}</td>
						</tr>
					# END web #
				</tbody>
			</table>

			<table class="table">
				<caption>{@font.icons.screen}</caption>
				<thead>
					<tr>
						<th>{@font.icons.icon}</th>
						<th>{@font.icons.name}</th>
						<th>{@font.icons.code}</th>
					</tr>
				</thead>
				<tbody>
					# START responsive #
					<tr>
						<td><i class="{responsive.PREFIX} fa-{responsive.FA} fa-lg"></i></td>
						<td><span>{responsive.FA}</span></td>
						<td>{responsive.CODE}</td>
					</tr>
					# END responsive #
				</tbody>
			</table>
		</div>
		<footer>{@font.icons.list}<a class="button alt-button" href="https://fontawesome.com/icons?d=listing&m=free">Cheatsheet Font-Awesome</a></footer>
	</article>

	<article>
		<header>
			<h3>{@font.icons.howto}</h3>
		</header>
		<div class="content">
			<p>{@font.icons.howto.explain}</p>
			<p>{@font.icons.howto.update}</p>

			<br />
			<h4>{@font.icons.howto.html}</h4>
			<p>{@font.icons.howto.html.class}</p>
			<pre class="language-html"><code class="language-html">&lt;i class="far fa-edit">&lt;/i> Edition</code></pre>
			<p>{@font.icons.howto.html.class.result.i}<i class="far fa-edit"></i> Edition</p>
			<pre class="language-html"><code class="language-html">&lt;a class="fa fa-globe" href="https://www.phpboost.com">PHPBoost&lt;/a></code></pre>
			<p>{@font.icons.howto.html.class.result.a}<a href="https://www.phpboost.com"><i class="fa fa-globe"></i>PHPBoost</a></p>
			<p>{@font.icons.howto.html.class.result.all}</p>

			<br />
			<h4>{@font.icons.howto.css}</h4>
			<p>{@font.icons.howto.css.class}</p>
			<span class="formatter-code">{@font.icons.howto.css.css.code}</span>
				<pre class="language-css line-numbers" data-line="3-4"><code class="language-css">.success { ... }
					.success::before {
					    content: "\f00c";
					    font-family: 'Font Awesome 5 Free';
					}</code></pre>
			<span class="formatter-code">{@font.icons.howto.css.html.code}</span>
			<div class="code">
				<pre class="language-html"><code class="language-html">&lt;div class="message-helper bgc success">{@css.message.success}&lt;/div></code></pre>
			</div>
			<div class="message-helper bgc success">{@css.message.success}</div>

			<br />
			<h4>{@font.icons.howto.bbcode}</h4>
			<p>{@font.icons.howto.bbcode.some.icons} <i class="fab fa-font-awesome-flag"></i></p>
			<p>{@font.icons.howto.bbcode.tag}</p>
			<p>{@font.icons.howto.bbcode.icon.name}</p>
			<p>{@H|font.icons.howto.bbcode.icon.test} <i class="fa fa-cubes"></i></p>
			<p>{@font.icons.howto.bbcode.icon.variants}</p>

			<br />
			<h4>{@font.icons.howto.variants}</h4>
			<p>{@font.icons.howto.variants.explain}</p>
			<p>{@font.icons.howto.variants.list}<a class="button alt-button" href="https://fortawesome.github.io/Font-Awesome/examples/">Font-Awesome/examples</a></p>

			<pre class="language-html"><code class="language-html">&lt;i class="fa fa-spinner fa-spin fa-2x">&lt;/i></code></pre>

			<p>{@font.icons.howto.variants.spinner}<i class="fa fa-spinner fa-spin fa-2x"></i></p>
		</div>
	</article>
	<footer></footer>
</section>
