<?php

namespace App\Presenters;

/**
 * 
 * @what Application CRON jobs
 * @author Tomáš Keske a.k.a клустерфцк
 * @copyright 2015-2016
 * 
 */

class CronPresenter extends BasePresenter {
        
    /**
     * Determines if we are running
     * from CLI, otherwise terminates.
     */
    public function __construct(){
        if (PHP_SAPI !== "cli"){            
           $this->terminate();
       }
    }

    /**
     * Job that finalizes order
     * and releases funds from escrow.
     * 
     * @param int $oid order id
     */
    public function actionAutoFinalize() {  
        
        touch("/var/www/html/www/userfiles/kak.txt");
        
        $oid = intval($GLOBALS["argv"][2]);
        $vendor = $this->orders->getDetails($oid)["author"];
        $ammount = $this->wallet->getEscrowed_Order($oid);
        $this->wallet->moveAndStore("erelease","escrow",$vendor,$ammount,$oid);
        $this->orders->autoFinalize($oid);  
    }
}