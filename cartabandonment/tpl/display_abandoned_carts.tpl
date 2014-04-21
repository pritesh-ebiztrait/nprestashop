<div class="clear">&nbsp;</div>
<fieldset>
        <legend>{l s='For reminder ' mod='cartabandonment'}&nbsp;{$num_sending}</legend>
        <table class="table" cellspacing=0 cellpadding=0>
                <thead>
                        <tr>
                                <th>{l s='Id' mod='cartabandonment'}</th>
                                <th>{l s='Firstname' mod='cartabandonment'}</th>
                                <th>{l s='Lastname' mod='cartabandonment'}</th>
                                <th>{l s='Nbr products' mod='cartabandonment'}</th>
                                <th>{l s='Amount (with tax)' mod='cartabandonment'}</th>
                                <th>{l s='Date add' mod='cartabandonment'}</th>
                        </tr>
                </thead>
                <tbody>
                {foreach from=$carts_data item=_cart}
                        <tr>
                                <td>{$_cart.id_cart}</td>
                                <td>{$_cart.firstname|ucfirst}</td>
                                <td>{$_cart.lastname|strtoupper}</td>
                                <td>{$_cart.nbr_products}</td>
                                <td>{$_cart.amount_wt}</td>
                                <td>{$_cart.date_add}</td>
                        </tr>
                {/foreach}
                </tbody>
        </table>
</fieldset>
<div class="clear">&nbsp;</div>