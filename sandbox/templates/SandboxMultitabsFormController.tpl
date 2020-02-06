<section id="sandbox-css">
	<header>
		<h1>
			{@sandbox.module.title} - {@title.multitabs}
		</h1>
	</header>
	<div class="sandbox-summary">
		<div class="close-summary" aria-label="${LangLoader::get_message('close_menu', 'admin')} {@sandbox.summary}">
			<i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
		</div>
			<ul>
				<li><a class="summary-link" href="#accordion-basic">{@multitabs.accordion.title.basic}</a></li>
				<li><a class="summary-link" href="#accordion-basic">{@multitabs.accordion.title.siblings}</a></li>
				<li><a class="summary-link" href="#modal-example">{@multitabs.modal.title}</a></li>
				<li><a class="summary-link" href="#tabs-example">{@multitabs.tabs.title}</a></li>
				<li><a class="summary-link" href="#jquery">jQuery</a></li>
			</ul>
		</div>
	<div class="open-summary">
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
    </div>
	<script>jQuery("#cssmenu-sandbox").menumaker({ title: "Sandbox", format: "multitoggle", breakpoint: 768 }); </script>

	<article>
		${LangLoader::get_message('multitabs.definition', 'multitabs', 'sandbox')}
	</article>

	<article id="accordion-example" class="sandbox-title">
		<header>
			<h2>{@multitabs.accordion.title}</h2>
		</header>
		<div id="accordion-basic">
		<div id="tabs-form">
			# INCLUDE TABS_PHP_FORM #

			<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
				<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
				<div class="formatter-content">
					{PRE_ACCORDION_FORM}
				</div>
			</div>
		</div>
		</div>
		<div id="accordion-siblings">
			{@multitabs.options.siblings}
			<div class="accordion-container siblings">
			    <div class="accordion-controls">
			        <span class="open-all-accordions" aria-label="${LangLoader::get_message('open.all.panels', 'main')}"><i class="fa fa-fw fa-chevron-down"></i></span>
			        <span class="close-all-accordions" aria-label="${LangLoader::get_message('close.all.panels', 'main')}"><i class="fa fa-fw fa-chevron-up"></i></span>
			    </div>
				<nav>
					<ul>
						<li><a href="" data-accordion data-target="target-04">{@multitabs.menu.title} 04</a></li>
						<li><a href="" data-accordion data-target="target-05">{@multitabs.menu.title} 05</a></li>
						<li><a href="" data-accordion data-target="target-06">{@multitabs.menu.title} 06</a></li>
					</ul>
				</nav>
				<div class="panel-container">
					<div id="target-04" class="accordion accordion-animation">
						<div class="content-panel">
							<h6>{@multitabs.panel.title} 04</h6>
							{@lorem.large.content}
						</div>
					</div>
					<div id="target-05" class="accordion accordion-animation">
						<div class="content-panel">
							<h6>{@multitabs.panel.title} 05</h6>
							{@lorem.medium.content}
						</div>
					</div>
					<div id="target-06" class="accordion accordion-animation">
						<div class="content-panel">
							<h6>{@multitabs.panel.title} 06</h6>
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="PHPBoost" itemprop="image">
						</div>
					</div>
				</div>
			</div>

			<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
				<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
				<div class="formatter-content">
					{PRE_ACCORDION_SIBLINGS}
				</div>
			</div>
		</div>
	</article>

	<article id="modal-example" class="sandbox-title">
		<header>
			<h2>{@multitabs.modal.title}</h2>
		</header>
		<div id="modal-html">
			<h5>{@multitabs.html}</h5>
			<div class="modal-container">
				<button class="button" data-modal data-target="modal-01">{@multitabs.open.modal}</button>
				<div id="modal-01" class="modal modal-animation">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel">
						<h6>{@multitabs.panel.title} 07</h6>
						{@lorem.large.content}
					</div>
				</div>
			</div>

			<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
				<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
				<div class="formatter-content">
					{PRE_MODAL_HTML}
				</div>
			</div>
		</div>
	</article>

	<article id="tabs-example" class="sandbox-title">
		<header>
			<h2>{@multitabs.tabs.title}</h2>
		</header>
		<div id="tabs-html">
			<h5>{@multitabs.html}</h5>
			<div class="tabs-container">
				<nav>
					<ul>
						<li><a href="#" data-tabs data-target="tab-07">{@multitabs.menu.title} 07</a></li>
						<li><a href="#" data-tabs data-target="tab-08">{@multitabs.menu.title} 08</a></li>
						<li><a href="#" data-tabs data-target="tab-09">{@multitabs.menu.title} 09</a></li>
					</ul>
				</nav>
				<div id="tab-07" class="tabs tabs-animation first-tab">
					<div class="content-panel">
						<h6>{@multitabs.panel.title} 07</h6>
						{@lorem.large.content}
					</div>
				</div>
				<div id="tab-08" class="tabs tabs-animation">
					<div class="content-panel">
						<h6>{@multitabs.panel.title} 08</h6>
						{@lorem.medium.content}
					</div>
				</div>
				<div id="tab-09" class="tabs tabs-animation">
					<div class="content-panel">
						<h6>{@multitabs.panel.title} 09</h6>
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="PHPBoost" itemprop="image">
					</div>
				</div>
			</div>

			<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
				<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
				<div class="formatter-content">
					{PRE_TABS_HTML}
				</div>
			</div>
		</div>
		<div id="tabs-form">
			<h5>{@multitabs.form}</h5>
			# INCLUDE TABS_PHP_FORM #

			<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
				<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
				<div class="formatter-content">
					{PRE_TABS_FORM}
				</div>
			</div>
		</div>
	</article>

	<article id="jquery">
		<header>
			<h5>jQuery</h5>
		</header>
		<div class="content">
			${LangLoader::get_message('multitabs.js', 'multitabs', 'sandbox')}
		</div>
	</article>

	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>