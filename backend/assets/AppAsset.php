<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'components/plugins/bootstrap/dist/css/bootstrap.min.css',
        'components/icons_fonts/elegant_font/elegant.min.css',
        'components/layouts/layout-icon-menu/css/color/light/color-default.min.css',
        'components/plugins/switchery/dist/switchery.min.css',
        'components/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css',
        'components/plugins/sweetalert2/dist/sweetalert2.min.css',
        'components/plugins/font-awesome/css/font-awesome.min.css',
        'components/plugins/weather-icons/css/weather-icons.min.css',
        'components/plugins/weather-icons/css/weather-icons-wind.min.css',
        'components/plugins/rickshaw/rickshaw.min.css',
        'components/plugins/dropify/dist/css/dropify.min.css',
        'components/css/components.css',
        'components/plugins/bootstrap-table/dist/bootstrap-table.min.css',
        'components/pages/login/login-v1/css/login.css',
        'components/plugins/datatables/media/css/dataTables.bootstrap4.min.css',
        'components/layouts/layout-icon-menu/css/layout.min.css',
        'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
        
    ];

    public $js = [
        'components/plugins/tether/dist/js/tether.min.js',
        'components/plugins/bootstrap/dist/js/bootstrap.min.js',
        'components/plugins/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js',
        'components/plugins/switchery/dist/switchery.min.js',
        'components/plugins/screenfull.js/dist/screenfull.min.js',
        'components/plugins/classie/classie.js',


        'components/plugins/flot/jquery.flot.js',
        'components/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js',
        'components/plugins/Flot/jquery.flot.resize.js',
        'components/plugins/flot/jquery.flot.pie.js',
        'components/plugins/flot/jquery.flot.time.js',
        'components/plugins/flot/jquery.flot.categories.js',
        'components/plugins/flot-spline/js/jquery.flot.spline.min.js',
        'components/plugins/arcseldon-jquery.sparkline/dist/jquery.sparkline.js',
        'components/plugins/skycons/skycons.js',
        'components/plugins/progressbar.js/dist/progressbar.min.js',
        'components/plugins/gauge.js/dist/gauge.min.js',
        'components/plugins/raphael/raphael.min.js',
        'components/plugins/jquery-mapael/js/jquery.mapael.min.js',
        'components/plugins/jquery-mapael/js/maps/france_departments.min.js',
        'components/plugins/jquery-mapael/js/maps/world_countries.min.js',
        'components/plugins/jquery-mapael/js/maps/usa_states.min.js',
        'components/plugins/d3/d3.min.js',
        'components/plugins/rickshaw/rickshaw.min.js',

        'components/plugins/bootstrap-table/dist/bootstrap-table.min.js',
        'components/plugins/dropify/dist/js/dropify.min.js',
        'components/js/site.min.js',        
        'components/js/global/dashboard_v4.min.js',
        'components/js/global/bootstrap_tbl.js',
        

        'components/js/global/dropify.js',
        'components/js/colorpicker.js',
        'components/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
        'components/layouts/layout-icon-menu/js/layout.min.js',
        

       

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
