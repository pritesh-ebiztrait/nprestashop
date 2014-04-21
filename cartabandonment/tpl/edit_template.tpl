<div class="clear">&nbsp;</div>
<form name='edit_template' id='edit_template'>
{if !empty($template->id)}<input type='hidden' name='id_template' value='{$template->id}'/>{/if}
<fieldset>
        <legend>{l s='Template name : ' mod='cartabandonment'}&nbsp;{$template->name}</legend>

        <div class="clear">&nbsp;</div>
        <div class='preview_template' style='border:2px solid;'>{$template->view()}</div>
        <div class="clear">&nbsp;</div>
        
        <div id="template">
                <label>{l s='Name of template' mod='cartabandonment'}</label>
                <input type="text" name="name" value="{$template->name}" size=70/>
                <div class="clear">&nbsp;</div>

                <label>{l s='Language of template' mod='cartabandonment'}</label>
                <select name="id_lang">
                        {foreach from=$languages item=lang}
                                <option value="{$lang.id_lang}" {if $template->id_lang == $lang.id_lang}selected = "selected"{/if}>{$lang.name}</option>
                        {/foreach}
                </select>
                <div class="clear">&nbsp;</div>
                
                <label>{l s='Title of email' mod='cartabandonment'}</label>
                <input type="text" name="title" value="{$template->title}" size=70/>
                <div class="clear">&nbsp;</div>
                <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                <div class="clear">&nbsp;</div>
                

                <div class="header_module">
                        <a class="header_module_toggle" href="javascript:toggleBlock('colors')" style="text-decoration:none;">
                                <img id="colors_img" src="{$admin_img}more.png"/>
                                {l s='Colors' mod='cartabandonment'}
                        </a>
                </div>
                <div id="colors" style="display:none;">
                        <fieldset>
                                <label>{l s='Main background color' mod='cartabandonment'}</label>
                                <input type='text' size=8 name='main_color' value='{$template->main_color}' class='my_colorpicker' style='background-color: #{$template->main_color}'/>
                                <div class='clear'>&nbsp;</div>
                                
                                <label>{l s='Header and footer background color' mod='cartabandonment'}</label>
                                <input type='text' size=8 name='header_background_color' value='{$template->header_background_color}' class='my_colorpicker'
                                       style='background-color: #{$template->header_background_color}'/>
                                <div class='clear'>&nbsp;</div>
                                
                                <label>{l s='Header and footer font color' mod='cartabandonment'}</label>
                                <input type='text' size=8 name='header_font_color' value='{$template->header_font_color}' class='my_colorpicker'
                                       style='background-color: #{$template->header_font_color}'/>
                                <div class='clear'>&nbsp;</div>
                                
                                <label>{l s='Title font color' mod='cartabandonment'}</label>
                                <input type='text' size=8 name='title_font_color' value='{$template->title_font_color}' class='my_colorpicker'
                                       style='background-color: #{$template->title_font_color}'/>
                                <div class='clear'>&nbsp;</div>
                                
                                <label>{l s='Body font color' mod='cartabandonment'}</label>
                                <input type='text' size=8 name='body_font_color' value='{$template->body_font_color}' class='my_colorpicker'
                                       style='background-color: #{$template->body_font_color}'/>
                                <div class="clear">&nbsp;</div>
                                <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                        </fieldset>
                </div>
                        
                {if $template->discount}
                        <div class="header_module">
                                <a class="header_module_toggle" href="javascript:toggleBlock('tnd_discount_text')" style="text-decoration:none;">
                                        <img id="tnd_discount_text_img" src="{$admin_img}more.png"/>
                                        {l s='Discount text' mod='cartabandonment'}
                                </a>
                        </div>
                        <div id="tnd_discount_text" style="display:none;">
                                <label>{l s='Text to present discount' mod='cartabandonment'}</label>
                                <textarea class="rte" id="tnd_discount_text_2" name='discount_text'>{$template->discount_text}</textarea>
                                <div class="clear">&nbsp;</div>
                                <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                                <div class="clear">&nbsp;</div>
                        </div>
                {/if}                
                
                <div class="header_module">
                        <a class="header_module_toggle" href="javascript:toggleBlock('tnd_header')" style="text-decoration:none;">
                                <img id="tnd_header_img" src="{$admin_img}more.png"/>
                                {l s='Header' mod='cartabandonment'}
                        </a>
                </div>
                <div id="tnd_header" style="display:none;">
                        <div class="clear hint" style="display:block;">
                                        <br/><br/>
                                        <ul>
                                                <li><b>%RMD_OPEN_LINK%, %RMD_OPEN_LINK%</b> : {l s='Those two variables have to be set on both sides of the item you want to link to the site' mod='cartabandonment'}</li>
                                        </ul>
                                </div>
                                <div class="clear">&nbsp;</div>
                        <label>{l s='Edit header' mod='cartabandonment'}</label>
                        <textarea class="rte" id="header_2" name='header'>{$template->header}</textarea>
                        <div class="clear">&nbsp;</div>
                        <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                        <div class="clear">&nbsp;</div>
                </div>

                <div class="header_module">
                        <a class="header_module_toggle" href="javascript:toggleBlock('tnd_left_column')" style="text-decoration:none;">
                                <img id="tnd_left_column_img" src="{$admin_img}more.png"/>
                                {l s='Left column' mod='cartabandonment'}
                        </a>
                </div>
                <div id="tnd_left_column" style="display:none;">
                        <label>{l s='Edit left column' mod='cartabandonment'}</label>
                        <textarea class="rte" id="tnd_left_column_2" name='left_column'>{$template->left_column}</textarea>
                        <div class="clear">&nbsp;</div>
                        <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                        <div class="clear">&nbsp;</div>
                </div>

                <div class="header_module">
                        <a class="header_module_toggle" href="javascript:toggleBlock('tnd_body')" style="text-decoration:none;">
                                <img id="tnd_body_img" src="{$admin_img}more.png"/>
                                {l s='Body' mod='cartabandonment'}
                        </a>
                </div>
                <div id="tnd_body" style="display:none;">
                        <label>{l s='Edit body' mod='cartabandonment'}</label>
                        <textarea class="rte" id="tnd_body_2" name='body'>{$template->body}</textarea>
                        <div class="clear">&nbsp;</div>
                        <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                        <div class="clear">&nbsp;</div>
                </div>

                <div class="header_module">
                        <a class="header_module_toggle" href="javascript:toggleBlock('tnd_services')" style="text-decoration:none;">
                                <img id="tnd_services_img" src="{$admin_img}more.png"/>
                                {l s='Services' mod='cartabandonment'}
                        </a>
                </div>
                <div id="tnd_services" style="display:none;">
                        <label>{l s='Edit services' mod='cartabandonment'}</label>
                        <textarea class="rte" id="tnd_services_2" name='services'>{$template->services}</textarea>
                        <div class="clear">&nbsp;</div>
                        <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                        <div class="clear">&nbsp;</div>
                </div>

                <div class="header_module">
                        <a class="header_module_toggle" href="javascript:toggleBlock('tnd_conclusion')" style="text-decoration:none;">
                                <img id="tnd_conclusion_img" src="{$admin_img}more.png"/>
                                {l s='Conclusion' mod='cartabandonment'}
                        </a>
                </div>
                <div id="tnd_conclusion" style="display:none;">
                        <label>{l s='Text to conclude' mod='cartabandonment'}</label>
                        <textarea class="rte" name="conclusion">{$template->conclusion}</textarea>
                        <div class="clear">&nbsp;</div>
                        <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                        <div class="clear">&nbsp;</div>
                </div>
                        
                <div class="header_module">
                        <a class="header_module_toggle" href="javascript:toggleBlock('tnd_footer')" style="text-decoration:none;">
                                <img id="tnd_footer_img" src="{$admin_img}more.png"/>
                                {l s='Footer' mod='cartabandonment'}
                        </a>
                </div>
                <div id="tnd_footer" style="display:none;">
                        <div class="clear">&nbsp;</div>
                        <div class="clear hint" style="display:block;">
                                <br/><br/>
                                <ul>
                                        <li><b>%UNSUBSCRIBE_OPEN_LINK%, %UNSUBSCRIBE_OPEN_LINK%</b> : {l s='Those two variables have to be set on both sides of the item you want to link of deregistration' mod='cartabandonment'}</li>
                                </ul>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <label>{l s='Edit footer' mod='cartabandonment'}</label>
                        <textarea class="rte" name="footer">{$template->footer}</textarea>
                        <div class="clear">&nbsp;</div>
                        <input class='button' type='button' value='{l s='Save template' mod='cartabandonment'}' style='float:right;' onclick='javascript:UpdateTemplate({$template->id})'/>
                        <div class="clear">&nbsp;</div>
                </div>
        </div>
</fieldset>
</form>