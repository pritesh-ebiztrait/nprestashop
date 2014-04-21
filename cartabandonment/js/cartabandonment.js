$.ajaxSetup({url : baseUri, type: 'POST', crossDomain : true});
url = baseUri;

$(document).ajaxComplete(function(){
        setColorPicker();
        initTinyMCE();     
});

function DisplayTableOfStatistics(order_by, begin, limit, desc)
{

        $.ajax({
                data : {
                        employee_id_lang : employee_id_lang,
                        id_shop : id_shop,
                        action : "display_table_of_statistics",
                        order_by : order_by,
                        desc : desc,
                        limit : limit,
                        begin : begin
                },
                success :function(data)
                {
                        $('#table_of_statistics').html(data);
                }
        });
}



function UpdateNbrOfCartPagination(value)
{

        $.ajax({
                data : {
                        employee_id_lang : employee_id_lang,
                        action : "update_nbr_of_cart_pagination",
                        id_shop : id_shop,
                        value : value
                },
                success :function(data)
                {
                        DisplayCarts(0, value);
                }
        });
}


function DisplayCarts(minVal, maxVal)
{
        $.ajax({
                data :{
                        employee_id_lang : employee_id_lang,
                        action : "display_carts",
                        id_shop : id_shop,
                        min_val : minVal,
                        max_val : maxVal
                },
                success :function(data)
                {
                        if(data)
                        {
                                $("#display_given_up_carts").html(data);
                                UpdateActionForPagination(minVal, maxVal);
                        }
                }
        });
}



function UpdateActionForPagination(minVal, maxVal)
{
        $.ajax({
                data :{
                        employee_id_lang : employee_id_lang,
                        action : "update_action_pagination",
                        id_shop : id_shop,
                        min_val : minVal,
                        max_val : maxVal
                },
                success :function(data)
                {
                        if(data)
                        {
                                $("#display_given_up_carts2").html(data);
                        }
                }
        });
}



function ValidateAllTemplates()
{
        $.ajax({
                data : {
                        employee_id_lang : employee_id_lang,
                        action : "validate_all_templates",
                        id_shop : id_shop
                },
                success :function(data)
                {
                        $("#validate_all_templates").html(data);
                }
        });
}


function UpdateVoucherValidity(value)
{  
        $.ajax({
                data :{
                        employee_id_lang : employee_id_lang,
                        action : "update_voucher_validity",
                        id_shop : id_shop,
                        value : value
                },
                success :function(data)
                {
                }
        });
}

function DisplayNotification(id)
{
        $('.tnd_notification').each(function(){$(this).hide(1000)});
        if(id)
                $(id).show(2000);
        ValidateAllTemplates();
}


function toggleBlock(id)
{
        if($("#"+id).hasClass("block_open"))
        {
                $("#"+id).slideUp(500);
                $("#"+id).toggleClass("block_open");
                content = $("#"+id+"_img").attr('src');
                content = content.replace("less.png", "more.png");
                $("#"+id+"_img").attr('src', content);
        }
        else
        {
                $("#"+id).toggleClass("block_open");
                $("#"+id).slideDown(500);
                content = $("#"+id+"_img").attr('src');
                content = content.replace("more.png", "less.png");
                $("#"+id+"_img").attr('src', content);
        }
}


function TestSendingOfEmails(mailFrom, mailTo)
{
        $.ajax({
                data :{
                        employee_id_lang : employee_id_lang,
                        action : "remind_carts",
                        test : 1,
                        id_shop : id_shop,
                        mail_from : mailFrom,
                        mail_to : mailTo
                },
                success : function(data){
                        if(data)
                                DisplayNotification($('#mail_test_conf'));
                        else DisplayNotification($('#mail_test_err'));
                }
        });
}


function ActivateReminders()
{
        
        $.post(url,
                {
                        employee_id_lang : employee_id_lang,
                        action : "remind_carts",
                        id_shop : id_shop,
                        mail_from : mailFrom
                },
                function(data){
                        if(data)
                        {
                                DisableEnableReminders();
                                DisplayNotification($('#rmd_act_conf'));
                        }
                        else DisplayNotification($('#rmd_act_err'));
                });
}


