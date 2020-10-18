<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="description" content="Admin, Dashboard, Bootstrap">
    <base href="/public">
    <style>
        .hidden-item {
            display: none !important;
        }
    </style>
    <link rel="shortcut icon" sizes="196x196" href="images/huyhieu.png">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="theme/libs/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="theme/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css">
    <link rel="stylesheet" href="theme/assets/css/app.min.css">
    <script src="theme/libs/bower/breakpoints.js/dist/breakpoints.min.js"></script>
    <script>
        Breakpoints();
    </script>
    <script src="theme/assets/js/core.min.js"></script>



    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Report Viewer Office2013 style -->
    <link href="stimulsoft/Libs/Reports.JS/Css/stimulsoft.viewer.office2013.whiteblue.css" rel="stylesheet">
    <link href="stimulsoft/Libs/Reports.JS/Css/stimulsoft.designer.office2013.whiteblue.css" rel="stylesheet">

    <!-- Stimusloft Reports.JS -->
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.reports.js" type="text/javascript"></script>
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.reports.maps.js" type="text/javascript"></script>
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.viewer.js" type="text/javascript"></script>
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.designer.js" type="text/javascript"></script>

    <link rel="stylesheet" href="css/global.css" />
    <!-- DevExtreme themes -->
    <link rel="stylesheet" href="dx/css/dx.common.css">
    <link rel="stylesheet" href="dx/css/dx.material.blue.light.compact.css">




</head>