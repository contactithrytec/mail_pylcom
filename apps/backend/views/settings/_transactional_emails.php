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
 * @since 1.4.9
 */

/** @var Controller $controller */
$controller = controller();

/** @var CActiveForm $form */
$form = $controller->getData('form');

/** @var OptionCronProcessTransactionalEmails $cronTransEmailsModel */
$cronTransEmailsModel = $controller->getData('cronTransEmailsModel');

?>
<div class="box box-primary borderless">
    <div class="box-header">
        <h3 class="box-title"><?php echo IconHelper::make('fa-cog') . t('settings', 'Settings for processing transactional emails'); ?></h3>
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
            'controller' => $controller,
            'form'       => $form,
        ]));
?>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <?php echo $form->labelEx($cronTransEmailsModel, 'delete_days_back'); ?>
                    <?php echo $form->numberField($cronTransEmailsModel, 'delete_days_back', $cronTransEmailsModel->fieldDecorator->getHtmlOptions('delete_days_back')); ?>
                    <?php echo $form->error($cronTransEmailsModel, 'delete_days_back'); ?>
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