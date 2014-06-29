<?php
    require_once CORE_PATH . 'compress/jsminplus.php';
    require_once CORE_PATH . 'compress/css.php';
    require_once CORE_PATH . 'compress/html.php';
  
    if (! GLOBAL_CACHE) {
        $files = glob("cache/*.[0-9]*.*");
        if ($files && gettype($files) == 'array') {
            foreach ($files as $filename) {
                unlink($filename);
            }
        }
    }
  
    /**
     * Compress CSS code
     */
    function compress_css($buffer) {
        $buffer = Minify_CSS_Compressor::process($buffer);
        return $buffer;
    }

    /**
     * Compress HTML
     */
    function compress_html($buffer) {
        $buffer = Minify_HTML::minify($buffer);
        return $buffer;
    }
     
    $content = '';
    
    if (isset ($_GET['type']) && ! empty($_GET['type'])) {
      switch  ($_GET['type']) {
    
      /**
       * Compress CSS
       */
      case 'css': 
          $file_name = SITE_PATH . '/cache/global.' . filemtime(SITE_PATH . '/css/global.css') . '.tpl.css';
          if (! file_exists($file_name)) {
          
            /**
             * Clear cache
             */
            foreach (glob(SITE_PATH . "/cache/*.[0-9]*.css") as $filename) {
                unlink($filename);
            }
            
            $content = file_get_contents(SITE_PATH . '/css/blueprint/screen.css');
            $content .= file_get_contents(SITE_PATH . '/css/smoothness/jquery-ui-1.8.14.custom.css');
            $content .= file_get_contents(SITE_PATH . '/css/global.css');
            
            /**
             * Change images
             */
            //$content = str_replace('sprite.png', 'sprite-' . filemtime(SITE_PATH . '/images/sprite.png') . '.png', $content);
            
            if (GLOBAL_COMPRESS) {
                $content = compress_css($content);
            }
            
            file_put_contents($file_name, $content);
            
          } else {
            $content = file_get_contents($file_name);
          }

          $last_modified_time = filemtime(SITE_PATH . '/css/global.css'); 
          header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT"); 
          
          header("Content-type: text/css; charset: UTF-8");
          header('Expires: Fri, 21 Dec ' . (date('Y') + 1) . ' 00:00:00 GMT');
          echo $content;
        break;

      /**
       * Compress JavaScript
       */
      case 'js':
          
          $file_name = SITE_PATH . '/cache/global.' . filemtime(SITE_PATH . '/js/global.js') . '.tpl.js';
          if (! file_exists($file_name)) {
            /**
             * Clear cache
             */
            foreach (glob("../cache/*.[0-9]*.js") as $filename) {
                unlink($filename);
            }
            $content = file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js') . PHP_EOL;
            $content .= file_get_contents('http://html5shiv.googlecode.com/svn/trunk/html5.js') . PHP_EOL;
            $content .= file_get_contents(SITE_PATH . '/js/jquery-ui-1.8.14.custom.min.js') . PHP_EOL;
            $content .= file_get_contents(SITE_PATH . '/js/jquery.maskedinput-1.3.min.js') . PHP_EOL;
            
            if (GLOBAL_COMPRESS) {
                $content .= JSMinPlus::minify(file_get_contents(SITE_PATH . '/js/global.js'));
            } else {
                $content .= file_get_contents(SITE_PATH . '/js/global.js');
            }


            
            file_put_contents($file_name, $content);
          } else {
            $content = file_get_contents($file_name);
          }
          
          $last_modified_time = filemtime(SITE_PATH . '/js/global.js'); 
          header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT"); 

          header("Content-type: application/javascript; charset: UTF-8");
          header('Expires: Fri, 21 Dec ' . (date('Y') + 1) . ' 00:00:00 GMT');
          echo $content;
        break;

      /**
       * Compress PNG
       */
      case 'png':
          $image_file = SITE_PATH . '/images/' . $_GET['filename'] . '.png';
          $content = file_get_contents($image_file);

          $last_modified_time = filemtime($image_file); 
          header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT"); 

          header('Content-Length: ' . filesize($image_file));
          header('Expires: Fri, 21 Dec ' . (date('Y') + 1) . ' 00:00:00 GMT');
          header('Content-Type: image/png');
          echo $content;
        break;
    }
  }