<?php
if(!function_exists('getpw'))
{

    function getpw($path,$user){
        $token = trim(file_get_contents('/var/lib/vault/token.webapps_ro'));
        
        
        $baseURL='https://vault.ics.uci.edu:8200/v1/';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_HTTPHEADER,["X-Vault-Token: ".$token]);
        curl_setopt($ch, CURLOPT_URL, $baseURL.$path);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ans=curl_exec($ch);

        $pass=json_decode($ans);
        return $pass->data->data->{$user};
    }
}