function DisableEnableReminders()
{
        $.post(url,
                {
                        employee_id_lang : employee_id_lang,
                        action : 'disable_enable_reminders',
                        id_shop : id_shop
                },
                function(data){
                        $('#disable_enable_reminders').html(data);
                });
}


function DeactivateReminders()
{
        $.post(url,
                {
                        action : 'deactivate_reminders',
                        id_shop : id_shop
                },
                function(data){
                        employee_id_lang : employee_id_lang,
                        $('#disable_enable_reminders').html(data);
                });
}



function ActivateReminders()
{
        $.post(url,
                {
                        action : 'activate_reminders',
                        id_shop : id_shop
                },
                function(data){
                        employee_id_lang : employee_id_lang,
                        $('#disable_enable_reminders').html(data);
                });
}



function SaveConfigOfSendingEmails()
{
        myForm = $("form#config_of_sending_of_emails").serializeArray();
        
        json = {};
        for(i in myForm)
        {
                json[myForm[i].name] = myForm[i].value;
        }
        
        $.post(url,
                {
                        employee_id_lang : employee_id_lang,
                        action : "save_config_of_sending_emails",
                        id_shop : id_shop,
                        form : json
                },
                function(data){
                        if(data)
                                DisplayNotification($('#saved_conf'));
                        else DisplayNotification($('#saved_err'));       
                        $(".crontab").html(data);
                });
}


function UpdateNumOfSending(id_template, num_sending)
{

        $.ajax({
                data :{
                        employee_id_lang : employee_id_lang,
                        action : "update_num_of_sending",
                        id_shop : id_shop,
                        num_sending : num_sending,
                        id_template : id_template
                },
                success : function(data){
                        data = $.parseJSON(data);
                        $('div.all_templates').html(data.all_templates);
                        $('div.one_template').html(data.edit_template);
                }
        });
}


function UpdateTemplateWithDiscount(id_template, discount)
{

        $.ajax({
                data :{
                        employee_id_lang : employee_id_lang,
                        action : "update_template_with_discount",
                        discount : discount,
                        id_shop : id_shop,
                        id_template : id_template
                },
                success : function(data){
                        data = $.parseJSON(data);
                        $('div.all_templates').html(data.all_templates);
                        $('div.one_template').html(data.edit_template);
                }
        });                
}


function UpdateTemplateWithContentOfCart(id_template, display)
{

        $.ajax({
                data:{
                        employee_id_lang : employee_id_lang,
                        action : "update_template_with_content_of_cart",
                        with_content_of_cart : display,
                        id_shop : id_shop,
                        id_template : id_template
                },
                success :function(data){
                        if(data)
                        {
                                data = $.parseJSON(data);
                                $('div.all_templates').html(data.all_templates);
                                $('div.one_template').html(data.edit_template);
                        }
                }
        });
}


function DisableEnableDiscount()
{
        $.post(url,
                {
                        employee_id_lang : employee_id_lang,
                        id_shop : id_shop,
                        action : 'disable_enable_discount'
                },
                function(data){
                        $('#disable_enable_discount').html(data);
                        
                });
}


function DeleteRange(el, maxEl){
        numEl = parseInt(el.replace("range_",""));

        $(".range_discount").each(
                function()
                {
                        nextNum = parseInt($(this).attr('id').replace("range_",""));
                        if(nextNum > numEl)
                        {
                                
                                nContent = $(this).html();
                                nContent = myReplace(nContent, nextNum, "previous");
                                $(this).html(nContent);
                                $(this).removeAttr("id");
                                $(this).attr("id", "range_"+(nextNum-1));
                        }
                });
                
        $("#"+el).remove();        
        
        SaveRanges();
}


function myReplace(myContent, numEl, sens)
{
        if(sens == "next")
        {
                reg = new RegExp("range_"+numEl, "g");
                myContent = myContent.replace(reg, "range_"+(numEl+1));
                myContent = myContent.replace("from_"+numEl, "from_"+(numEl+1));
                myContent = myContent.replace("to_"+numEl, "to_"+(numEl+1));
                myContent = myContent.replace("value_"+numEl, "value_"+(numEl+1));
                myContent = myContent.replace("type_"+numEl, "type_"+(numEl+1));
        }
        else if(sens == "previous")
        {
                reg = new RegExp("range_"+numEl, "g");
                myContent = myContent.replace(reg, "range_"+(numEl-1));
                myContent = myContent.replace("from_"+numEl, "from_"+(numEl-1));
                myContent = myContent.replace("to_"+numEl, "to_"+(numEl-1));
                myContent = myContent.replace("value_"+numEl, "value_"+(numEl-1));
                myContent = myContent.replace("type_"+numEl, "type_"+(numEl-1));
        }
        
        return myContent;
}


