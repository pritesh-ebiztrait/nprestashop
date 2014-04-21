<?php
header('Access-Control-Allow-Origin: *');
require_once ('../../config/config.inc.php');
require_once ('../../init.php');

if (Tools::getValue('token') != Configuration::get('TND_TOKEN'))
        die('Bye bye pirats');

$obj = Module::getInstanceByName('cartabandonment');
$obj->loadClasses();
$action = Tools::getValue('action');
$employee_id_lang = Tools::getValue('employee_id_lang');
$old_id_lang = $cookie->id_lang;
$cookie->id_lang = (int)$employee_id_lang;

switch ($action) {
        case 'update_nbr_of_cart_pagination' :
                $value = Tools::getValue('value');
                echo $obj->updateNbrOfCartPagination($value);
                break;

        case 'update_action_pagination' :
                $min_val = Tools::getValue('min_val');
                $max_val = Tools::getValue('max_val');
                echo $obj->displayActionForPagination($min_val, $max_val);
                break;

        case 'display_carts' :
                $min_val = Tools::getValue('min_val');
                $max_val = Tools::getValue('max_val');
                $max = Configuration::get('TND_NBR_CART_PAGINATION');
                echo $obj->displayGivenUpCarts($min_val, $max);
                break;      

        case 'validate_all_templates' :
                echo $obj->validateAllTemplates();
                break;
        case 'disable_enable_reminders' :
                echo $obj->disable_enable_reminders();
                break;
        
        case 'update_voucher_validity' : 
                $value = Tools::getValue('value');
                echo $obj->updateVoucherValidity($value);
                break;
        
        case 'switch_active' :
                $id_template = Tools::getValue('id_tpl');
                echo $obj->switchActive(intval($id_template));
                break;
        
        case 'save_ranges' :
                $data = Tools::getValue('form');
                echo $obj->saveRanges($data);
                break;
        
        case 'add_block' :
                echo $obj->addBlock();
                break;
        
        case 'save_block' :
                echo $obj->saveBlock();
                break;
                
        case 'preview_template' :
                $id_template = Tools::getValue('template');
                $light = Tools::getValue('light');
                echo $obj->previewTemplate($id_template, $light);
                break;
        
        case 'display_description_of_new_template' :
                echo $obj->displayDescriptionOfNewTemplate();
                break;
        
        case 'save_template' :
                $data = Tools::getValue('form');
                $id_template = (int)Tools::getValue('id_template');
                $preview = Tools::getValue('preview');
                echo Tools::JSONEncode($obj->saveTemplate($data, $id_template, $preview));
                break;
                
        case 'edit_template' :
                $id_template = Tools::getValue('id_template');
                $preview = Tools::getValue('preview');
                echo $obj->editTemplate($id_template, $preview);
                break;
        
        case 'delete_template' :
                $id_template = Tools::getValue('id_template');
                echo $obj->deleteTemplate($id_template);
                break;
        
        case 'duplicate_template' :
                $id_template = Tools::getValue('id_template');
                echo $obj->duplicateTemplate($id_template);
                break;
        
        
        case 'save_config_of_sending_emails' :
                echo $obj->saveConfigOfSendingEmails();
                break;
        
        case 'update_num_of_sending' :
                $id_template = (int)Tools::getValue('id_template');
                $num_sending = Tools::getValue('num_sending');
                echo Tools::JSONEncode($obj->updateNumOfSending($id_template, $num_sending));
                break;
        
        case 'update_template_with_discount' :
                $id_template = (int)Tools::getValue('id_template');
                $discount = (int)Tools::getValue('discount');
                echo Tools::JSONEncode($obj->updateTemplateWithDiscount($id_template, $discount));
                break;
        
        
        case 'update_template_with_content_of_cart' :
                $id_template = (int)Tools::getValue('id_template');
                $with_content_of_cart = (int)Tools::getValue('with_content_of_cart');
                echo Tools::JSONEncode($obj->updateTemplateWithContentOfCart($id_template, $with_content_of_cart));
                break;
        
        case 'remind_carts' :
                $from = Tools::getValue('mailFrom');
                $to = Tools::getValue('mail_to');
                
                empty($from) ? $from = Configuration::get('PS_SHOP_EMAIL') : '';
                empty($to) ? $to = Configuration::get('PS_SHOP_EMAIL') : '';
                
                Configuration::updateValue('CART_REMINDER_FROM', $from);
                echo $obj->remindCarts($from, $to);
                break;
        
        case 'activate_reminders' :
                echo $obj->activateReminders();
                break;
        
        case 'deactivate_reminders' :
                echo $obj->activateReminders();
                break;
                         
        case 'display_transformed_carts' :
                echo $obj->displayTransformedCarts();
                break;
        
        case 'displayTab' :
                $tab = Tools::getValue('tab');
                switch ($tab) 
                {
                        case 'config_templates':
                                echo $obj->getContentTemplates();
                                break;
                        case 'config_mails':
                                echo $obj->getContentEmails();
                                break;
                        case 'config_carts':
                                echo $obj->getContentCarts();
                                break;
                        default:
                                echo $obj->getUserGuide();
                                break;
                }
                break;
        
        case 'display_table_of_statistics' :
                $begin = (int)Tools::getValue('begin');
                $limit = (int)Tools::getValue('limit');
                $order_by = Tools::getValue('order_by');
                $desc = (bool)Tools::getValue('desc');
                echo $obj->displayTableOfStatistics($order_by, $desc, $limit, $begin);
                break;
        
        default:
                break;
}

$cookie->id_lang = (int)$old_id_lang;