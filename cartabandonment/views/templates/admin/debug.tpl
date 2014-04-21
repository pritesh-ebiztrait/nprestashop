<script type="text/javascript" src="{$module_dir}views/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="{$module_dir}views/js/jscolor.js"></script>
<script type="text/javascript">
{literal}

tinymce.init({
	selector: "textarea",
	plugins: ["image", "table", "textcolor"],
	file_browser_callback: function(field_name, url, type, win) {
		if(type=='image') $('#my_form input').click();
	},
	toolbar1: "link image forecolor backcolor"
});
{/literal}
</script>
<script>
var tpl = 1;
</script>
<style>
input.color {
	width: 10em;
	padding: 3px 0;
	border: 1px solid black;
	text-align: center;
	cursor: pointer;
}
</style>
<iframe id="form_target" name="form_target" style="display:none"></iframe>
<form id="my_form" action="/upload/" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
    <input name="image" type="file" onchange="$('#my_form').submit();this.value='';">
</form>
<div style="margin: auto;">
Creation d'un templates
</div>
<br><br>
Choix du template
<div style="width: 115px; height: 150px; border: 1px solid red; background-image:url({$module_dir}model/1.gif); margin: auto;">

</div>
<br>

	<div id="tpl_1" style="width: 1024px; height: auto; margin: auto;">
		<form method="POST" action="#">
			{include file="../../../model/1_form.tpl"}
			<input type="hidden" id="edit" name="edit" value="1">
			<input type="hidden" id="tpl" name="tpl" value="2">
			<input type="hidden" id="uri" name="uri" value="{$uri}">
			<input type="hidden" id="token" name="token" value="{$token}">
			<input type="submit">
		</form>
	</div>
	<div id="edit_template" style="display: none;">
	
	</div>
<script type="text/javascript" src="{$module_dir}views/js/cartabandonment.js"></script>
<script type="text/javascript">
<!--
/*<![CDATA[*/
function updateColor(id, color) {
	$("#"+id).css("background-color", "#"+color);
}
/*]]>*/
-->
</script>