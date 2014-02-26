<?php

# /app/Controller/HomeController.php

class UrlController extends AppController {

    public $name = 'Url';
    public $uses = array('Url','Email');
    

    public function admin_index() {
        
    }
   
    public function incluiEmails( $emails )
    {
        foreach( $emails as $resultado ){
           print_r( $resultado );
           $array_email = array();
           $conditions_email = "'" . $resultado . "'";
           
           if( $this->Email->find('count', array('conditions' => "Email.email = " . $conditions_email )  ) == 0 )
           {
                $array_email['email'] = $resultado;


                $this->Email->create();
                if( ! $this->Email->save( $array_email ) )
                    echo "Houve um erro ao incluir a email: " . $resultado . " ! \n\n";
                else
                    echo "URL: " . $resultado . " incluída com sucesso! \n\n";
           }
           else
           {
                echo "Emaill: " . $resultado . " já está incluída no banco de dados! \n\n";
           }
       }
    }
    
    public function incluiSubSublinks( $links, $domain ){
        foreach( $links as $resultado ){
            
            if( strlen( $resultado ) <= 3 )
               continue;
            
            $array_url['url'] = $resultado;
            $array_url['domain'] = $domain;
            $conditions_url = "Url.url = '" . addslashes( $resultado ) . "'";
            if( $this->Url->find('count', array('conditions' => $conditions_url )  ) == 0 )
            {
                $this->Url->create();
                if( ! $this->Url->save( $array_url ) )
                    echo "Houve um erro ao incluir a url: " . $resultado . " ! \n\n";
                else
                    echo "URL: " . $resultado . " incluída com sucesso! \n\n";
            }
            else
            {
                echo "URL: " . $resultado . " já está incluída no banco de dados! \n\n";
                  
            }
        }
    }
    
