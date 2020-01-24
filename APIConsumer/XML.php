<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <title>CompanyEmployeesApi</title>
         <link rel = "stylesheet" type = "text/css" href = "css.css" />
        
        

        
           
        <?php
  
       
        
       // echo $informationRecived;
        ?>
        
        
    </head>
    <body>
        
     
     <div><a href="JSON.php"><p>See Information sent in JSON form</p></a></div>
           
     <div class="employees">
     <h1>Employees</h1>
            
                <?php
   
                //Validator
                include "xsd/validator.php";
                 //initiate the conexion with the API
                 $ch = curl_init();
                 
                 $url = 'http://127.0.0.1:5000/XMLdepartmentEmployees';
                 
                 //set the curl
                 curl_setopt($ch,CURLOPT_URL,$url);
                 
                 curl_setopt($ch,CURLOPT_HEADER, false);
                 
                 curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                 
                 $information = curl_exec($ch);
                 
                 //close the curl
                 curl_close($ch);
       
                
                
                
                function libxml_display_errors() {
                    $errors = libxml_get_errors();
                    foreach ($errors as $error) {
                        print libxml_display_error($error);
                    }
                    libxml_clear_errors();
                }

                // Enable user error handling
                libxml_use_internal_errors(true);

                $xml = new DOMDocument();
                $xml->load('http://127.0.0.1:5000/XMLdepartmentEmployees');

                if (!$xml->schemaValidate('xsd/XMLEmployees.xsd')) {
                    print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
                    libxml_display_errors();
                } else {
                        $informationRecived = simplexml_load_string($information);
                

            ?>
            <table style='width:100%'>
            <tr>
                <th>Department Name</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
            <tr>
                <?php
                foreach ($informationRecived as $informationRecived)  
                {
                    echo "<tr><td>".$informationRecived->dept_name."</td>";
                    echo "<td>".$informationRecived->first_name."</td>";
                    echo "<td>".$informationRecived->last_name."</td></tr>";           
                }
                ?>
            </tr>
            </table>
     <?php } ?>
     </div>
           
            <div>
            <h1>Managers</h1>
            <table style='width:100%'>
            <tr>
                <th>Department Name</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
            <tr>
                <?php
                $chManagers = curl_init();
        
                $urlManagers = 'http://127.0.0.1:5000/XMLdepartmentManager';

                curl_setopt($chManagers,CURLOPT_URL,$urlManagers);

                curl_setopt($chManagers,CURLOPT_HEADER, false);

                curl_setopt($chManagers,CURLOPT_RETURNTRANSFER,true);

                $informationManagers= curl_exec($chManagers);

                curl_close($chManagers);
                
                 $informationRecivedManagers = simplexml_load_string($informationManagers);
                 
                 // Enable user error handling
                libxml_use_internal_errors(true);
                
                $xml = new DOMDocument();
                $xml->load('http://127.0.0.1:5000/XMLdepartmentEmployees');
                 
                  if (!$xml->schemaValidate('xsd/XMLManagers.xsd')) {
                    print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
                    libxml_display_errors();
                } else {
                    
                
               
                   foreach ($informationRecivedManagers as $informationRecivedManagers)  
                   {
                      echo "<tr><td>".$informationRecivedManagers->dept_name."</td>";
                      echo "<td>".$informationRecivedManagers->first_name."</td>";
                      echo "<td>".$informationRecivedManagers->last_name."</td>";           
                   }
                
                 }
                  
                 ?>
            </tr>
            </table>
        
        </div>
            
            
            <div class="salary">
                <?php
                $chDepartmentsSalary = curl_init();
        
                $urlDepartmentsSalary = 'http://127.0.0.1:5000/XMLdepartmentsSalary';

                curl_setopt($chDepartmentsSalary,CURLOPT_URL,$urlDepartmentsSalary);

                curl_setopt($chDepartmentsSalary,CURLOPT_HEADER, false);

                curl_setopt($chDepartmentsSalary,CURLOPT_RETURNTRANSFER,true);

                $infoDepartmentsSalary= curl_exec($chDepartmentsSalary);
                
                $infoRecivedDepartmentsSalary = simplexml_load_string($infoDepartmentsSalary);

                curl_close($chDepartmentsSalary);
                
                 // Enable user error handling
                libxml_use_internal_errors(true);
                
            
            
                $xml = new DOMDocument();
                $xml->load('http://127.0.0.1:5000/XMLdepartmentsSalary');
                 
                  if (!$xml->schemaValidate('xsd/XMLDepartmentsSalary.xsd')) 
                      {
                        print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
                        libxml_display_errors();
                      } 
                else 
                    
                 {
                        $sumITSalary = null;
                        $ITEmployees = null;
                        $sumMarketingSalary = null;
                        $MarketingEmployees = null;
         
                       
                        foreach ($infoRecivedDepartmentsSalary as $infoRecivedDepartmentsSalary)  
                        {
                            if($infoRecivedDepartmentsSalary->dept_name == "IT")
                            {
                                $sumITSalary = $sumITSalary + $infoRecivedDepartmentsSalary->salary;
                                $ITEmployees ++;
                            }
                            else if($infoRecivedDepartmentsSalary->dept_name == "Marketing")
                            {
                                $sumMarketingSalary = $sumMarketingSalary +$infoRecivedDepartmentsSalary->salary;
                                $MarketingEmployees ++;
                            }
                        }
                        
                            $avgSalaryIT = null;
                            $avgSalaryIT =  $sumITSalary / $ITEmployees;

                            $avgMarketingSalary = null;
                            $avgMarketingSalary = $sumMarketingSalary / $MarketingEmployees;
                            
                            // Draw the chart 
                            $dataPoints = array( 

                                array("y" => $avgSalaryIT, "label" => "IT" ),
                                array("y" => $avgMarketingSalary, "label" => "Marketing" ),
                                );    
                 }
                
                
                
              ?>  
                
                <script>
            window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                    //animationEnabled: true,
                    theme: "light2",
                    title:{
                            text: "Average Salary per Department"
                    },
                    axisY: {
                            title: "Salary in Euro"
                    },
                    data: [{
                            type: "column",
                            yValueFormatString: "#,##0.## &euro; salary",
                            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
            });
            chart.render();

            }
            </script>
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                  
            </div>
            
            <div class="hobbies">
                <?php
                $chHobbie = curl_init();
        
                $urlHobbie = 'http://127.0.0.1:5000/XMLhobbiesInfo';

                curl_setopt($chHobbie,CURLOPT_URL,$urlHobbie);

                curl_setopt($chHobbie,CURLOPT_HEADER, false);

                curl_setopt($chHobbie,CURLOPT_RETURNTRANSFER,true);

                $infoHobbie= curl_exec($chHobbie);
                
                $infoRecivedHobbie = simplexml_load_string($infoHobbie);

                curl_close($chHobbie);
                
                 // Enable user error handling
                libxml_use_internal_errors(true);
                
            
            
                $xml = new DOMDocument();
                $xml->load('http://127.0.0.1:5000/XMLhobbiesInfo');
                 
                  if (!$xml->schemaValidate('xsd/XMLHobbiesInfo.xsd')) 
                      {
                        print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
                        libxml_display_errors();
                      } 
                else 
                   {

                ?>
                 
                   <h1>Money Spent On Hobbies</h1>
                        <table style='width:100%'>
                        <tr>
                            <th>HobbieName</th>
                            <th>MoneySpent</th>
                            
                        </tr>
                        <tr>
                        <?php
                        
                        $sumDancing = 0;
                        foreach ($infoRecivedHobbie as $infoRecivedHobbie)  
                            {
                            
                               echo "<td>$infoRecivedHobbie->HobbieName</td>";
                               echo "<td>$infoRecivedHobbie->MoneySpent &euro;</td></tr>";
                                
                            }
                              
                              
                            
                    
                         ?>
                         <tr> 
                        </table>
            </div>
                   <?php  }?>
    </body>
</html>
