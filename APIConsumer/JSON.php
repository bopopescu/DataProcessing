<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <link rel = "stylesheet" type = "text/css" href = "css.css" />
        <title>CompanyEmployeesApi</title>
        
        

        
           
        <?php
        
        require_once 'vendor/autoload.php';
        use JsonSchema\Validator;
        use JsonSchema\Constraints\Constraint;
        
        
        ?>
        
        
    </head>
    <body>
        
         <div><a href="XML.php"><p>See Information sent in XML form</p></a></div>
        
        <div class="employee">
            <h1>Employees</h1>
            <table style='width:100%'>
            <tr>
                <th>Department Name</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
            <tr>
                <?php
                //initiate the connexion with the API
                 $ch = curl_init();
                 
                 $url = 'http://127.0.0.1:5000/JSONdepartmentEmployees';
                 
                 // set the curl
                 curl_setopt($ch,CURLOPT_URL,$url);
                 
                 curl_setopt($ch,CURLOPT_HEADER, false);
                 
                 curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                 
                 $information = curl_exec($ch);
                 //close the curl
                 curl_close($ch);
                 // preapre the json for validation
                 $config = json_decode(file_get_contents('http://127.0.0.1:5000/JSONdepartmentEmployees'));
        
                 $validator = new Validator;
                 // validate
                 $validator->validate(
                 $config,
                 (object)['$ref' => 'file://' . realpath('js/JsonEmployees.json')],
                 Constraint::CHECK_MODE_APPLY_DEFAULTS
                );
                 // check the result of the validation  
                if ($validator ->isValid())
                {
                    //display the info from JSON
                   $informationRecived = (array) json_decode($information);
                   foreach ($informationRecived as $informationRecived)  
                   {
                      echo "<tr><td>".$informationRecived->dept_name."</td>";
                      echo "<td>".$informationRecived->first_name."</td>";
                      echo "<td>".$informationRecived->last_name."</td></tr>";           
                   }
                
                 }
                  else 
                      {
                        echo 'The sent information is not valid';
                      }
                 ?>
                
            </tr>
            </table>
        </div>
  
        
        <div class="Managers">
        <h1>Managers</h1>
            <table style='width:100%'>
            <tr>
                <th>Department Name</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
            <tr>
                <?php
                //initiate the connexion with the API
                $chManagers = curl_init();
        
                $urlManagers = 'http://127.0.0.1:5000/JSONdepartmentManager';

                // set the curl
                curl_setopt($chManagers,CURLOPT_URL,$urlManagers);

                curl_setopt($chManagers,CURLOPT_HEADER, false);

                curl_setopt($chManagers,CURLOPT_RETURNTRANSFER,true);

                $informationManagers= curl_exec($chManagers);

                curl_close($chManagers);
                
                // preapre the json for validation
                $configManagers = json_decode(file_get_contents('http://127.0.0.1:5000/JSONdepartmentManager'));
        
                $validatorManagers = new Validator;
                $validatorManagers->validate(
                $configManagers,
                 (object)['$ref' => 'file://' . realpath('js/JsonManagers.json')],
                 Constraint::CHECK_MODE_APPLY_DEFAULTS
                );
        
                
                if ($validatorManagers ->isValid())
                {
                    //display data
                   $informationRecivedManagers = (array) json_decode($informationManagers);
                   
                   foreach ($informationRecivedManagers as $informationRecivedManagers)  
                   {
                      echo "<tr><td>".$informationRecivedManagers->dept_name."</td>";
                      echo "<td>".$informationRecivedManagers->first_name."</td>";
                      echo "<td>".$informationRecivedManagers->last_name."</td>";           
                   }
                
                 }
                  else 
                      {
                        echo 'The sent information is not valid';
                   
                        
                      }
                 ?>
            </tr>
            </table>
        
        </div>
        <div class="salary">
            <?php
           
            $chDepartmentsSalary = curl_init();
        
            $urlDepartmentsSalary = 'http://127.0.0.1:5000/JSONdepartmentsSalary';

            curl_setopt($chDepartmentsSalary,CURLOPT_URL,$urlDepartmentsSalary);

            curl_setopt($chDepartmentsSalary,CURLOPT_HEADER, false);

            curl_setopt($chDepartmentsSalary,CURLOPT_RETURNTRANSFER,true);

            $infoDepartmentsSalary= curl_exec($chDepartmentsSalary);
           
            curl_close($chDepartmentsSalary);
                
            $configManagers = json_decode(file_get_contents('http://127.0.0.1:5000/JSONdepartmentsSalary'));
        
            $validatorManagers = new Validator;
            $validatorManagers->validate(
            $configManagers,
             (object)['$ref' => 'file://' . realpath('js/JsonSalary.json')],
              Constraint::CHECK_MODE_APPLY_DEFAULTS
             );
            
            if ($validatorManagers ->isValid())
                {
                 $infoRecivedDepartmentsSalary = (array) json_decode($infoDepartmentsSalary,true);
               
                 //declare the variables needed for calculating the average
                $sumITSalary = null;
                $ITEmployees = null;
                $sumMarketingSalary = null;
                $MarketingEmployees = null;
         
                for($i=0;$i<count($infoRecivedDepartmentsSalary);$i++)
                {
            
                    if($infoRecivedDepartmentsSalary[$i]['dept_name'] == "IT")
                    {
                        $sumITSalary = $sumITSalary + $infoRecivedDepartmentsSalary[$i]['salary'];
                        $ITEmployees ++;
                    }
                    else if($infoRecivedDepartmentsSalary[$i]['dept_name'] == "Marketing")
                    {
                        $sumMarketingSalary = $sumMarketingSalary +$infoRecivedDepartmentsSalary[$i]['salary'];
                        $MarketingEmployees ++;
                    }
                }
                
                $avgSalaryIT = null;
                $avgSalaryIT =  $sumITSalary / $ITEmployees;
                
                $avgMarketingSalary = null;
                $avgMarketingSalary = $sumMarketingSalary / $MarketingEmployees;


            // Draw the chart using CANVASJS 
            $dataPoints = array( 
                
                    array("y" => $avgSalaryIT, "label" => "IT" ),
                    array("y" => $avgMarketingSalary, "label" => "Marketing" ),
            );
                }
                else
                {
                     echo 'The sent information is not valid';
                    
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
        
           <div class="hobies">
              <?php
              
                $chHobbie = curl_init();
        
                $urlHobbie = 'http://127.0.0.1:5000/JSONhobbiesInfo';

                curl_setopt($chHobbie,CURLOPT_URL,$urlHobbie);

                curl_setopt($chHobbie,CURLOPT_HEADER, false);

                curl_setopt($chHobbie,CURLOPT_RETURNTRANSFER,true);

                $infoHobbie = curl_exec($chHobbie);
                
                $configHobbie = json_decode(file_get_contents('http://127.0.0.1:5000/JSONhobbiesInfo'));

                curl_close($chHobbie);
                
                $validatorHobbie = new Validator;
                $validatorHobbie->validate(
                $configHobbie,
                (object)['$ref' => 'file://' . realpath('js/JsonHobby.json')],
                 Constraint::CHECK_MODE_APPLY_DEFAULTS
                );
                
                if ($validatorHobbie ->isValid())
                {
                   
                    $hobbieNames = array();
                    $sums = array();
                    
                    
                 $infoRecivedHobbie = (array) json_decode($infoHobbie,true);
       
                    $temp = [];
                    foreach($infoRecivedHobbie as $value) 
                     {
                        //check if color exists in the temp array
                        if(!array_key_exists($value['HobbieName'], $temp)) 
                            {
                            //if it does not exist, create it with a value of 0
                            $temp[$value['HobbieName']] = 0;
                            }
                        //Add up the values from each color
                        $temp[$value['HobbieName']] += $value['MoneySpent'];
                     }
         
                }
                    else
                    {
                        echo "The data is not valid";
                        
                    }
                    
                   ?>
                        <h1>Money Spent On Hobbies in Total per month</h1>
                        <table style='width:100%'>
                        <tr>
                          <?php

                          foreach ($temp as $x => $x_value)  
                            {
                               echo "<th>$x</th>";
                            }
                          ?>
                        </tr>
                             <?php
                                 foreach ($temp as $x => $x_value)  
                                 {
                                    echo "<td>$x_value &euro;</td>";
                                 }
                            ?>
                         <tr> 
           </div>
             
    </body>
</html>
