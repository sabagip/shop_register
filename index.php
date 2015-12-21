<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
    require_once 'lib/soap/nusoap.php';
    $server = new soap_server();
    $server->configureWSDL('hellowsdl2', 'urn:hellowsdl2');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>HOLA MUNDO </h1>
        <?php 
            $server->wsdl->addComplexType(
                    'Person', 'complexType',
                    'struct', 'all', '',
                    array(
                        'firstname'=> array(    
                                            'name'      =>  'firstname',
                                            'type'      =>  'xsd:string',
                                            'age'       =>  array(
                                                                    'name'      =>      'age',
                                                                    'age'       =>      'xsd:int',
                                                                    ),
                                            'gender'      =>  array(
                                                                    'name'      =>      'gender',
                                                                    'type'      =>      'xsd:string'
                                                                    ),
                                            )
                        )
                    );
            
            //Parametros de salida
            $server->wsdl->addComplexType(
                        'SweepstakesGretting',
                        'ComplexType',
                        'struct',
                        'all',
                        '',
                        array(
                                'gretting' => array(
                                                        'name' => 'gretting',
                                                        'type'  =>  'xsd:string'
                                                    ),
                                'winner'    => array(
                                                        'name' => 'winner',
                                                        'type'  =>  'xsd:boolean'
                                                    )
                            )
                    );
            
            $server->register('hello', //method name
                                array('person' => 'tns:Person'),        // input parameters
                                array('return' => 'tns:SweepstakesGreeting'),    // output parameters
                                'urn:hellowsdl2',                // namespace
                                'urn:hellowsdl2#hello',                // soapaction
                                'rpc',                        // style
                                'encoded',                    // use
                                'Greet a person entering the sweepstakes'    // documentation
                    
                    );
            
            function hello($person) {
                global $server;

                $greeting = 'Hello, ' . $person['firstname'] .
                '. It is nice to meet a ' . $person['age'] .
                ' year old ' . $person['gender'] . '.';

                if (isset($_SERVER['REMOTE_USER'])) {
                $greeting .= '  How do you know ' . $_SERVER['REMOTE_USER'] . '?';
                }

                $winner = $person['firstname'] == 'Scott';

                return array(
                'greeting' => $greeting,
                'winner' => $winner
                );
            }
            
            $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
            $server->service($HTTP_RAW_POST_DATA);
        ?>
    </body>
</html>
