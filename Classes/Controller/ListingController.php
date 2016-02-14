<?php
namespace Linuxmce\Linuxmcedownloads\Controller;

class ListingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {    

    public function showAction(){

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($url);

        $url = $this->settings['url'];
        $sslCheckDisable = $this->settings['sslCheckDisable'];
        $hideFilesTypes = $this->settings['hideFilesTypes'];
        $hideFilesTypes = preg_split( "/(,|;| )/", $hideFilesTypes);
        $showMd5 = $this->settings['showMd5'];
        $sort = $this->settings['sort'];
        $sortDirection = $this->settings['sortDirection'];
           
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if($sslCheckDisable = true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);            
        }

        $dataDir = curl_exec($ch);
        curl_close($ch);

		$release=explode("<pre>", $dataDir);
		$release=explode("</pre>",$dataDir);
		
		$content = str_replace('<a href="../">../</a>','',$release[0]);
		
		$content = explode("\n", strip_tags($content));

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($content);
        $content = array_slice($content, 4, -1);

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($content);
		
		$content = array_reverse($content); 

        $downloads = array();
        foreach($content as &$value) {
          preg_match('/\.(.*?) /s', $value, $matches);
          
          $array = explode('.', $matches[0]);
          $extension = trim(end($array));

          if(!in_array($extension, $hideFilesTypes)) {
                                      
            $line = explode(' ', $value);
            $line = array_values(array_filter($line));
            
            
            if($showMd5 == true) {
                $md5file = $url . $line[0] . '.md5';
                $ch = curl_init();
                $timeout = 5;
                curl_setopt($ch, CURLOPT_URL, $md5file);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                if($sslCheckDisable = true) {
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);            
                }
                
                $dataMd5 = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if($httpCode == 404) {
                    $md5 = "No MD5 file available";
        		} else {
                    $md5 = trim(str_replace($line[0],'',$dataMd5));
            		$insertion = "&shy;";
            		$index = 16;
            		$md5 = substr_replace($md5, $insertion, $index, 0);        		
        		}
            } else {
                $md5 = '';
            }            
                        
            $name = str_replace('-', '&shy; ', $line[0]);
    		$date = str_replace('-', '&shy; ', $line[1]);

            $size = $line[3] / 1024 / 1024 / 1024;
            $size = round($size, 2);

            $urlFile = $url . $line[0];
            
            if($sort == 'date') {
                $key = strtotime($line[1] . ' 00:00');
            } elseif($sort == 'name') {
                $key = (string)$name;
            } elseif($sort == 'size') {
                $key = (string)$size;                
            }
                       
            $downloads[$key] = ['name' => $name, 'date' => $date, 'size' => $size, 'md5' => $md5, 'url' => $urlFile];
            
//            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($line);
          }
        }
        ksort($downloads);

        if($sortDirection == 'asc') {
            $downloads = array_reverse($downloads);
        }
        $this->view->assign('downloads',$downloads);
    }
}