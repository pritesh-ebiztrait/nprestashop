<div>
        <ul class="tnd_menubar">
                <li class="tnd_tab selected user_guide">
                        <a href="javascript:loadTab('user_guide')">{l s='User guide' mod='cartabandonment'}</a>
                </li>
                <li class="tnd_tab config_templates">
                        <a href="javascript:loadTab('config_templates')">{l s='Edit Templates' mod='cartabandonment'}</a>
                </li>
                <li class="tnd_tab config_mails">
                        <a href="javascript:loadTab('config_mails')">{l s='Do reminders' mod='cartabandonment'}</a>
                </li>
                <li class="tnd_tab config_carts">
                        <a href="javascript:loadTab('config_carts')">{l s='Discounts and statistics' mod='cartabandonment'}</a>
                </li>
        </ul>
</div>
<br /><br /><br />
<div class="tnd_content">                                
        <div class="tnd_page user_guide">
                {$user_guide}
        </div>
        <div class="tnd_page config_templates" style="display:none;"></div>
        <div class="tnd_page config_carts" style="display:none;"></div>
        <div class="tnd_page config_mails" style="display:none;"></div>
</div>