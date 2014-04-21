<div class="hint" style="display:block">
        <b>{l s='How to use this module' mod='cartabandonment'}</b><br/><br/>
        <b>
                {l s='This module allows you to automatically send e-mails to remind your customers who have not completed the order process on your online shop.' mod='cartabandonment'}
                {l s="The e-mails are customizable: you can add a discount code, adjust the contents of the mail to the customer\'s language, choose the delay before sending the first reminder, schedule several reminders ..." mod='cartabandonment'}
        '</b><br/>
        <ol>
                <li>
                        <b>{l s='Edit a template for sending e-mails automatically' mod='cartabandonment'}</b>
                        <p>
                                {l s='First, write the name of the template, and select the language in which you want to send the e-mail (a French model and a English model are already available).' mod='cartabandonment'}
                        </p>
                        <p>
                                {l s='The table presents you all the templates you have created. Here you can choose to associate a discount to a template, and set the number for each e-mail (if a 1st reminder, a 2nd reminder ...)' mod='cartabandonment'}
                                {l s='The buttons to the right allow you to edit, customize and duplicate each template.' mod='cartabandonment'}
                        </p>
                        <p>
                                {l s='Then, for each template, customize the title of the e-mail, the body of the e-mail, the header, footer, the services provided and set the colors for text and blocks.' mod='cartabandonment'}
                                {l s='Make sure that the language of the contents of each mail matches the language selected for the template. Create a reminder template for each active language in your shop.' mod='cartabandonment'}
                        </p>
                        <p>
                                {l s='If you want multiple reminder emails are sent, you must create as many e-mail reminders for each language (otherwise the e-mail will be sent a reminder at random).' mod='cartabandonment'}
                                {l s='To do this, feel free to duplicate a model that you have previously customized and then modify the text to suit each language (templates of different languages ​​can have the same name).' mod='cartabandonment'}
                        </p>
                        <p>
                                {l s='The "duplicate" function brings consistency between all the e-mail reminder you send.' mod='cartabandonment'}
                                {l s='For a same language, all your e-mails will have the same footer, the same header and the same services block.' mod='cartabandonment'}
                        </p>
                        <p>
                                {l s='Note: It is necessary to use variables in place so that the client receives e-mail with personal information (with this customer number, his name, the contents of the cart he wanted to order ...).' mod='cartabandonment'}
                        </p>
                </li>
                <li>
                        <b>{l s='Configure the sendings of e-mails' mod='cartabandonment'}</b>
                        <p>
                                {l s='First, define the time between the day the customer adds items to their cart without finalizing the order, and the day the mail is a reminder is sent. If you want to send several reminders, you can also determine the number of e-mails and the number of days between each sending.' mod='cartabandonment'}
                        </p>
                        <p>
                                {l s='Delays before sending each e-mail can be configured in days, hours or minutes. If you choose a period of days, you can then set the time you want when the e-mail is sent during the week and the the time when it is sent during the weekend.' mod='cartabandonment'}
                        </p>
                </li>
                <li>
                        <b>{l s='Carts, discounts and stats' mod='cartabandonment'}</b>
                        <p>
                                {l s='In this tab, you can find the abandoned shopping carts and those who were converted after your reminders.' mod='cartabandonment'}
                                {l s='You can also set the discounts associated with email reminders. These discounts can be realized as a percentage or currency, and be different price according to the ranges you choose.' mod='cartabandonment'}
                        </p>
                </li>
        </ol>
        <b><p>
                {l s='CAUTION: To ensure that e-mails are sent automatically, it is necessary to have previously set a cron task, and the sending of e-mails from your online shop.' mod='cartabandonment'}
                {l s='Please contact your host to configure it.' mod='cartabandonment'}
        </p></b>
        <div class="crontab"></div>
</div>