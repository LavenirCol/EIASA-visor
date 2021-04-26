<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'theme/dashforge/lib/@fortawesome/fontawesome-free/css/all.min.css',
        'theme/dashforge/lib/ionicons/css/ionicons.min.css',
        'theme/dashforge/css/dashforge.css',
        'theme/dashforge/css/dashforge.filemgr.css',
        'theme/dashforge/css/skin.charcoal.css',
        'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css',
        'https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/r-2.2.5/sp-1.1.1/datatables.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css'
    ];
    public $js = [
        'theme/dashforge/lib/jquery/jquery-3.6.0.min.js',
        'theme/dashforge/lib/bootstrap/js/bootstrap.bundle.min.js',
        'theme/dashforge/lib/feather-icons/feather.min.js',
        'theme/dashforge/lib/perfect-scrollbar/perfect-scrollbar.min.js',
        'theme/dashforge/js/dashforge.js',
        'theme/dashforge/js/dashforge.aside.js',
        'theme/dashforge/js/dashforge.filemgr.js',
        'theme/dashforge/lib/js-cookie/js.cookie.js',
        'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js',
        'https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/r-2.2.5/sp-1.1.1/datatables.min.js',
        'https://cdn.jsdelivr.net/npm/sweetalert2@10',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js',
        'https://code.highcharts.com/stock/highstock.js',
        'https://code.highcharts.com/stock/modules/data.js',
        'https://code.highcharts.com/stock/modules/exporting.js',
        'https://code.highcharts.com/stock/modules/export-data.js',
        'js/mimetypes.js',
        'js/reportes.js',
        'js/filemanager.js'
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
