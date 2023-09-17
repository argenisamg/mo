<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="assets/css/wiwynn-icon.css"> -->
    <link rel="icon" href="assets/img/wiwynn-icon.png" type="image/png">
    <title>MO Comparator</title>
    <!-- <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="assets/css/style-mo.css">
    <link rel="stylesheet" href="assets/css/loading.css">
    <!-- <script src="https://kit.fontawesome.com/cace28c713.js" crossorigin="anonymous"></script> -->
</head>
<style>   
   /* Fondo modal: negro con opacidad al 50% */
   .modal {
        display: none; /* Por defecto, estará oculto */
        position: fixed; /* Posición fija */
        z-index: 1; /* Se situará por encima de otros elementos de la página*/
        padding-top: 20rem; /* El contenido estará situado a 200px de la parte superior */
        left: 0;
        top: 0;
        width: 100%; /* Ancho completo */
        height: 100%; /* Algura completa */
        overflow: auto; /* Se activará el scroll si es necesario */
        background-color: rgba(0,0,0,0.5); /* Color negro con opacidad del 50% */
    }    
    /* Ventana o caja modal */
    .contenido-modal {
        position: relative; /* Relativo con respecto al contenedor -modal- */
        background-color: white;
        border-radius: 4px;
        margin: auto; /* Centrada */
        padding: 10px;
        width: 90%;
        -webkit-animation-name: animarsuperior;
        -webkit-animation-duration: 0.5s;
        animation-name: animarsuperior;
        animation-duration: 0.5s
    }

    .contenido-modal h2 {
        text-align: center;
        color: #565758;
    }

    /* Animación */
    @-webkit-keyframes animatetop {
        from {top:-300px; opacity:0} 
        to {top:0; opacity:1}
    }

    @keyframes animarsuperior {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
    }

    /* Botón cerrar */
    .modal-header {
        display: flex;
        flex-direction: column;
    }    

    .close {
        color: #000000;
        float: left;
        font-size: 30px;
        font-weight: bold;
    }    
    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .button-modal {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }    
    /*Loader*/
    .content-spinner {        
        display: block;
        position: fixed; /* Posición fija */
        z-index: 1; /* Se situará por encima de otros elementos de la página*/
        
        left: 0;
        top: 0;
        width: 100%; /* Ancho completo */
        height: 100%; /* Algura completa */        
        background-color: rgba(0,0,0,0.5); /* Color negro con opacidad del 50% */

        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }    
</style>
<body>    
    <header>          
        <!-- <div class="contenedor-navegacion"> -->
            <div class="nav-principal container-principal-amg">
                <nav class="nav-navegacion">
                    <img src="assets/img/wiwynnsmallwhite.png" alt="Wiwynn" class="img">
                    <p class="a-nav">MO Comparison</p>                                          
                </nav>                    
            </div>
        <!-- </div>  -->
    </header>                                          
    
    <div class="container-principal-amg">
        <div class="container-amg">
            <!-- <div class="formulario-container"> -->
                <div class="general-information">
                    <div class="labels-description">                    
                            <label>PART NUMBER: <span id="upnactual"></span></label>      
                            <label>MO ACTUAL: <span id="moactual"></span></label>                        
                            <label>DATE: <span id="datemoactual"></span></label>                                                      
                            <label>NEXT COMPARISON: <span id="hora"></span></label>      
                    </div>
                    <input type="button" name="btnsummary" id="btnsummary" class="btnsummary" value="Summary">
                </div>
                <fieldset class="fieldset-amg">
                    <legend class="legend-indicator-center">RESULTS AREA</legend>
                    <br/>                                             
                    <!-- space for MO's shapes -->
                    <div id="group-inter" class="group-inter"></div>                                                                                                          
                </fieldset>

                <!-- loading -->
                <div class="content-spinner">
                    <div id="loading" class="loadingio-spinner-ellipsis-oslofo8v1d">
                        <div class="ldio-r1ougxposrf">
                            <div></div><div></div><div></div><div></div><div></div>
                        </div>
                    </div>
                </div>

                <!-- Modal result tables -->
                <div id="ventanaModal" class="modal">
                    <div class="contenido-modal">
                        <span class="close" id="close">&times;</span>
                        <div class="modal-header">
                            <h2>Results Comparison</h2>
                            <hr class="horizontal-line">                            
                        </div>
                        <div class="tablesAreaResult" id="resultsTables">     
                            <!-- Comparative table one -->             
                            <div>
                                <label id="lblMaster">MO Master</label> 
                                <table class="table">
                                    <thead>
                                        <th>UPN</th>
                                        <th>MO</th>
                                        <th>POSITION</th>
                                        <th>CPN</th>
                                        <th>CREATE DATE</th>
                                        <th>CATEGORY</th>
                                        <th>DESCRIPTION</th>                                    
                                    </thead>
                                    <tbody id="tableone">                                                                                                                 
                                    </tbody>
                                </table>         
                            </div>     
                            
                            <!-- Comparative table two -->
                            <div>
                                <label id="lblMaster">MO Comparison</label>
                                <table class="table">
                                    <thead>
                                        <th>UPN</th>
                                        <th>MO</th>
                                        <th>POSITION</th>
                                        <th>CPN</th>
                                        <th>CREATE DATE</th>
                                        <th>CATEGORY</th>
                                        <th>DESCRIPTION</th>                                     
                                    </thead>
                                    <tbody id="tabletwo">                                                                                                             
                                    </tbody>
                                </table>     
                            </div>                                                                                         
                        </div>   <!--End tablesAreaResult-->
                        <hr class="horizontal-line">
                        <div class="button-modal">
                            <input type="button" name="closemodal" id="closemodal" class="closemodal" value="Close">
                        </div>
                    </div>
                </div>           
                <!-- End modal Result tables  -->

                <!-- Modal summary -->                
                <div id="modalSummary" class="modal">
                    <div class="contenido-modal">
                        <span class="close" id="close">&times;</span>
                        <div class="modal-header">
                            <h2>Summary of Results</h2>
                            <hr class="horizontal-line">                            
                        </div>                                 
                                <table class="table">
                                    <thead>
                                        <th>UPN</th>
                                        <th>MO Master</th>
                                        <th>CREATE DATE</th>                                                                         
                                        <th>MO Comparison</th>
                                        <th>CREATE DATE</th>                                        
                                    </thead>
                                    <tbody id="tabodysummary"></tbody>
                                </table>                                                                                                                                                                                                           
                        <hr class="horizontal-line">
                        <div class="button-modal">
                            <input type="button" name="closemodalsummary" id="closemodalsummary" class="closemodalsummary" value="Close">
                        </div>
                    </div>
                </div>
                <!-- End Modal summary -->

        </div> <!--container-->
    </div> <!--container-principal-->    
    <footer>
        <!-- <div class="expansion"></div> -->
        <div>            
            <img src="assets/img/logo30.png" alt="Wiwynn Mx">
            <p>© 2023 Wiwynn, TE Department, Inc. All Rights Reserved | Developed by Argenis Munoz</p>            
        </div>
    </footer>     
    
    <script src="assets/js/shapecreate.js"></script>    
    <!-- <script src="assets/bootstrap/js/bootstrap.min.js"></script> -->
    </body>
</html>