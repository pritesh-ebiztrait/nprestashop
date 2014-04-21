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
var tpl = 0;
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
<!-- FIRST ROW -->
<div class="row">
	<!-- TEMPLATES LIST -->
	<div class="col-xs-12 col-sm-6 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card"></i>
				{l s='Templates list' mod='cartabandonment'}
			</div>
			<div class="panel-body">
			{if isset($templates)}
				<table class="table table-bordered table-striped table-hover" id="sandboxtesting">
					<thead>
						<tr>
							<th class="center">{l s='ID' mod='cartabandonment'}</th>
							<th>{l s='Template name' mod='cartabandonment'}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$templates item=template} 
							<tr>
								<td>{$template.id_template}</td>
								<td>{$template.name}</td>
								<td>
									<span class="btn btn-lg glyphicon glyphicon-eye-open" onClick="previewTemplate({$template.id_template}, '{$token}');"></span>
									<span class="btn btn-lg glyphicon glyphicon-edit" onClick="editTemplate({$template.id_template}, '{$token}');"></span>
									<span class="btn btn-lg glyphicon glyphicon-trash" onClick="deleteTemplate({$template.id_template}, '{$token}');$(this).parent().parent().remove();"></span>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			{/if}
			<a href="#" onClick="addTemplate();">+ {l s='New template' mod='cartabandonment'}</a>
			</div>
		</div>
	</div>
	<!-- TEMPLATES LIST -->
	<!-- REMINDERS -->
	<div class="col-xs-4 col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-cogs"></i>
				{l s='Reminder configuration' mod='cartabandonment'}
			</div>
			<div>
				<form role="form" class="form-horizontal" action="{$gwallet_form|escape:'htmlall':'UTF-8'}" method="post">
					<input type="hidden" name="conf" value="1">
					<div class="form-group">
						<label class="col-sm-6 control-label" for="form-field-1">
							{l s='First reminder:' mod='cartabandonment'}
						</label>
						<div class="col-sm-3">
							<input type="text" placeholder="" name="first_reminder" id="form-field-1" value="{$ABANDONCART_FIRST_REMINDER}" class="form-control">
						</div>
						<div class="col-sm-2">
							<select name="first_reminder_what" class="form-control">
								<option {if $ABANDONCART_FIRST_REMINDER_WHAT eq 'D'}selected="selected"{/if} value="D">{l s='Days' mod='cartabandonment'}</option>
								<option {if $ABANDONCART_FIRST_REMINDER_WHAT eq 'H'}selected="selected"{/if} value="H">{l s='Hours' mod='cartabandonment'}</option>
								<option {if $ABANDONCART_FIRST_REMINDER_WHAT eq 'M'}selected="selected"{/if} value="M">{l s='Minutes' mod='cartabandonment'}</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-6 control-label" for="form-field-2">
							{l s='Second reminder:' mod='cartabandonment'}
						</label>
						<div class="col-sm-3">
							<input type="text" placeholder="" name="second_reminder" id="form-field-2" value="{$ABANDONCART_SECOND_REMINDER}" class="form-control">
						</div>
						<div class="col-sm-2">
							<select name="second_reminder_what" class="form-control">
								<option {if $ABANDONCART_SECOND_REMINDER_WHAT eq 'D'}selected="selected"{/if} value="D">{l s='Days' mod='cartabandonment'}</option>
								<option {if $ABANDONCART_SECOND_REMINDER_WHAT eq 'H'}selected="selected"{/if} value="H">{l s='Hours' mod='cartabandonment'}</option>
								<option {if $ABANDONCART_SECOND_REMINDER_WHAT eq 'M'}selected="selected"{/if} value="M">{l s='Minutes' mod='cartabandonment'}</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-6 control-label" for="form-field-3">
							{l s='Max date reminder:' mod='cartabandonment'}
						</label>
						<div class="col-sm-3">
							<input type="text" placeholder="In days" name="max_reminder" id="form-field-3" value="{$ABANDONCART_MAX_DATE}" class="form-control">
						</div>
						<div class="col-sm-2">
							<select name="max_reminder_what" class="form-control">
								<option {if $ABANDONCART_MAX_DATE_WHAT eq 'D'}selected="selected"{/if} value="D">{l s='Days' mod='cartabandonment'}</option>
								<option {if $ABANDONCART_MAX_DATE_WHAT eq 'H'}selected="selected"{/if} value="H">{l s='Hours' mod='cartabandonment'}</option>
								<option {if $ABANDONCART_MAX_DATE_WHAT eq 'M'}selected="selected"{/if} value="M">{l s='Minutes' mod='cartabandonment'}</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-7">
							<button name="submit" class="btn btn-teal btn-block" type="submit">
								{l s='Save' mod='cartabandonment'} <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- REMINDERS -->