function AddNewRange(el, maxEl)
{
        myContent = $("#"+el).html();
        numEl = parseInt(el.replace("range_",""));
        myContent = myReplace(myContent, numEl, "next");

        
        allChildren = $("#"+el).parent().children();
        
        $(".range_discount").each(
                function()
                {
                        nextNum = parseInt($(this).attr('id').replace("range_",""));
                        if(nextNum > numEl)
                        {
                                
                                nContent = $(this).html();
                                nContent = myReplace(nContent, nextNum, "next");
                                $(this).html(nContent);
                                $(this).removeAttr("id");
                                $(this).attr("id", "range_"+(nextNum+1));
                        }
                });
        
        $("#"+el).after('<tr class="range_discount" id="range_'+(numEl+1)+'">'+myContent+"</tr>");
        
        SaveRanges();
}


function SaveRanges(notification)
{
        myForm = $("#edit_ranges_discount").serialize();
console.log(myForm);

        $.post(
                url,
                {
                        employee_id_lang : employee_id_lang,
                        action : "save_ranges",
                        id_shop : id_shop,
                        form : myForm
                },
                function(data){
                        if(notification)
                        {
                                if(data)
                                        DisplayNotification($('#saved_conf'));
                                else DisplayNotification($('#saved_err'));
                        }
                }
        );
}


function AddNewBlock(save)
{
        if(save == "true")
        {
                myForm = $(".config_new_template").serialize(true);
                $.post(
                        url,
                        {
                                employee_id_lang : employee_id_lang,
                                action : "add_block",
                                id_shop : id_shop,
                                form : myForm
                        },
                        function(data){
                                $("#template").html(data);
                                initTinyMCE();
                        }
                );
        }
        
        else $("div.config_new_block").fadeIn(300);
}


function SaveBlock(id)
{
        myId = "#block_form_"+id;
        tinyMCE.triggerSave();
        myForm = $(myId).serializeArray();
        json = {};
        
        for(i in myForm)
        {
                json[myForm[i].name] = myForm[i].value;
        }

        $.post(
                url,
                {
                        employee_id_lang : employee_id_lang,
                        action : "save_block",
                        id_shop : id_shop,
                        form : json,
                        id : id
                },
                function(data){
                        if(!$("#preview_template").hasClass('block_open'))
                                $("#preview_template").toggleClass('block_open');
                        $("#preview_template").html(data);
                }      
        );
}



function SwitchActive(template, el)
{
        
        $.post(
                url,
                {
                        employee_id_lang : employee_id_lang,
                        action : 'switch_active',
                        id_shop : id_shop,
                        id_tpl : template
                },
                function(data){
                        $('div.all_templates').html(data);
                }
        );
}



function DeactiveCarts()
{
        $(".check_carts").each(
                function()
                {
                        if($(this).attr("checked"))
                        {
                                id = $(this).attr("id");
                                id = parseInt(id.replace('cart_',''));
                                
                                $.post(
                                        url,
                                        {
                                                employee_id_lang : employee_id_lang,
                                                action : 'deactivate_cart',
                                                id_shop : id_shop,
                                                id : id
                                        },
                                        function(data){
                        
                                        }
                                );
                        }
                }
        );
}



function AddNewTemplate(value)
{
        tinyMCE.triggerSave();
        if(!value)
        {
                $.ajax({
                        data:{
                                employee_id_lang : employee_id_lang,
                                id_shop : id_shop,
                                action : "display_description_of_new_template"
                        },
                        success:function(data){
                                $('div.new_template').html(data);
                                $('div.one_template').html('');
                        }
                });
                
        }
        else
        {
                myForm = $('form.add_new_template').serializeArray();
                json = {};
                for(i in myForm)
                {
                        json[myForm[i].name] = myForm[i].value;
                }
                $.ajax({
                        data:{
                                employee_id_lang : employee_id_lang,
                                action : "save_template",
                                id_shop : id_shop,
                                form : json
                        },
                        success:function(data){
                                data = $.parseJSON(data);
                                $('div.new_template').html('');
                                $('div.one_template').html(data.edit_template);
                                $('div.all_templates').html(data.all_templates);
                        }
                });
        }
}


