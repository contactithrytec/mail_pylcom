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
 * @since 1.4.5
 */

/** @var Controller $controller */
$controller = controller();

/** @var string $pageHeading */
$pageHeading = (string)$controller->getData('pageHeading');

/** @var MaxmindDatabase $model */
$model = $controller->getData('model');

/**
 * This hook gives a chance to prepend content or to replace the default view content with a custom content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->getData()}
 * In case the content is replaced, make sure to set {@CAttributeCollection $collection->add('renderContent', false)}
 * in order to stop rendering the default content.
 * @since 1.3.3.1
 */
hooks()->doAction('before_view_file_content', $viewCollection = new CAttributeCollection([
    'controller'    => $controller,
    'renderContent' => true,
]));

// and render if allowed
if ($viewCollection->itemAt('renderContent')) { ?>
    <div class="box box-primary borderless">
        <div class="box-header">
            <div class="pull-left">
                <h3 class="box-title">
                    <?php echo IconHelper::make('glyphicon-map-marker') . html_encode((string)$pageHeading); ?>
                </h3>
            </div>
            <div class="pull-right">
                <?php echo HtmlHelper::accessLink(IconHelper::make('refresh') . t('app', 'Refresh'), ['maxmind/index'], ['class' => 'btn btn-primary btn-flat', 'title' => t('app', 'Refresh')]); ?>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
            <?php
            /**
             * This hook gives a chance to prepend content or to replace the default grid view content with a custom content.
             * Please note that from inside the action callback you can access all the controller view
             * variables via {@CAttributeCollection $collection->controller->getData()}
             * In case the content is replaced, make sure to set {@CAttributeCollection $collection->itemAt('renderGrid')} to false
             * in order to stop rendering the default content.
             * @since 1.3.3.1
             */
            hooks()->doAction('before_grid_view', $collection = new CAttributeCollection([
                'controller'    => $controller,
                'renderGrid'    => true,
            ]));

    // and render if allowed
    if ($collection->itemAt('renderGrid')) {
        $controller->widget('zii.widgets.grid.CGridView', hooks()->applyFilters('grid_view_properties', [
            'ajaxUrl'           => createUrl($controller->getRoute()),
            'id'                => $model->getModelName() . '-grid',
            'dataProvider'      => $model->getDataProvider(),
            'filter'            => null,
            'filterPosition'    => 'body',
            'filterCssClass'    => 'grid-filter-cell',
            'itemsCssClass'     => 'table table-hover',
            'selectableRows'    => 0,
            'enableSorting'     => false,
            'cssFile'           => false,
            'pager'             => false,
            'columns' => hooks()->applyFilters('grid_view_columns', [
                [
                    'name'  => t('ip_location', 'Name'),
                    'value' => '$data["name"]',
                    'type'  => 'raw',
                ],
                [
                    'name'  => t('ip_location', 'Path on server'),
                    'value' => '$data["path"]',
                ],
                [
                    'name'  => t('ip_location', 'Download'),
                    'value' => 'CHtml::link($data["name"], $data["url"], array("target" => "_blank"))',
                    'type'  => 'raw',
                ],
                [
                    'name'  => t('ip_location', 'Action to take'),
                    'value' => 'empty($data["exists"]) ? t("ip_location", "Download and place it in the right path") : t("ip_location", "None")',
                ],
            ], $controller),
        ], $controller));
    }
    /**
     * This hook gives a chance to append content after the grid view content.
     * Please note that from inside the action callback you can access all the controller view
     * variables via {@CAttributeCollection $collection->controller->getData()}
     * @since 1.3.3.1
     */
    hooks()->doAction('after_grid_view', new CAttributeCollection([
        'controller'    => $controller,
        'renderedGrid'  => $collection->itemAt('renderGrid'),
    ]));
    ?>
            <div class="clearfix"><!-- --></div>
            </div>    
        </div>
        <div class="box-footer">
            <div class="pull-left">
                <?php echo t('ip_location', 'This product includes GeoLite2 data created by MaxMind, available from {from}', [
            '{from}' => '<a href="http://www.maxmind.com" target="_blank">http://www.maxmind.com</a>',
        ]); ?>
            </div>
            <div class="pull-right"></div>
            <div class="clearfix"><!-- --></div>
        </div>
    </div>
<?php
}
/**
 * This hook gives a chance to append content after the view file default content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->getData()}
 * @since 1.3.3.1
 */
hooks()->doAction('after_view_file_content', new CAttributeCollection([
    'controller'        => $controller,
    'renderedContent'   => $viewCollection->itemAt('renderContent'),
]));