</div>
<!-- FIRST ROW -->
<div class="row" id="newTemplate" style="display: none;">
	<div class="col-xs-12 col-sm-6 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-cogs"></i>
				{l s='New template' mod='cartabandonment'}
				<div class="panel-tools">
					<span class="btn btn-xs btn-link glyphicon glyphicon-remove" onClick="$('#newTemplate').hide();"></span>
				</div>
			</div>
			<div class="panel-body center-block">
				<form role="form" id="form_add_template" class="form-horizontal" method="POST" action="#">
					<div class="form-group">
						<label class="col-sm-5 control-label" for="form-field-3">
							{l s='Template name' mod='cartabandonment'}
						</label>
						<div class="col-sm-7">
							<input type="text" placeholder="" name="name" id="form-field-3" value="{$client_id|escape:'htmlall':'UTF-8'}" class="form-control" required data-required="true">
						</div>
					</div>
					<div class="row" style="margin-bottom: 50px;">
						<div style="width: 115px; height: 150px; border: 1px solid red; background-image:url({$module_dir}model/1.gif); margin: auto;" onClick="selectModel(1);">&nbsp;</div>
					</div>
					<div style="width: 1024px; height: auto; margin: auto; display: none;" id="model_1" class="models">
						{include file="../../../model/1_form.tpl"}
						<input type="hidden" name="edit" value="1">
						<input type="hidden" name="tpl" value="2">
						<input type="hidden" name="uri" value="{$uri}">
						<input type="hidden" id="token_cartabandonment" name="token_cartabandonment" value="{$token}">
						<div class="row" style="margin-top: 5px;">
							<div class="col-md-12">
								<button name="submitGWallet" class="btn btn-teal btn-block" type="submit">
									{l s='Save' mod='cartabandonment'} <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row" id="edit_template" style="display: none;">
	<div class="col-xs-12 col-sm-6 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-cogs"></i>
				{l s='Template Editor' mod='cartabandonment'}
				<div class="panel-tools">
					<span class="btn btn-xs btn-link glyphicon glyphicon-remove" onClick="$('#newTemplate').hide();"></span>
				</div>
			</div>
			<div class="panel-body center-block">
				<form role="form" id="form_add_template" class="form-horizontal" method="POST" action="#">
					<div style="width: 1024px; height: auto; margin: auto;" id="edit_template_content">
					</div>
					<input type="hidden" name="edit" value="1">
					<input type="hidden" name="tpl" value="2">
					<input type="hidden" name="uri" value="{$uri}">
					<input type="hidden" id="token_cartabandonment" name="token_cartabandonment" value="{$token}">
					<div class="row" style="margin-top: 5px;">
						<div class="col-md-12">
							<button name="submitGWallet" class="btn btn-teal btn-block" type="submit">
								{l s='Save' mod='cartabandonment'} <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{include file="./addons.tpl"}

<script type="text/javascript" src="{$module_dir}views/js/cartabandonment.js"></script>
<script>
function addTemplate(){
	$("#newTemplate").show();
	$("#edit_template").hide();
}
function selectModel(id_model){
	$("#model_"+id_model).show();
	tpl = id_model;
}

<!--
/*<![CDATA[*/
function updateColor(id, color) {
	$("#"+id).css("background-color", "#"+color);
}
/*]]>*/
-->
/*
$('#form_add_template').validate({
    rules: {
        name: {
            minlength: 2,
            required: true
        },
        tpl: {
            required: true
        }
    },
    highlight: function (element) {
        $(element).closest('.control-group').removeClass('success').addClass('error');
    },
    success: function (element) {
        element.text('OK!').addClass('valid')
            .closest('.control-group').removeClass('error').addClass('success');
    }
});*/
</script>