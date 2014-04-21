<div class='clear'>&nbsp;</div>
<fieldset>
        <legend>{l s='List of reminders' mod='cartabandonment'}</legend>
        <table>
                <tr>
                        <td>{l s='Display statistics' mod='cartabandonment'}</td>
                        {if $begin > 0}
                        <td>
                                <a href='javascript:DisplayTableOfStatistics("{$order_by}", 0, {$limit}, {$desc})'>
                                        <img src="{$admin_img}list-prev2.gif" />
                                </a>
                        </td>
                        <td>
                                <a href='javascript:DisplayTableOfStatistics("{$order_by}", {$begin-$limit}, {$limit}, {$desc})'>
                                        <img src="{$admin_img}list-prev.gif" />
                                </a>
                        </td>
                        
                        {/if}
                        <td>
                                <b>{$begin+1}</b>
                                &nbsp;to&nbsp;
                                <b>{$begin+count($all_stats)}</b>
                        </td>
                        
                        {if $begin+$limit < $total_stats}
                        <td>
                                <a href='javascript:DisplayTableOfStatistics("{$order_by}", {$begin+$limit}, {$limit}, {$desc})'>
                                        <img src="{$admin_img}list-next.gif" />
                                </a>
                        </td>
                        <td>
                                <a href='javascript:DisplayTableOfStatistics("{$order_by}", {$total_stats-$begin}, {$limit}, {$desc})'>
                                        <img src="{$admin_img}list-next2.gif" />
                                </a>
                        </td>
                        {/if}
                </tr>
        </table>
        <table class='table' cellspacing=0 colspadding=0>
                <thead>
                        <tr>
                                <th>
                                        <table class='tnd_table' cellspacing=0 colspadding=0>
                                                <tr>
                                                        <th>{l s='Id cart' mod='cartabandonment'}</th>  
                                                        <th>
                                                                <table>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("id_cart", {$begin}, {$limit}, 0)'>
                                                                                        <img src="{$admin_img}up.gif" />
                                                                                </a></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("id_cart", {$begin}, {$limit}, 1)'>
                                                                                        <img src="{$admin_img}down.gif" />
                                                                                </a></td>
                                                                        </tr>
                                                                </table>
                                                        </th>
                                                </tr>
                                        </table>
                                </th>
                                <th>
                                        {l s='Firstname' mod='cartabandonment'}
                                </th>
                                <th>
                                        {l s='Lastname' mod='cartabandonment'}
                                </th>
                                <th>
                                        <table class='tnd_table' cellspacing=0 colspadding=0>
                                                <tr>
                                                        <th>{l s='Last remind' mod='cartabandonment'}</th>
                                                        <th>
                                                                <table>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("date_add", {$begin}, {$limit}, 0)'>
                                                                                        <img src="{$admin_img}up.gif" />
                                                                                </a></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("date_add", {$begin}, {$limit}, 1)'>
                                                                                        <img src="{$admin_img}down.gif" />
                                                                                </a></td>
                                                                        </tr>
                                                                </table>
                                                        </th>
                                                </tr>
                                        </table> 
                                </th>
                                <th>
                                        <table class='tnd_table' cellspacing=0 colspadding=0>
                                                <tr>
                                                        <th>{l s='Nbr remind' mod='cartabandonment'}</th>
                                                        <th>
                                                                <table>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("num_sending", {$begin}, {$limit}, 0)'>
                                                                                        <img src="{$admin_img}up.gif" />
                                                                                </a></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("num_sending", {$begin}, {$limit}, 1)'>
                                                                                        <img src="{$admin_img}down.gif" />
                                                                                </a></td>
                                                                        </tr>       
                                                                </table>
                                                        </th>
                                                </tr>
                                        </table>
                                </th>
                                <th>
                                        <table class='tnd_table' cellspacing=0 colspadding=0>
                                                <tr>
                                                        <th>{l s='Transformed' mod='cartabandonment'}</th>
                                                        <th>
                                                                <table>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("transformed", {$begin}, {$limit}, 0)'>
                                                                                        <img src="{$admin_img}up.gif" />
                                                                                </a></td>
                                                                        </tr>     
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("transformed", {$begin}, {$limit}, 1)'>
                                                                                        <img src="{$admin_img}down.gif" />
                                                                                </a></td>
                                                                        </tr>   
                                                                </table>
                                                        </th>
                                                </tr>
                                        </table>
                                </th>
                                <th>
                                        <table class='tnd_table' cellspacing=0 colspadding=0>
                                                <tr><th>
                                                        <th>{l s='Click' mod='cartabandonment'}</th>
                                                        <th>
                                                                <table>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("read", {$begin}, {$limit}, 0)'>
                                                                                        <img src="{$admin_img}up.gif" />
                                                                                </a></td>
                                                                        </tr>   
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("read", {$begin}, {$limit}, 1)'>
                                                                                        <img src="{$admin_img}down.gif" />
                                                                                </a></td>
                                                                        </tr>      
                                                                </table>
                                                        </th>
                                                </tr>
                                        </table>
                                </th>
                                <th>
                                        <table class='tnd_table' cellspacing=0 colspadding=0>
                                                <tr>
                                                        <th>{l s='Unsubscribed' mod='cartabandonment'}</th>
                                                        <th>
                                                                <table>
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("unsubscribed", {$begin}, {$limit}, 0)'>
                                                                                        <img src="{$admin_img}up.gif" />
                                                                                </a></td>
                                                                        </tr>    
                                                                        <tr>
                                                                                <td><a href='javascript:DisplayTableOfStatistics("unsubscribed", {$begin}, {$limit}, 1)'>
                                                                                        <img src="{$admin_img}down.gif" />
                                                                                </a></td>
                                                                        </tr>          
                                                                </table>
                                                        </th>
                                                </tr>
                                        </table>
                                </th>
                        </tr>
                </thead>
                <tbody>
                {foreach from=$all_stats item=stat}
                        <tr>
                                <td>{$stat.id_cart}</td>
                                <td>{$stat.lastname}</td>
                                <td>{$stat.firstname}</td>
                                <td>{$stat.date_add}</td>
                                <td>{$stat.num_sending}</td>
                                <td>{if $stat.transformed}{l s='Yes' mod='cartabandonment'}{else}{l s='No' mod='cartabandonment'}{/if}</td>
                                <td>{if $stat.read}{l s='Yes' mod='cartabandonment'}{else}{l s='No' mod='cartabandonment'}{/if}</td>
                                <td>{if $stat.unsubscribed}{l s='Yes' mod='cartabandonment'}{else}{l s='No' mod='cartabandonment'}{/if}</td>
                        </tr>
                {/foreach}
                </tbody>
        </table>
</fieldset>