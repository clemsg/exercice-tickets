<?php

namespace SrcTickets\Services;

class TicketsService {
    
    private $dao;
    
    public function __construct($dao) {
        
        $this->dao = $dao;
    }
    
    public function insertCsv(){
        
        $path = __DIR__ . '/../../web/csv/';
        $insert = array();
        $msg = null;
        
        /*
         * ouverture du fichier csv
         */
        if(($f = fopen($path . 'tickets_appels_201202.csv', 'r')) !== false){
            
            while(($data = fgetcsv($f, 1000, ";")) !== false){
                /*
                 * on boucle et on regarde la 1ere ligne qui nous interesse
                 */
                if (is_numeric($data[0])) {
                    
                    /*
                     * on parse la date en format americain
                     */
                    $date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data[3]). ' '.$data[4]));
                    $data[7] = utf8_encode($data[7]);
                    
                    /*
                     * controle sur les type de données pour eviter de charger un tableau
                     * de 100 000 lignes... (rappel de la memory_limit à 128MO)
                     * du coup oblige de faire un pre-traitement avant l'insertion en base
                     */
                    switch(substr($data[7], 0, strpos($data[7], ' '))){
                        
                        /*
                         * pour les connection 3G etc..
                         */
                        case 'connexion':
                            
                            /*
                             * on insert juste les connections de la tranche horaire 8h00 - 18h00
                             */
                            $currentTime = date('H:i:s', strtotime($data[4]));
                            $timeStart = date('H:i:s', strtotime('08:00:00'));
                            $timeStop = date('H:i:s', strtotime('18:00:00'));

                            if($currentTime <= $timeStart || $currentTime >= $timeStop){

                                $insert[]= array(
                                    (int)$data[0],
                                    (int)$data[1],
                                    (int)$data[2],
                                    $date,
                                    $data[5],
                                    $data[6],
                                    $data[7]
                                );
                            }
                            
                            
                        break;
                        
                        /*
                         * on passe aux appels (et non rappels)
                         */
                        case 'appel':
                        case 'appels':
                            
                            /*
                             * insertion juste des appels à partir du 15/02/2012
                             * pour alleger l'insertion en base toujours
                             */
                            $date1 = new \DateTime(str_replace('/', '-', $data[3]));
                            $date2 = new \DateTime('2012-02-15');
                            
                            if($date1 >= $date2){
                                $insert[]= array(
                                    (int)$data[0],
                                    (int)$data[1],
                                    (int)$data[2],
                                    $date,
                                    $data[5],
                                    $data[6],
                                    $data[7]
                                );
                            }
                        break;
                        
                        
                        default:
                            
                            /*
                             * traitement des sms car ils n'ont pas de volumes ni de durées donc champ vide
                             */
                            if($data[5] == ''){
                                $insert[]= array(
                                    (int)$data[0],
                                    (int)$data[1],
                                    (int)$data[2],
                                    $date,
                                    $data[5],
                                    $data[6],
                                    $data[7]
                                );
                            }
                        break;
                            
                    }
                    
                    
                }
            }
            fclose($f);
            
            /*
             * après se traitement on passe de 100 000 données à a peu pres 50 000
             */
            $this->dao->insertCsv($insert);
            
            $msg = "Fichier csv bien importé";
            
        }else{
            $msg = "Il y a eu un problème lors de l'appel du fichier";
        }
        
        return $msg;
    }

}
