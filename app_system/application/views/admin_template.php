<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title><?php echo isset($company['name'])?$company['name']:"Iyotro"; ?></title>
    <meta name="description" content="Iyotro" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/libs/assets/animate.css/animate.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/libs/assets/font-awesome/css/font-awesome.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/libs/angular/angular-loading-bar/build/loading-bar.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/libs/angular/angular-material/angular-material.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/libs/jquery/bootstrap/dist/css/bootstrap.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/styles/material-design-icons.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/styles/font.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/styles/app.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/styles/global.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/bower_components/ng-notify/dist/ng-notify.min.css")?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/bower_components/angularPrint/angularPrint.css")?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/bower_components/angular-ui-tree/dist/angular-ui-tree.min.css")?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/bower_components/ngWYSIWYG/css/editor.css");?>" />
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/bower_components/angular-block-ui-master/dist/angular-block-ui.min.css");?>" />
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/node_modules/angular-material-data-table/dist/md-data-table.min.css");?>" />
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/styles/ng-tags-input.bootstrap.css")?>" type="text/css" media='screen,print'/>
    <link rel="stylesheet" href="<?php echo base_url("angular/assets/styles/ng-tags-input.css")?>" type="text/css" media='screen,print'/>
    <script>
      var application_name = '<?php echo  isset($company['name'])?$company['name']:"Arzap"; ?>';
      var base_url = "<?php echo base_url();?>";
    </script>
  </head>
  <body ng-app="app" style="margin:0px">
    <base href="<?php echo base_url();?>angular/" />
    <div class="app" ui-view ng-controller="AppCtrl"></div>
    <!-- jQuery -->
    <script src="<?php echo base_url("angular/assets/libs/jquery/jquery/dist/jquery.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/jquery/bootstrap/dist/js/bootstrap.js")?>"></script>
    <!-- Angular -->
    <script src="<?php echo base_url("angular/assets/libs/angular/angular/angular.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-animate/angular-animate.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-aria/angular-aria.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-cookies/angular-cookies.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-messages/angular-messages.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-resource/angular-resource.js")?>"></script>

    <script src="<?php echo base_url("angular/assets/libs/angular/angular-touch/angular-touch.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-material/angular-material.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/ngTagsInput/ng-tags-input.js")?>"></script>
    <link rel='stylesheet' href='<?php echo base_url("angular/assets/bower_components/textAngular/dist/textAngular.css");?>'>
    <script src='<?php echo base_url("angular/assets/bower_components/textAngular/dist/textAngular-rangy.min.js");?>'></script>
    <script src='<?php echo base_url("angular/assets/bower_components/textAngular/dist/textAngular-sanitize.min.js");?>'></script>
    <script src='<?php echo base_url("angular/assets/bower_components/textAngular/dist/textAngular.min.js");?>'></script>
    

    <script src="<?php echo base_url("angular/assets/libs/angular/angular-ui-router/release/angular-ui-router.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/ngstorage/ngStorage.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-ui-utils/ui-utils.js")?>"></script>
    <!-- bootstrap -->
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-bootstrap/ui-bootstrap-tpls.js")?>"></script>
    <!-- lazyload -->
    <script src="<?php echo base_url("angular/assets/libs/angular/oclazyload/dist/ocLazyLoad.js")?>"></script>
    <!-- translate -->
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-translate/angular-translate.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-translate-loader-static-files/angular-translate-loader-static-files.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-translate-storage-cookie/angular-translate-storage-cookie.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-translate-storage-local/angular-translate-storage-local.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/angular-translate-loader-url/angular-translate-loader-url.js")?>"></script>
    <!-- loading-bar -->
    <script src="<?php echo base_url("angular/assets/libs/angular/angular-loading-bar/build/loading-bar.js")?>"></script>
    <!-- App -->
    <script src="<?php echo base_url("angular/scripts/app.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/config.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/config.lazyload.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/config.router.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/app.ctrl.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/services.api.js")?>"></script>

    <script src="<?php echo base_url("angular/scripts/directives/lazyload.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/directives/ui-jp.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/directives/ui-nav.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/directives/ui-fullscreen.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/directives/ui-scroll.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/directives/ui-toggle.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/directives/global.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/filters/fromnow.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/services/ngstore.js")?>"></script>
    <script src="<?php echo base_url("angular/scripts/services/ui-load.js")?>"></script>
      
    <!-- load controller -->
    <script src="<?php echo base_url("angular/shared/template/script/SidebarCtrl.js")?>"></script>

    <!-- New liblary -->
    <script src="<?php echo base_url("angular/assets/bower_components/ng-notify/dist/ng-notify.min.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/angularPrint/angularPrint.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/checklist-model/checklist-model.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/ng-file-upload-shim/ng-file-upload-shim.min.js")?>"></script> <!-- for no html5 browsers support -->
    <script src="<?php echo base_url("angular/assets/bower_components/ng-file-upload/ng-file-upload.min.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/angular-base64/angular-base64.min.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/angular-ui-tree/dist/angular-ui-tree.js")?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/ngWYSIWYG/js/wysiwyg.js");?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/angular-drag-and-drop-lists-master/angular-drag-and-drop-lists.js");?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/angular-touch/angular-touch.min.js");?>"></script>
    <script src="<?php echo base_url("angular/assets/bower_components/angular-block-ui-master/dist/angular-block-ui.min.js");?>"></script>
    <script src="<?php echo base_url("angular/assets/node_modules/api-check/dist/api-check.min.js");?>"></script>
    <script src="<?php echo base_url("angular/assets/node_modules/angular-formly/dist/formly.min.js");?>"></script>
    <script src="<?php echo base_url("angular/assets/node_modules/angular-formly-templates-bootstrap/dist/angular-formly-templates-bootstrap.min.js");?>"></script>
    
    <!-- keypad -->
      <link rel="stylesheet" href="<?php echo base_url("angular/assets/styles/keypad-numeric.css")?>" type="text/css" />
      <script src="<?php echo base_url("angular/assets/libs/jquery/jquery.2.1.3.js")?>"></script>
      <script src="<?php echo base_url("angular/assets/libs/angular/ngDraggable/ngDraggable.js")?>"></script>
      <script src="<?php echo base_url("angular/assets/libs/angular/ngKeypad/ngKey.js")?>"></script>
      <script src="<?php echo base_url("angular/assets/libs/angular/ngKeypad/ngKeypad.js")?>"></script>
      <script src="<?php echo base_url("angular/assets/libs/angular/ngKeypad/ngKeypadInput.js")?>"></script>
    <!-- end keypad -->
    <script src="<?php echo base_url("angular/assets/node_modules/angular-material-data-table/dist/md-data-table.min.js");?>"></script>
    

    <!-- scrpit global -->
    <script type="text/javascript">
        function open_modal(data,$modal){
           var modalInstance = $modal.open({
                  animation: true,
                  templateUrl: 'views/modal/'+data.template,
                  controller: data.ctrl,
                  size: data.size,
                  resolve: {
                      param: function () {
                          return data.param;
                      }
                  }
              });
        }
        function open_full_modal(data,$modal){
           var modalInstance = $modal.open({
                  animation: true,
                  templateUrl: 'views/modal/'+data.template,
                  controller: data.ctrl,
                  size: data.size,
            windowClass: 'app-modal-window',
                  resolve: {
                      param: function () {
                          return data.param;
                      }
                  }
              });
        }
        function alert_ngnotify(type,msg,ngNotify){
          ngNotify.set(msg, {
            position: 'top',
            theme: 'pure',
            type: type
          });
        }

        function alert_bootsrap (type,msg,scope) {
            scope.alerts = [
              { type: type, msg: msg }
            ];
        }
        
    </script>

  </body>
</html>