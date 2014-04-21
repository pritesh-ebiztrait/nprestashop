<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/global.css" />
<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/first_time.css" />
<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/tabulous.css" />

<div id="tabs">
	<ul>
		<li><a href="#tabs-1" title="">Templates</a></li>
		<li><a href="#tabs-2" title="">Reminders</a></li>
	</ul>
	<div id="tabs_container">
		<div id="tabs-1">
			<div id="slide1" class="content active">
				<p>
					Welcome to the awesome cart abandonment module configurator,<br><br>
					This tutorial will help you to create your first mail template and your reminders<br>
					blablabla ...
				</p>
				<a href="#slide2" id="1" class="button right next">{l s='NEXT'}</a>
			</div>
			<div id="slide2" class="content">
				<p>
					TEMPLATE CONFIGURATION
				</p>
				<a href="#slide1" id="2" class="button left prev">{l s='PREV'}</a> <a href="#slide3" id="2" class="button right next">{l s='NEXT'}</a>
			</div>
			<div id="slide3" class="content">
				<p>
					DO REMINDERS
				</p>
				<a href="#slide2" id="3" class="button left prev">{l s='PREV'}</a> <a href="#slide3" id="3" class="button right end">{l s='THE END'}</a>
			</div>
		</div>

		<div id="tabs-2">
			Test2
		</div>
	</div><!--End tabs container-->
</div>
<script type="text/javascript" src="{$module_dir}views/js/tabulous.min.js"></script>
<script type="text/javascript">
{literal}
	$('#tabs').tabulous({
		effect: 'scale'
	});

	$(function() {
		$(".next").click(
			function() {
				var id = parseInt(this.id)+1;
				$(this).parent().hide
				('slide', {direction: 'left'}, 1000, 
					function(){
						$("#slide"+id).show('slide', {direction: 'right'}, 1000);
					}
				);
			}
		)
		$(".prev").click(
			function() {
				var id = parseInt(this.id)-1;
				$(this).parent().hide
				('slide', {direction: 'right'}, 1000, 
					function(){
						$("#slide"+id).show('slide', {direction: 'left'}, 1000);
					}
				);
			}
		)
	});
{/literal}
</script>