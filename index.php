<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="assets/css/wiwynn-icon.css"> -->
    <link rel="icon" href="assets/img/wiwynn-icon.png" type="image/png">
    <title>MO Comparator</title>    
    <link rel="stylesheet" href="assets/css/style-mo.css">
    <link rel="stylesheet" href="assets/css/loading.css">
    <!-- <script src="https://kit.fontawesome.com/cace28c713.js" crossorigin="anonymous"></script> -->
</head>
<style>
    .info {
        color: #ffffff;
        text-decoration: none;
    }
</style>
<body>    
    <header>          
        <!-- <div class="contenedor-navegacion"> -->
            <div class="nav-principal container-principal-amg">
                <nav class="nav-navegacion">
                    <img src="assets/img/wiwynnsmallwhite.png" alt="Wiwynn" class="img">
                    <p class="a-nav">MO Comparison</p>  
                    <!-- <a href="#" id="info"><i class="fas fa-thin fa-circle-info"></i></a> -->
                    <p class="a-nav"><a href="#" id="info" class="info">About</a></p>
                </nav>    
                <div class="formulario-uno">
                    <form id="formulario-pn" class="formulario-pn">
                        <div class="search-section">
                            <label for="txtSearchMo">L10 Line:</label>
                            <select class="line-option" name="line-select" id="line-select" onchange="setSelection(this);" required>
                                <option value="">-</option>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="B3">B3</option>
                                <option value="B4">B4</option>
                                <option value="B5">B5</option>
                            </select>
                            <label for="txtSearchMo">PN:</label>
                            <input type="text" name="txtSearchMo" id="txtSearchMo" value="" placeholder="Type here L10 PN..." required>                        
                            <input type="submit" name="btnSearchPn" id="btnSearchPn" value="Consult" style="display: block;">
                                <!-- loading -->
                                <div id="loading" style="display: none;" class="loadingio-spinner-ellipsis-oslofo8v1d">
                                    <div class="ldio-r1ougxposrf">
                                        <div></div><div></div><div></div><div></div><div></div>
                                    </div>
                                </div>
                        </div>
                    </form>                     
                </div>                       
            </div>
        <!-- </div>  -->
    </header>                                          
    
    <div class="container-principal-amg">
        <div class="container-amg">            
                <fieldset>
                    <legend class="legend-indicator">Search MO</legend>
                    <form id="formulario-mo" class="formulario-mo">  
                        <label for="mo-select">MO number programming:</label>
                        <select class="mo-option" name="mo-select" id="mo-select" onchange="setSelectionMo(this);" required>
                            <option value="">-</option>
                        </select>                     
                        <label for="date-select">Created date MO:</label>
                        <select class="date-option" name="date-select" id="date-select" onchange="setSelectionDate(this);" required>
                            <option value="">-</option>
                        </select>
                        <!-- <input type="date" id="dateCreatedMo" name="dateCreatedMo" required> -->
                        <div class="button-group">                                                    
                            <div class="group-inter-button">
                                <input type="reset" name="btnClear" id="btnClear" value="Clear">
                                <input type="submit" name="btnSearch" id="btnSearch" value="Compare">
                            </div>
                        </div>                                                                             
                    </form>
                </fieldset>                     

                <div class="tablesAreaResult" id="resultsTables" >     
                    <!-- Comparative table one -->             
                    <div>
                        <label id="lblMaster">MO Master:</label> 
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
                            <tbody id="tableOne">                                                                                                                 
                            </tbody>
                        </table>         
                    </div>     
                    
                    <!-- Comparative table two -->
                    <div>
                        <label id="lblMaster">MO Comparison:</label>
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
                            <tbody id="tableTwo">                                                                                                             
                            </tbody>
                        </table>     
                    </div>                                                                                         
                </div>   <!--End tablesAreaResult-->

                <div id="circles-container" class="circles-container">     
                    <div class="circle-text circles-container">
                        <div id="circleResult" class="circle circle-5">
                            <span id="spanRes"></span>
                        </div>                    
                    </div>                        
                </div> 
                           
        </div> <!--container-->
    </div> <!--container-principal-->  
      
    <footer>
        <!-- <div class="expansion"></div> -->
        <div>            
            <img src="assets/img/logo30.png" alt="Wiwynn Mx">
            <p>© 2023 Wiwynn, TE Department, Inc. All Rights Reserved | Developed by Argenis Munoz</p>            
        </div>
    </footer>   
    <script src="assets/js/getjson.js"></script>          
    </body>
</html>