function EditTemplate(id_template, preview)
{
        $('div.new_template').html('');

        $.ajax({
                data :{
                        employee_id_lang : employee_id_lang,
                        action : 'edit_template',
                        id_shop : id_shop,
                        id_template : id_template
                },
                success : function(data){
                        $('div.one_template').html(data);
                }
        });
                   
}

function setColorPicker()
{
        $('.my_colorpicker').ColorPicker({
                onSubmit: function(hsb, hex, rgb, el) {
                        $(el).val(hex);
                        $(el).ColorPickerHide();
                        $(el).css('background-color', '#'+hex);
                },
                onBeforeShow: function () {
                        $(this).ColorPickerSetColor(this.value);
                        $(this).css('background-color', '#'+this.value);
                }
        })
        .bind('keyup', function(){
                $(this).ColorPickerSetColor(this.value);
                $(this).css('background-color', '#'+this.value);               
        });
}


function DeleteTemplate(id_template)
{
        $.ajax({
                data:{
                        employee_id_lang : employee_id_lang,
                        action : 'delete_template',
                        id_shop : id_shop,
                        id_template : id_template
                },
                success:function(data){
                        $('div.all_templates').html(data);
                        $('.one_template').html('');
                }
        }); 
}


function DuplicateTemplate(id_template)
{
        $.ajax({
                data:{
                        employee_id_lang : employee_id_lang,
                        action : 'duplicate_template',
                        id_shop : id_shop,
                        id_template : id_template
                },
                success:function(data){
                        $('div.all_templates').html(data);
                }
        });
}


function UpdateTemplate(id_template)
{
        tinyMCE.triggerSave();
        if (parseInt(id_template) == 0)
                myForm = $("#add_new_template").serializeArray();
        else
                myForm = $("#edit_template").serializeArray();
        json = {};
        for(i in myForm)
        {
                json[myForm[i].name] = myForm[i].value;
        }

        $.ajax({
                data : {
                        employee_id_lang : employee_id_lang,
                        action : 'save_template',
                        id_shop : id_shop,
                        id_template : id_template,
                        form : json
                },
                success :function(data){
                        if (data)
                        {
                                data = $.parseJSON(data);
                                
                                $('div.all_templates').html(data.all_templates);
                                $('div.one_template').html(data.edit_template);
                        }
                }
        });
}


function loadTab(class_name)
{
        block = $('div.'+class_name);
        tab = $('li.'+class_name);
        if (!$(tab).hasClass("selected"))
        {
                $.ajax({
                        data: {
                                employee_id_lang : employee_id_lang,
                                action : "displayTab",
                                id_shop : id_shop,
                                tab : class_name
                        },
                        success : function(data){
                                block.html(data);
                                $(".tnd_page").each(function(){
                                        if (!$(this).hasClass(class_name))
                                                $(this).hide();
                                });
                                
                                $(".tnd_tab").each(function(){
                                        if (!$(this).hasClass(class_name))
                                                $(this).removeClass("selected");
                                });
                                
                                tab.addClass("selected");
                                block.show();
                        }
                });

        }
}


function initTinyMCE(){
        tinyMCE.init({
                mode : "specific_textareas",
                theme : "advanced",
                skin:"cirkuit",
                editor_selector : "rte",
                editor_deselector : "noEditor",
                plugins : "safari,pagebreak,style,table,advimage,advlink,inlinepopups,media,contextmenu,paste,fullscreen,xhtmlxtras,preview",
                // Theme options
                theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
                theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
                theme_advanced_buttons4 : "styleprops,|,cite,abbr,acronym,del,ins,attribs,pagebreak",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : false,
                content_css : pathCSS+"global.css",
                document_base_url : ad,
                width: "600",
                height: "auto",
                font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
                elements : "nourlconvert,ajaxfilemanager",
                file_browser_callback : "ajaxfilemanager",
                entity_encoding: "raw",
                convert_urls : false,
        language : iso

        });
}



$(document).ready(function() {
        $(".hr_of_sending").timepicker({});
});

                                