<?php

namespace SrcTickets\Repository;

use Doctrine\DBAL\Connection;

class TicketsRepository {
    
    private $db;

    public function __construct(Connection $db)
    {
            $this->db = $db;
    }

    /*
     *  même principe que le singleton, sauf que le chargement du constructeur 
     * se fait dans le fichier app de silex au lieu d'avoir une classe statique
     */
    protected function getDb()
    {
            return $this->db;
    }

    public function insertCsv($tab){
        
        /*
         * on vide la table avant si il y a eu deja une insertion
         */
        $this->getDb()->executeUpdate('TRUNCATE TABLE donnees_abonnes');
        $insert = array();
        
        /*
         * on protege les données avec des quotes
         */
        foreach($tab as $value){
            foreach($value as &$d){
                $d = $this->getDb()->quote($d, \PDO::PARAM_STR);
            }
            /*
             * preparation du multi values pour eviter le trop plein de requetes
             */
            $insert[]= '('.implode(',', $value).')';
        }
        
        /*
         * obliger d'exploser le tableau pour diviser les insert pour eviter le crash
         * de mysql donc a peu pres 10 requetes de 5000 values ce qui à l'air correct
         */
        foreach(array_chunk($insert, 5000) as $tab){
            $this->getDb()->executeUpdate('INSERT INTO donnees_abonnes (compte, facture, abonne, date_heure, duree_reel, duree_facture, type) VALUES '.implode(',', $tab));
        }
        
        
    }
    
    /*
     * requete pour obtenir la somme des appels mais j'ai un doute sur la conversion
     * de la fonction SECOND de mysql... du coup je fais une conversion du temps
     * en php
     */
    public function getAppel(){
        $sql = "SELECT duree_reel "
                . "FROM donnees_abonnes "
                . "WHERE type LIKE ? "
                . "AND type NOT LIKE ?";
        
        $result = $this->getDb()->fetchAll($sql, array('appel%', '%reçu%'));
        
        $i = 0;
        foreach($result as $appel){
            if($appel['duree_reel'] != ''){
                $date = \DateTime::createFromFormat('H:i:s',$appel['duree_reel']);
                if(is_object($date)){
                    
                    /*
                     * conversion deja des horaires en seconde puis incrementation
                     * des secondes pour avoir le total
                     */
                    $h = $date->format('H')*3600;
                    $m = $date->format('i')*60;
                    $s = $date->format('s');
                    $i += ($h+$m+$s);
                }
            }

        }
        
        /*
         * reconversion total des secondes en horaire
         */
        $temp = $i % 3600;
        $time[0] = ( $i - $temp ) / 3600 ;
        $time[2] = $temp % 60 ;
        $time[1] = ( $temp - $time[2] ) / 60; 
        
        return "{$time[0]}H {$time[1]}m {$time[2]}s";
    }
    
    /*
     * requete pour le top10 (petite feinte avec une multiplication par 1 pour faire 
     * un order by numerique quand le type du champ est un varchar)
     */
    public function getDataFactures(){
        $sql = "SELECT * "
                . "FROM donnees_abonnes "
                . "WHERE type LIKE ? "
                . "GROUP BY abonne "
                . "ORDER BY duree_facture * 1 DESC "
                . "LIMIT 10";
        
        $result = $this->getDb()->fetchAll($sql, array('connexion%'));
        
        return $result;
    }
    
    /*
     * requete pour le total des sms (petit détail mais je ne suis pas certain 
     * pour les 'suivi conso #123#' si il faut les prendre en compte, est-ce l'envoi
     * ou la reception ???)
     */
    public function getSms(){
        
        $sql = "SELECT COUNT(id) AS total "
                . "FROM donnees_abonnes "
                . "WHERE type LIKE ? ";
        
        $result = $this->getDb()->fetchAssoc($sql, array('envoi%'));
        
        return $result;
    }
}