    public function incluiSublinks( $links, $domain ){
        
        foreach( $links as $resultado ){
           
           if( strlen( $resultado ) <= 3 )
               continue;
            
           $array_url = array();
           $conditions_url = "Url.url = '" . addslashes( $resultado ) . "'";
           
           if( $this->Url->find('count', array('conditions' =>  $conditions_url )  ) == 0 )
           {
                $array_url['url'] = $resultado;
                $array_url['domain'] = $domain;

                
                $this->Url->create();
                if( ! $this->Url->save( $array_url ) )
                    echo "Houve um erro ao incluir a url: " . $resultado . " ! \n\n";
                else
                    echo "URL: " . $resultado . " incluída com sucesso! \n\n";
                
                
                $suburls = $this->Url->find('all', array('conditions' => "Url.visited = 'no'" ) );
                foreach( $suburls as $suburl ){
                    
                    if( strstr( $suburl['Url']['url'], 'http://' ) || strstr( $suburl['Url']['url'], 'https://' ) )
                        $link =  $suburl['Url']['url'];
                    else
                        $link = 'http://' . $suburl['Url']['domain'] . '/' .  $suburl['Url']['url'];

                    $conteudo = $this->getPagina( $link );
                    $this->setVisited($suburl['Url']['id']);
                    if( ! $conteudo )
                        continue;
                    
                    preg_match_all('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $conteudo, $resultados);
                    
                    echo "<br><br>Lista de emails: ";
                        print_r( $resultados );
                        echo "<br><br>------------------------------<br><br>";
                    if( isset( $resultados[0][0] ) )
                         $this->incluiEmails( $resultados[0] );
                    
                    preg_match_all('/<a href=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?>/i', $conteudo, $resultados);
                    
                    $domain = $this->getDommain( $suburl['Url']['url'] );
                    if( empty( $domain ) )
                        $domain = $suburl['Url']['domain'];
                    
                    $this->incluiSubSubLinks( $resultados[1], $domain );
                    
                    
                }
                
           }
           else
           {
                echo "URL: " . $resultado . " já está incluída no banco de dados! \n\n";
                
           }
       }
        
    }
    
    
    public function incluiLinks( $links, $domain )
    {
        foreach( $links as $resultado ){
           if( strlen( $resultado ) <= 3 )
               continue;
           
           $array_url = array();
           $conditions_url = "Url.url = '" . addslashes( $resultado ) . "'";
           
           if( $this->Url->find('count', array('conditions' =>  $conditions_url )  ) == 0 )
           {
                $array_url['url'] = $resultado;
                $array_url['domain'] = $domain;

                
                $this->Url->create();
                if( ! $this->Url->save( $array_url ) )
                    echo "Houve um erro ao incluir a url: " . $resultado . " ! \n\n";
                else
                    echo "URL: " . $resultado . " incluída com sucesso! \n\n";
                
                
                $suburls = $this->Url->find('all', array('conditions' => "visited = 'no'" ) );
                if( sizeof( $suburls ) > 0 ){
                    foreach( $suburls as $suburl ){
                        
                        if( strlen( $suburl['Url']['url'] ) <= 3 )
                        {
                            $this->setVisited($suburl['Url']['id']);
                            continue;
                        }
                        
                        if( strstr( $suburl['Url']['url'], 'http://' ) || strstr( $suburl['Url']['url'], 'https://' ) )
                            $link =  $suburl['Url']['url'];
                        else
                            $link = 'http://' . $suburl['Url']['domain'] . '/' .  $suburl['Url']['url'];

                        $conteudo = $this->getPagina( $link );
                        $this->setVisited($suburl['Url']['id']);
                        if( ! $conteudo )
                            continue;

                        preg_match_all('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $conteudo, $resultados);
                        
                        echo "<br><br>Lista de emails: ";
                        print_r( $resultados );
                        echo "<br><br>------------------------------<br><br>";
                        
                        if( isset( $resultados[0][0] ) )
                             $this->incluiEmails( $resultados[0] );

                        preg_match_all('/<a href=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?>/i', $conteudo, $resultados);

                        $domain = $this->getDommain( $suburl['Url']['url'] );
                        if( empty( $domain ) )
                            $domain = $suburl['Url']['domain'];


                        $this->incluiSubLinks( $resultados[1], $domain );


                    }
                }
                else
                {
                    echo "Nenhuma URL que não tenha sido visitada foi encontrada! \n\n";
                }
                
           }
           else
           {
                echo "URL: " . $resultado . " já está incluída no banco de dados! \n\n";
                
                /*$suburls = $this->Url->find('all', array('conditions' => 'visited = no' ) );
                print_r( $suburls );
                foreach( $suburls as $suburl ){
                    //$conteudo = $this->getPagina( $suburl['Url']['url'] );
                    print_r( $suburl );
                    //$this->setVisited($url['Url']['id']);
                }*/
                
           }
       }
    }
    
    public function setVisited( $id ){
        $this->Url->id = $id;
        $this->Url->saveField('visited', 'yes');
    }   
    
    public function crawler() {
       $this->autoLayout = false;
       $this->autoRender = false;
        
       $conditions = "Url.visited='no'";
       $url = $this->Url->find('first', array('conditions' => $conditions) );
       
       if( sizeof( $url ) > 0 )
       {
            $conteudo = $this->getPagina( $url['Url']['url'] );
            $this->setVisited($url['Url']['id']);

            preg_match_all('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $conteudo, $resultados);
            echo "<br><br>Lista de emails: ";
            print_r( $resultados );
            echo "<br><br>------------------------------<br><br>";
            
            if( isset( $resultados[1] ) )
                 $this->incluiEmails( $resultados[1] );
            
            preg_match_all('/<a href=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?>/i', $conteudo, $resultados);
            $domain = $this->getDommain( $url['Url']['url'] );
            $this->incluiLinks( $resultados[1], $domain );

            
       }
       else {
           echo "Nenhuma URL que não tenha sido visitada foi encontrada!\n\n";
       }
       
        
    }
    
    public function getDommain( $url ){
        
        if( strstr( $url, 'http://' ) ){
            $url = str_replace('http://', '', $url );
            $domain = explode( '/', $url );
            
        }
        else if( strstr( $url, 'https://' ) ){
            $url = str_replace('https://', '', $url );
            $domain = explode( '/', $url );
        }
        else
        {
            $domain = explode( '/', $url );
        }
        return $domain[0];
    }
    
    function getPagina( $url )
    {
            ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
            $conecurl = @fopen("$url","r");
            
            if( ! $conecurl )
                return false;

            $lin="";$dados="";
            while(!feof($conecurl)) {
            $lin .= fgets($conecurl,4096);
            }
            fclose($conecurl);

            return $lin;
    }


}

?>