<?php declare(strict_types=1);
if (!defined('MW_PATH')) {
    exit('No direct script access allowed');
}

/**
 * This file is part of the MailWizz EMA application.
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.0
 */

/** @var Controller $controller */
$controller = controller();

/** @var CActiveForm $form */
$form = $controller->getData('form');

/** @var OptionCronDeleteLogs $cronDeleteLogsModel */
$cronDeleteLogsModel = $controller->getData('cronDeleteLogsModel');

?>
<div class="box box-primary borderless">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?php echo IconHelper::make('fa-cog') . t('settings', 'Delete logs settings'); ?></h3>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <div class="box-body">
        <?php
        /**
         * This hook gives a chance to prepend content before the active form fields.
         * Please note that from inside the action callback you can access all the controller view variables
         * via {@CAttributeCollection $collection->controller->getData()}
         * @since 1.3.3.1
         */
        hooks()->doAction('before_active_form_fields', new CAttributeCollection([
            'controller'        => $controller,
            'form'              => $form,
        ]));
?>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <?php echo $form->labelEx($cronDeleteLogsModel, 'delete_campaign_delivery_logs'); ?>
                    <?php echo $form->dropDownList($cronDeleteLogsModel, 'delete_campaign_delivery_logs', $cronDeleteLogsModel->getYesNoOptions(), $cronDeleteLogsModel->fieldDecorator->getHtmlOptions('delete_campaign_delivery_logs')); ?>
                    <?php echo $form->error($cronDeleteLogsModel, 'delete_campaign_delivery_logs'); ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
			        <?php echo $form->labelEx($cronDeleteLogsModel, 'delete_campaign_bounce_logs'); ?>
			        <?php echo $form->dropDownList($cronDeleteLogsModel, 'delete_campaign_bounce_logs', $cronDeleteLogsModel->getYesNoOptions(), $cronDeleteLogsModel->fieldDecorator->getHtmlOptions('delete_campaign_bounce_logs')); ?>
			        <?php echo $form->error($cronDeleteLogsModel, 'delete_campaign_bounce_logs'); ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
			        <?php echo $form->labelEx($cronDeleteLogsModel, 'delete_campaign_open_logs'); ?>
			        <?php echo $form->dropDownList($cronDeleteLogsModel, 'delete_campaign_open_logs', $cronDeleteLogsModel->getYesNoOptions(), $cronDeleteLogsModel->fieldDecorator->getHtmlOptions('delete_campaign_open_logs')); ?>
			        <?php echo $form->error($cronDeleteLogsModel, 'delete_campaign_open_logs'); ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
			        <?php echo $form->labelEx($cronDeleteLogsModel, 'delete_campaign_click_logs'); ?>
			        <?php echo $form->dropDownList($cronDeleteLogsModel, 'delete_campaign_click_logs', $cronDeleteLogsModel->getYesNoOptions(), $cronDeleteLogsModel->fieldDecorator->getHtmlOptions('delete_campaign_click_logs')); ?>
			        <?php echo $form->error($cronDeleteLogsModel, 'delete_campaign_click_logs'); ?>
                </div>
            </div>
        </div>
        <?php
/**
 * This hook gives a chance to append content after the active form fields.
 * Please note that from inside the action callback you can access all the controller view variables
 * via {@CAttributeCollection $collection->controller->getData()}
 * @since 1.3.3.1
 */
hooks()->doAction('after_active_form_fields', new CAttributeCollection([
    'controller'        => $controller,
    'form'              => $form,
]));
?>
        <div class="clearfix"><!-- --></div>
    </div>
</div>
<hr />
