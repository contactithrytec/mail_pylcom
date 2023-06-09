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
 * @since 1.3.4.8
 */

/** @var Controller $controller */
$controller = controller();

/** @var Lists $list */
$list = $controller->getData('list');

/** @var ListSegment $segment */
$segment = $controller->getData('segment');

/** @var ListSegmentCsvExport $export */
$export = $controller->getData('export');

/** @var int $pause */
$pause = (int)$controller->getData('pause');

/** @var int $processAtOnce */
$processAtOnce = (int)$controller->getData('processAtOnce');

?>
<div class="box box-primary borderless">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title">
                <?php echo IconHelper::make('export') . t('list_export', 'CSV export progress'); ?> 
            </h3>
        </div>
        <div class="pull-right">
            <?php echo CHtml::link(t('list_export', 'Back to export options'), ['list_segments_export/index', 'list_uid' => $list->list_uid, 'segment_uid' => $segment->segment_uid], ['class' => 'btn btn-primary btn-flat', 'title' => t('app', 'Back')]); ?>
            <?php echo CHtml::link(IconHelper::make('info'), '#page-info', ['class' => 'btn btn-primary btn-flat', 'title' => t('app', 'Info'), 'data-toggle' => 'modal']); ?>
        </div>
        <div class="clearfix"><!-- --></div>
    </div>
    <div class="box-body" id="csv-export" data-pause="<?php echo (int)$pause; ?>" data-iframe="<?php echo createUrl('list_import/ping'); ?>" data-attributes='<?php echo json_encode($export->attributes); ?>'>
        <span class="counters">
            <?php echo t('list_export', 'From a total of {total} subscribers, so far {totalProcessed} have been processed, {successfullyProcessed} successfully and {errorProcessing} with errors. {percentage} completed.', [
                '{total}' => '<span class="total">0</span>',
                '{totalProcessed}' => '<span class="total-processed">0</span>',
                '{successfullyProcessed}' => '<span class="success">0</span>',
                '{errorProcessing}' => '<span class="error">0</span>',
                '{percentage}'  => '<span class="percentage">0%</span>',
            ]); ?>
        </span>
        <div class="progress progress-striped active">
            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                <span class="sr-only">0% <?php echo t('app', 'Complete'); ?></span>
            </div>
        </div>
        <div class="alert alert-info log-info">
             <?php echo t('list_export', 'The export process is starting, please wait...'); ?>
        </div>
        <div class="log-errors"></div>
    </div>
    <div class="box-footer"></div>
</div>

<!-- modals -->
<div class="modal modal-info fade" id="page-info" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo IconHelper::make('info') . t('app', 'Info'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                $text = 'The export process will start shortly. <br />
                While the export is running it is recommended you leave this page as it is and wait for the export to finish.<br />
                The exporter runs in batches of {subscribersPerBatch} subscribers per file with a pause of {pause} seconds between the batches, therefore 
                the export process might take a while depending on your list size and number of subscribers to export.<br />
                This is a tedious process, so sit tight and wait for it to finish.';
echo t('list_export', StringHelper::normalizeTranslationString($text), [
    '{subscribersPerBatch}' => $processAtOnce,
    '{pause}'               => $pause,
]);
?>
            </div>
        </div>
    </div>
</div>