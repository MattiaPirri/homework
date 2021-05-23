<?php
    //Spotify
    $ClientID = '9056e8a07e564f5eb78151fe419c5767';
    $ClientSecret = 'c9fb854392464f1b8291bf5bf79c94ae';
    //Google books
    $key = 'AIzaSyCU5OwCgYEeCrXjksIiSjvdcOEXEjZGft8';
    $token;
    //Questa funzione la chiamo in tutti i file che hanno bisogno di ottenre l'immagine e/o la descrizione del prodotto
    function completa_prodotto($row){
        global $ClientID, $ClientSecret, $key, $token;
        if($row['categoria']=="Musica"){
            if(!isset($token)){
                // ACCESS TOKEN
                $ch = curl_init();
                //Imposto l'url dell'endpoint
                curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token' );
                //Restituisci il risultato come stringa
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //Specifico il metodo post
                curl_setopt($ch, CURLOPT_POST, 1);
                # Setto body e header della POST come da documentazione Spotify
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials'); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($ClientID.':'.$ClientSecret))); 
                //eseguo la richiesta e decodifico il risultato come array associativo
                $token= json_decode(curl_exec($ch), true);
                curl_close($ch);  
            }
            $query = urlencode($row["Nome"]);
            $url = 'https://api.spotify.com/v1/search?type=track&q='.$query;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            # Imposto il token
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'])); 
            $res_fetch=curl_exec($ch);
            curl_close($ch);
            $json = json_decode($res_fetch);
            //var_dump($json);
            $row['src']=$json->{'tracks'}->{'items'}[0]->{'album'}->{'images'}[0]->{'url'};
            //print_r($json->{'tracks'}->{'items'}[0]->{'album'}->{'images'}[0]->{'url'});
            //print_r($row);
            /* Javascript
            descrizione.textContent = "Album: " + json.tracks.items[0].album.name +
            "\r\nDurata: " + Math.floor(json.tracks.items[0].duration_ms / 1000 / 60) + ":" +
            Math.floor((json.tracks.items[0].duration_ms / 1000) % 60);
            */
            $row['Descrizione'] = "Album: ".$json->{'tracks'}->{'items'}[0]->{'album'}->{'name'}.
                "\r\nDurata: ".floor($json->{'tracks'}->{'items'}[0]->{'duration_ms'}/1000/60).":".
                floor(($json->{'tracks'}->{'items'}[0]->{'duration_ms'}/1000)%60);
        }else if($row['categoria']=="Libri"){
            //print_r($row);
            $q=urlencode($row['Nome']);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL,"https://www.googleapis.com/books/v1/volumes?printType=books&key=$key&q=$q");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
            $json = json_decode(($result));
            $row['src'] = $json->{'items'}[0]->{'volumeInfo'}->{'imageLinks'}->{'thumbnail'};
            $row['Descrizione']= $json->{'items'}[0]->{'searchInfo'}->{'textSnippet'};
            //echo $row['descrizione'];
        }else{
            $row['src'] = "img/products/".$row['EAN'].".jpeg";
        }
        return $row;
    }
?>