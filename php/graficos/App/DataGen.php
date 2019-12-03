<?php
 header("Content-type: text/html; charset=utf-8");
//This page contains functions which generate the XML data for the chart.
//Effectively, we've separated this part from each PHP page to simulate a
//3-tier architecture. In real world, you could replace this by a COM 
//Component or similar technologies which would connect to the database and 
//return data (in XML or normally, which could later be converted to XML).

//getSalesByYear function returns the XML for yearly sales figures (including
//quantity)
function getSalesByYear(&$FC) {
    // Function to connect to the DB
    $link = connectToDB();

    $strSQL = "SELECT Year(o.OrderDate) As SalesYear, ROUND(SUM(d.Quantity*p.UnitPrice),0) As Total, SUM(d.Quantity) as Quantity FROM FC_OrderDetails as d,FC_Orders as o,FC_Products as p WHERE o.OrderID=d.OrderID and d.ProductID=p.ProductID GROUP BY Year(o.OrderDate) ORDER BY Year(o.OrderDate)";
    $result = mysql_query($strSQL) or die(mysql_error());
    

    if ($result) {
        //Initialize datasets
        $FC->addDataset("Revenue",""); 
		while($ors = mysql_fetch_array($result)) {
            $FC->addCategory($ors['SalesYear'],"");
			
            //Generate the link
            $strLink = urlencode("javaScript:updateCharts(" . $ors['SalesYear'] . ");");
			//Add Set with in DataSet
			$FC->addChartData($ors['Total'],"link=" . $strLink);
            
        }
		
		//Initialize datasets
		$FC->addDataset("Units Sold","parentYAxis=S"); 
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
			//Add Set with in DataSet
		    $FC->addChartData($ors['Quantity'],"");
		}
		
    }
    mysql_close($link);

}

//getCumulativeSalesByCatXML returns the cumulative sales for each category
//in a given year
function getCumulativeSalesByCatXML($intYear, $forDataURL,&$FC) {

    // Function to connect to the DB
    $link = connectToDB();

    //To store categories - also flag to check whether category is
	//already generated
    $catXMLDone = false;
	
	$arrCat =array();
    $strSQL = "SELECT  distinct Month(o.OrderDate) as MonthNum from FC_Orders as o WHERE year(o.OrderDate)=$intYear order by Month(o.OrderDate)";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        $mc=0;
        while($orsCat = mysql_fetch_array($result)) {
           //Add this category as dataset
           $arrCat[$mc++]=MonthName($orsCat['MonthNum'],true);
           $FC->addCategory(MonthName($orsCat['MonthNum'],true));
         }
     mysql_free_result($result);
    }
 
	
	//First we need to get unique categories in the database
	$strSQL = "Select CategoryID,CategoryName from FC_Categories GROUP BY CategoryID,CategoryName";
    $result = mysql_query($strSQL) or die(mysql_error());

    if ($result) {
        while($orsCat = mysql_fetch_array($result)) {
            //Add this category as dataset
            $FC->addDataset(escapeXML($orsCat['CategoryName'],$forDataURL),"");
			 
            //Now, we need to get monthly sales data for products in this category
            $strSQL = "SELECT  Month(o.OrderDate) as MonthNum, g.CategoryID, g.CategoryName, ROUND(SUM(d.Quantity),0) as Quantity, SUM(d.Quantity*p.UnitPrice) As Total FROM FC_Categories as g,  FC_Products as p, FC_Orders as o, FC_OrderDetails as d  WHERE year(o.OrderDate)=" . $intYear ." and g.CategoryID=" . $orsCat['CategoryID'] . " and d.ProductID=p.ProductId and g.CategoryID= p.CategoryID and o.OrderID= d.OrderID GROUP BY g.CategoryID,g.CategoryName,Month(o.OrderDate)";
            //Execute it
			$result2 = mysql_query($strSQL) or die(mysql_error());
			$mc=0;
            while($ors = mysql_fetch_array($result2)) {
                
                //Generate the link
                $strLink = urlencode("javaScript:updateProductChart(" . $intYear . "," . $ors['MonthNum'] . "," . $ors['CategoryID'] . ")");
                //Append data
    		    while($arrCat[$mc++]<$ors["MonthNum"]){
				    $FC->addChartData();
                }

				$FC->addChartData($ors['Total'],"link=" . $strLink,"");
            }
			if($mc<count($arrCat)){
				for($i=0;$i<count($arrCat)-$mc;$i++){
					$FC->addChartData();
				}
			}
            //Update flag that we've appended categories		
            $catXMLDone = true;
            //Clear up objects
            mysql_free_result($result2);
            
        }
    }
    mysql_close($link);
    
}


//getSalesByProdXML returns the sales for the products within a category
//for a given year and month 
function getSalesByProdXML($intYear, $intMonth, $intCatId, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	
	
	//First we need to get unique categories in the database
	$strSQL = "SELECT  g.CategoryName,p.ProductName,ROUND(SUM(d.Quantity),0) as Quantity, ROUND(SUM(d.Quantity*p.UnitPrice),0) As Total FROM FC_Categories as g,  FC_Products as p, FC_Orders as o, FC_OrderDetails as d WHERE year(o.OrderDate)=" . $intYear . " and month(o.OrderDate)=" . $intMonth . " and g.CategoryID=" . $intCatId . " and d.ProductID= p.ProductID and g.CategoryID= p.CategoryID and o.OrderID= d.OrderID GROUP BY g.CategoryName,p.ProductName ";
    $result = mysql_query($strSQL) or die(mysql_error());

    if ($result) {
		# Add Chart Dataset
	    $FC->addDataset("Revenue",""); 
        while($ors = mysql_fetch_array($result)) {
		    # Add Chart category
			$FC->addCategory(escapeXML($ors['ProductName'],$forDataURL),"");
			# Add Chart Data
			$FC->addChartData($ors['Total'],"");
        }
		# Add Chart Dataset
		$FC->addDataset("Units Sold","parentYAxis=S"); 
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
		    # Add Chart Data
			$FC->addChartData($ors['Quantity'],"");
       }
    }
    mysql_close($link);
	
} 

//getAvgShipTimeXML function returns the delay in average shipping time required
//to ship an item.
//$intYear - Year for which we calculate average shipping time
//$numCountries - For how many countries. If -1, then all countries
//$addJSLinks - Whether to add JavaScript links
//$forDataURL - Whether XML Data to be generated for dataURL method or dataXML method
//Returns - Single Series XML Data
function getAvgShipTimeXML($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
		
	$strSQL = "	SELECT a.qtd as Average, b.nome as Country FROM cs2.movimento_consulta a 
				INNER JOIN cs2.valcons b ON a.tpcons = b.codcons 
				WHERE data = now() order by Average asc";

    $result = mysql_query($strSQL) or die(mysql_error());

    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                //Generate the link
                //TRICKY: We're having to escape the " character using chr(34) character.
                //In HTML, the data is provided as chart.setXMLData(" - so " is already used and un-terminated
                //For each XML attribute, we use '. So ' is used in <set link='
                //Now, we've to pass Country Name to JavaScript function, so we've to use chr(34)
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
                
        }
    }
    mysql_close($link);

}

function getSaleMonth($intMonth, $intYear,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$month_name = array("", "Janeiro", "Fevereiro", "Mar�o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"); 
    $str = $month_name[floor($intMonth)].'/'.$intYear;
				
	$strSQL = "	Select count(*) qtd From cs2.cadastro
				Where Month(dt_cad)=$intMonth and Year(dt_cad) = $intYear";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
			$FC->addChartData($ors['qtd'],"label=" . escapeXML($str, $forDataURL) ,"");
        }
    }

	for ( $i = 1 ; $i < 12 ; $i++ ){
		$intMonth--;
		if ( $intMonth == 0 ){
			$intMonth = 12;
			$intYear = $intYear - 1;
		}
		$month_name = array("", "Janeiro", "Fevereiro", "Mar�o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"); 
		$str = $month_name[floor($intMonth)].'/'.$intYear;
	
	 	$strSQL = "	Select count(*) qtd From cs2.cadastro
				Where Month(dt_cad)=$intMonth and Year(dt_cad) = $intYear";
	    $result = mysql_query($strSQL) or die(mysql_error());
    	if ($result) {
			while($ors = mysql_fetch_array($result)) {
				$FC->addChartData($ors['qtd'],"label=" . escapeXML($str, $forDataURL) ,"");
			}
		}
	}
    mysql_close($link);
}

function getAvgShipTimeXML2($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC, $data) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	$strSQL = "SELECT sum(a.qtd) as Average, b.nome as Country FROM cs2.movimento_consulta a 
				INNER JOIN cs2.valcons b ON a.tpcons = b.codcons 
				WHERE month(data) = $mes and year(data) = $ano 
				group by nome
				order by Average asc";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function getAvgShipTimeXML_Clientes($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	$strSQL = " SELECT count(*) as Average, 
                        CONCAT( 
                            CASE month(a.data_cadastro) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_cadastro)
                        ) as Country 
                    FROM base_web_control.cliente a
                    WHERE $selecao 
                        a.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(a.data_cadastro) , year(a.data_cadastro)
                    ORDER BY a.dt_cad";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function CrediarioBoleto_Ultimos2Anos($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	
	//$strSQL = "SELECT count(*) as Average, concat( 
	
	$strSQL = "SELECT sum(valor) * 2 as Average, concat( 
					case month(emissao) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(emissao)
				) as Country 
				FROM cs2.titulos_recebafacil
				Where emissao between '2009-01-01' and '2010-12-31' and tp_titulo <> 1 and 
				( valorpg > 0 ) AND ( descricao_repasse IS NULL )
				GROUP BY month(emissao) , year(emissao)
				ORDER BY emissao
				LIMIT 24";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function Recupere_Ultimos2Anos($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	
	//$strSQL = "SELECT count(*) as Average, concat( 
	
	$strSQL = "SELECT sum(valor) * 2 as Average, concat( 
					case month(emissao) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(emissao)
				) as Country 
				FROM cs2.titulos_recebafacil
				Where emissao between '2009-01-01' and '2010-12-31' and tp_titulo = 1 and 
				( valorpg > 0 ) AND ( descricao_repasse IS NULL )
				GROUP BY month(emissao) , year(emissao)
				ORDER BY emissao
				LIMIT 24";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function WebControle_Consumidores($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	
	//$strSQL = "SELECT count(*) as Average, concat( 
	
	$strSQL = "SELECT count(*) as Average, concat( 
					case month(data_cadastro) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(data_cadastro)
				) as Country 
				FROM base_web_control.cliente
				Where data_cadastro between '2009-01-01' and '2010-12-31'
				GROUP BY month(data_cadastro) , year(data_cadastro)
				LIMIT 24";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function Recomende_System($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	
	//$strSQL = "SELECT count(*) as Average, concat( 
	
	$strSQL = "SELECT count(*) as Average, concat( 
					case month(amd) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(amd)
				) as Country 
				FROM cs2.relacionamento_consumidor
				Where amd between '2009-01-01' and '2010-12-31'
				GROUP BY month(amd) , year(amd)
				ORDER BY amd
				LIMIT 24";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function Veiculos($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	
	//$strSQL = "SELECT count(*) as Average, concat( 
	
	$strSQL = "SELECT count(*) as Average, concat( 
					case month(amd) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(amd)
				) as Country 
				FROM cs2.cons
				Where amd between '2009-01-01' and '2010-12-31' and mid(debito,1,3) = 'A04'
				GROUP BY month(amd) , year(amd)
				ORDER BY amd
				LIMIT 24";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function LocalizaMAX($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	
	//$strSQL = "SELECT count(*) as Average, concat( 
	
	$strSQL = "SELECT count(*) as Average, concat( 
					case month(amd) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(amd)
				) as Country 
				FROM cs2.cons
				Where amd between '2009-01-01' and '2010-12-31' and (debito = 'A0230' or debito = 'A0232') 
				GROUP BY month(amd) , year(amd)
				order by amd
				LIMIT 24";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function EncaminhamentoProtesto($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	
	$strSQL = "SELECT count(*) as Average, concat( 
					case month(dt_envio_cartorio) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(dt_envio_cartorio)) as Country 
				FROM consulta.alertas
				Where dt_envio_cartorio between '2009-01-01' and '2010-12-31'
				GROUP BY month(dt_envio_cartorio) , year(dt_envio_cartorio)
				order by dt_envio_cartorio
				LIMIT 24";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function getAvgShipTimeXML_Vendas($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	$strSQL = "SELECT count(*) as Average, concat( 
	    case month(data_venda) 
         when 1 then 'Janeiro'
         when 2 then 'Fevereiro'        
         when 3 then 'Marco'        
         when 4 then 'Abril'        
         when 5 then 'Maio'        
         when 6 then 'Junho'        
         when 7 then 'Julho'
         when 8 then 'Agosto'
         when 9 then 'Setembro'
         when 10 then 'Outubro'        
         when 11 then 'Novembro'        
         when 12 then 'Dezembro'
       end ,'/',year(data_venda) ) as Country 
				FROM base_web_control.venda
				GROUP BY month(data_venda) , year(data_venda)
				LIMIT 12";

    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}

function getAvgShipTimeXML_OS($intYear, $numCountries, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$mes = substr($data,0,2);
	$ano = substr($data,3,4);
	$strSQL = "SELECT count(*) as Average, concat( 
	    case month(data_hora_criada) 
         when 1 then 'Janeiro'
         when 2 then 'Fevereiro'        
         when 3 then 'Marco'        
         when 4 then 'Abril'        
         when 5 then 'Maio'        
         when 6 then 'Junho'        
         when 7 then 'Julho'
         when 8 then 'Agosto'
         when 9 then 'Setembro'
         when 10 then 'Outubro'        
         when 11 then 'Novembro'        
         when 12 then 'Dezembro'
       end ,'/',year(data_hora_criada) ) as Country 
				FROM base_web_control.ordem_servico
				GROUP BY month(data_hora_criada) , year(data_hora_criada)
				LIMIT 12";
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            //If JavaScript links are to be added
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink,"");
                
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=" . escapeXML($ors['Country'], $forDataURL) ,"");
        }
    }
    mysql_close($link);
}
//getAvgShipTimeCityXML function returns the average shipping time required
//to ship an item for the cities within the given country
//$intYear - Year for which we calculate average shipping time
//$country - Cities of which country?
//Returns - Single Series XML Data
function getAvgShipTimeCityXML($intYear, $country, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
    //Retrieve the shipping info by city
    $strSQL = "Select ShipCity, ROUND(AVG(DAY(ShippedDate)-DAY(RequiredDate)),0) As Average from FC_Orders WHERE YEAR(OrderDate)=" . $intYear . " and ShipCountry='" . $country . "' GROUP BY ShipCity ORDER BY Average DESC ";
    $result = mysql_query($strSQL) or die(mysql_error());
    //Create the XML data document containing only data
    //We add the <chart> element in the calling function, depending on needs.	
    $strXML = "";
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            # Add Chart Data
            $FC->addChartData($ors['Average'],"label=" . escapeXML($ors['ShipCity'],$forDataURL),"");
        }
    }
    mysql_close($link);
    return $strXML;
}

//getTopCustomersXML returns the XML data for top customers for
//the given year.
function getTopCustomersXML($intYear, $howMany, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	
	$strSQL = "SELECT c.CompanyName as CustomerName, SUM(d.Quantity*p.UnitPrice) As Total, SUM(d.Quantity) As Quantity FROM FC_Customers as c, FC_OrderDetails as d, FC_Orders as o, FC_Products as p WHERE YEAR(OrderDate)=" . $intYear . " and c.CustomerID=o.CustomerID and o.OrderID=d.OrderID and d.ProductID=p.ProductID GROUP BY c.CompanyName ORDER BY Total DESC LIMIT ". $howMany;
    $result = mysql_query($strSQL) or die(mysql_error());


	//Iterate through each data row
    if ($result) {
	    # Add Chart Dataset
		$FC->addDataset("Amount",""); 
        while($ors = mysql_fetch_array($result)) {
			# Add Chart category
			$FC->addCategory(escapeXML($ors['CustomerName'],$forDataURL),"");
			# Add Chart Data
			$FC->addChartData($ors['Total'],"","");
            
        }
		# Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S"); 
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
		    # Add Chart Data
			$FC->addChartData($ors['Quantity'],"","");
        }
    }
    mysql_close($link);

}

//getCustByCountry function returns number of customers present
//in each country in the database.
function getCustByCountry($forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();

    $counter = 0;

	//Retrieve the data
	$strSQL = "SELECT count(CustomerID) AS Num, Country FROM FC_Customers GROUP BY Country ORDER BY Num DESC;";
    $result = mysql_query($strSQL) or die(mysql_error());
	
	//Create the XML data document containing only data
	//We add the <chart> element in the calling function, depending on needs.	
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Increase counter
            $counter++;
            //Append the data
            //We slice the first pie (the country having highest number of customers)		
            if ($counter==1)
			    # Add Chart Data
			    $FC->addChartData( $ors['Num'],"label=" . escapeXML($ors['Country'],$forDataURL) .";isSliced=1","");
                
            else
			    # Add Chart Data
				$FC->addChartData( $ors['Num'],"label=" . escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);

}

//getSalePerEmp function returns the XML data for sales generated by
//each employee for the given year
function getSalePerEmpXML($slicePies, $addJSLinks, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();

    $count = 0;

	$strSQL = 'SELECT COUNT(*) AS Total, b.uf AS LastName
			FROM base_web_control.venda a
			INNER JOIN base_web_control.cliente b ON a.id_cadastro = b.id
			Group by b.uf
			Order by LastName,Total desc';
	
    $result = mysql_query($strSQL) or die(mysql_error());

    //Create the XML data document containing only data
	//We add the <chart> element in the calling function, depending on needs.	
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            //Append the data
            $count++;

            //If link is to be added
            if ($addJSLinks)
                $strLink = "link=javascript:updateChart(" . $ors['EmployeeID'] . ")";
            else
                $strLink = "";

            //If top 2 employees, then sliced out				
            if ($slicePies && ($count<3))
                $slicedOut="1";
            else
                $slicedOut="0";
			
			$slicedOut="1";
			
			$strParam="label=" . escapeXML($ors['LastName'],$forDataURL) . ";isSliced=" . $slicedOut . ";" . $strLink;
			# Add Chart Data
			$FC->addChartData($ors['Total'],$strParam);
			
        }
    }
    mysql_close($link);
}

//getSalesByCountryXML function returns the XML Data for sales
//for a given country in a given year.
function getSalesByCountryXML($intYear, $howMany, $addJSLinks, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();

    $strSQL = "SELECT c.Country, ROUND(SUM(d.Quantity*p.UnitPrice*(1-d.Discount)),0) As Total, SUM(d.Quantity) as Quantity FROM FC_Customers as c, FC_Products as p, FC_Orders as o, FC_OrderDetails as d WHERE YEAR(OrderDate)=" . $intYear . " and d.ProductID=p.ProductID and c.CustomerID=o.CustomerID and o.OrderID=d.OrderID GROUP BY c.Country ORDER BY Total DESC";
	if ($howMany!=-1)
		$strSQL .= " LIMIT " . $howMany;
	
    $result = mysql_query($strSQL) or die(mysql_error());

	//Iterate through each data row
    if ($result) {
		# Add Chart Dataset
		$FC->addDataset("Amount",""); 
        while($ors = mysql_fetch_array($result)) {
		   	# Add Chart category
			$FC->addCategory(escapeXML($ors['Country'],$forDataURL),"");
		
            //If JavaScript links are to be added
            if ($addJSLinks) {
                //Generate the link
                //TRICKY: We're having to escape the " character using chr(34) character.
                //In HTML, the data is provided as chart.setXMLData(" - so " is already used and un-terminated
                //For each XML attribute, we use '. So ' is used in <set link='
                //Now, we//ve to pass Country Name to JavaScript function, so we've to use chr(34)
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] .  chr(34) . ");");
				# Add Chart Data
				$FC->addChartData($ors['Total'],"link=" . $strLink,"");
                
            }
            else
			    # Add Chart Data
			    $FC->addChartData($ors['Total'],"","");
         
        }
		# Add Chart Dataset
        $FC->addDataset("Quantity","parentYAxis=S"); 
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
		     # Add Chart Data 
			 $FC->addChartData($ors['Quantity'],"","");	
		}
		
    }
    mysql_close($link);
}

//getSalesByCountryCityXML function generates the XML data for sales
//by city within the given country, for the given year.
function getSalesByCountryCityXML($intYear, $country, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	
	$strSQL = "SELECT  c.City, ROUND(SUM(d.Quantity*p.UnitPrice*(1-d.Discount)),0) As Total, SUM(d.Quantity) as Quantity  FROM FC_Customers as c, FC_Products as p, FC_Orders as o, FC_OrderDetails as d WHERE YEAR(OrderDate)=" . $intYear . " and d.ProductID=p.ProductID and c.CustomerID=o.CustomerID and o.OrderID=d.OrderID and c.Country='" . $country . "' GROUP BY c.City ORDER BY Total DESC";
    $result = mysql_query($strSQL) or die(mysql_error());

    if ($result) {
		# Add Chart Dataset
		$FC->addDataset("Amount","");
        while($ors = mysql_fetch_array($result)) {
            # Add Chart category
			$FC->addCategory(escapeXML($ors['City'],$forDataURL),"");
			# Add Chart Data
			$FC->addChartData($ors['Total'],"","");
		           
        }
		
		# Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S");
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
		    # Add Chart Data
		    $FC->addChartData($ors['Quantity'],"","");
           
        }
    }
    mysql_close($link);
	
}

//getSalesByCountryCustomerXML function generates the XML data for sales
//by customers within the given country, for the given year.
function getSalesByCountryCustomerXML($intYear, $country, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	
	$strSQL = "SELECT c.CompanyName as CustomerName, SUM(d.Quantity*p.UnitPrice) As Total, SUM(d.Quantity) As Quantity FROM FC_Customers as c, FC_OrderDetails as d, FC_Orders as o, FC_Products as p WHERE YEAR(OrderDate)=" . $intYear . " and c.CustomerID=o.CustomerID and o.OrderID=d.OrderID and d.ProductID=p.ProductID and c.Country='" . $country . "' GROUP BY c.CompanyName ORDER BY Total DESC";
    $result = mysql_query($strSQL) or die(mysql_error());
		
	
	//Iterate through each data row
    if ($result) {
	    # Add Chart Dataset
		$FC->addDataset("Amount","");
        while($ors = mysql_fetch_array($result)) {
            //Since customers name are long, we truncate them to 5 characters and then show ellipse
            //The full name is then shown as toolText
			# Add Chart category
			$FC->addCategory(escapeXML(substr($ors['CustomerName'],0,5) . "...", $forDataURL),"toolText=" . escapeXML($ors['CustomerName'],$forDataURL));
 			# Add Chart Data
			$FC->addChartData($ors['Total'],"","");
        }
        # Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S");
		mysql_data_seek($result,0);
        while($ors = mysql_fetch_array($result)) {
   			# Add Chart Data
			$FC->addChartData($ors['Quantity'],"","");
         }

    }
    mysql_close($link);

}

//getExpensiveProdXML method returns the 10 most expensive products
//in the database along with the sales quantity of those products
//for the given year
function getExpensiveProdXML($intYear, $howMany, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	
	$strSQL = "SELECT p.ProductName, p.UnitPrice, SUM(d.Quantity) as Quantity FROM FC_Products p, FC_Orders as o, FC_OrderDetails d WHERE YEAR(OrderDate)=" . $intYear . " and d.ProductID=p.ProductID and o.OrderID=d.OrderID GROUP BY p.ProductName,p.UnitPrice  ORDER BY p.UnitPrice DESC LIMIT " . $howMany ;
    $result = mysql_query($strSQL) or die(mysql_error());

    //Iterate through each data row
    if ($result) {
        # Add Chart Dataset
		$FC->addDataset("Unit Price","");
		while($ors = mysql_fetch_array($result)) {
		    # Add Chart category
			$FC->addCategory(escapeXML($ors['ProductName'],$forDataURL),"");
			# Add Chart Data
			$FC->addChartData($ors['UnitPrice'],"","");

        }
		# Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S");
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
			# Add Chart Data
			$FC->addChartData($ors['Quantity'],"","");
        }
    }
    mysql_close($link);

}

//getInventoryByCatXML function returns the inventory of all items
//and their respective quantity
function getInventoryByCatXML($addJSLinks, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();

	$strSQL = "SELECT  c.CategoryName,ROUND(SUM(p.UnitsInStock),0) as Quantity, ROUND(SUM(p.UnitsInStock*p.UnitPrice),0) as Total from FC_Categories as c , FC_Products as p WHERE c.CategoryID=p.CategoryID GROUP BY c.CategoryName ORDER BY Total DESC";
    $result = mysql_query($strSQL) or die(mysql_error());

    //Iterate through each data row
    if ($result) {
	    # Add Chart Dataset
		$FC->addDataset("Cost of Inventory","");
        while($ors = mysql_fetch_array($result)) {
		    # Add Chart category
			$FC->addCategory(escapeXML($ors['CategoryName'],$forDataURL),"");
            
            //If JavaScript links are to be added
            if ($addJSLinks) {
                //Generate the link
                //TRICKY: We//re having to escape the " character using chr(34) character.
                //In HTML, the data is provided as chart.setXMLData(" - so " is already used and un-terminated
                //For each XML attribute, we use '. So ' is used in <set link='
                //Now, we've to pass Country Name to JavaScript function, so we've to use chr(34)
                $strLink = urlencode("javaScript:updateChart(" . chr(34) . $ors['CategoryName'] .  chr(34) . ")");
                # Add Chart Data
				$FC->addChartData($ors['Total'],"link=" . $strLink ,"");
            }
            else
			    # Add Chart Data
                $FC->addChartData($ors['Total'],"","");
        }
		# Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S");
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
		    # Add Chart Data
			$FC->addChartData($ors['Quantity'],"","");
		}
    }
    mysql_close($link);

}

//getInventoryByProdXML function returns the inventory of all items
//within a given category and their respective quantity
function getInventoryByProdXML($catName, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();

	$strSQL = "SELECT p.ProductName,ROUND((SUM(p.UnitsInStock)),0) as Quantity , ROUND((SUM(p.UnitsInStock*p.UnitPrice)),0) as Total from FC_Categories as c , FC_Products as p WHERE c.CategoryID=p.CategoryID and c.CategoryName='" . $catName . "' GROUP BY p.ProductName Having SUM(p.UnitsInStock)>0";
    $result = mysql_query($strSQL) or die(mysql_error());

			
	//Iterate through each data row
    if ($result) {
		# Add Chart Dataset
		$FC->addDataset("Cost of Inventory","");
        while($ors = mysql_fetch_array($result)) {
            //Product Names are long - so show 8 characters and ... and show full thing in tooltip
            if (strlen($ors['ProductName'])>8)
                $shortName = escapeXML(substr($ors['ProductName'],0,8) . "...",$forDataURL);
            else
                $shortName = escapeXML($ors['ProductName'],$forDataURL);
			# Add Chart category
			$FC->addCategory($shortName,"toolText=" . escapeXML($ors['ProductName'],$forDataURL));
			# Add Chart Data
			$FC->addChartData($ors['Total'],"","");
            
        }
		# Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S");
		mysql_data_seek($result,0);
		while($ors = mysql_fetch_array($result)) {
		   # Add Chart Data
		   $FC->addChartData($ors['Quantity'],"","");
		}
		
    }
    mysql_close($link);

}

//getSalesByCityXML function returns the XML Data for sales
//for all cities in a given year.
function getSalesByCityXML($intYear, $howMany, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	
    $strSQL = "SELECT c.City, SUM(d.Quantity*p.UnitPrice) As Total FROM FC_Customers as c, FC_Products as p, FC_Orders as o, FC_OrderDetails as d   WHERE YEAR(OrderDate)=" . $intYear . " and d.ProductID=p.ProductID and c.CustomerID=o.CustomerID and o.OrderID=d.OrderID GROUP BY c.City ORDER BY Total DESC";
	if ($howMany!=-1)
        $strSQL .= " LIMIT " . $howMany;
	
    $result = mysql_query($strSQL) or die(mysql_error());

	//Iterate through each data row
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
			# Add Chart Data
			$FC->addChartData($ors['Total'] ,"label=" . escapeXML($ors['City'],$forDataURL),"");
        }
    }
    mysql_close($link);

}

//getYrlySalesByCatXML function returns the XML Data for sales
//for a given country in a given year.
function getYrlySalesByCatXML($intYear, $addJSLinks, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	
	$strSQL = "SELECT g.CategoryID,g.CategoryName,SUM(d.Quantity*p.UnitPrice) as Total, SUM(d.Quantity) As Quantity FROM FC_Categories as g, FC_Products as p, FC_Orders as o, FC_OrderDetails as d  WHERE YEAR(OrderDate)=" . $intYear . " and d.ProductID=p.ProductID and g.CategoryID=p.CategoryID and o.OrderID=d.OrderID GROUP BY g.CategoryID,g.CategoryName ORDER BY Total DESC";
    $result = mysql_query($strSQL) or die(mysql_error());

	//Iterate through each data row
    if ($result) {
	    # Add Chart Dataset
		$FC->addDataset("Revenue","");
        while($ors = mysql_fetch_array($result)) {
		    # Add Chart category
		    $FC->addCategory(escapeXML($ors['CategoryName'],$forDataURL),"");

            //If JavaScript links are to be added
            if ($addJSLinks) {
                //Generate the link
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . $ors['CategoryID'] . ");");
				# Add Chart Data
				$FC->addChartData($ors['Total'],"link=" . $strLink ,"");
             }
            else
			    # Add Chart Data
				$FC->addChartData($ors['Total'],"","");
         
        }
		mysql_data_seek($result, 0);
		# Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S");
		while($ors = mysql_fetch_array($result)) {
			  # Add Chart Data
			  $FC->addChartData($ors['Quantity'],"","");
		}
		
    }
    mysql_close($link);

}

//getSalesByProdCatXML function returns the sales of all items
//within a given category in a year and their respective quantity
function getSalesByProdCatXML($intYear, $catId, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();

	$strSQL = "SELECT g.CategoryName,p.ProductName,ROUND(SUM(d.Quantity),0) as Quantity, ROUND(SUM(d.Quantity*p.UnitPrice),0) As Total FROM FC_Categories as g,  FC_Products as p, FC_Orders as o, FC_OrderDetails as d WHERE year(o.OrderDate)=" . $intYear . " and g.CategoryID=" . $catId . " and d.ProductID=p.ProductID and g.CategoryID=p.CategoryID and o.OrderID=d.OrderID GROUP BY g.CategoryName,p.ProductName";
    $result = mysql_query($strSQL) or die(mysql_error());

		
	//Iterate through each data row
    if ($result) {
	    # Add Chart Dataset
		$FC->addDataset("Revenue","");
        while($ors = mysql_fetch_array($result)) {
            //Product Names are long - so show 8 characters and ... and show full thing in tooltip
            if (strlen($ors['ProductName'])>8)
                $shortName = escapeXML(substr($ors['ProductName'],0,8) . "...",$forDataURL);
            else
                $shortName = escapeXML($ors['ProductName'],$forDataURL);
            # Add Chart category
			$FC->addCategory($shortName,"toolText=" . escapeXML($ors['ProductName'],$forDataURL));	
			# Add Chart Data		
            $FC->addChartData($ors['Total'],"","");	
        
        }
		mysql_data_seek($result,0);
		# Add Chart Dataset
		$FC->addDataset("Quantity","parentYAxis=S");
		while($ors = mysql_fetch_array($result)) {
			# Add Chart Data  
			$FC->addChartData($ors['Quantity'],"","");	
	    
		}
    }
    mysql_close($link);

}


//getEmployeeName function returns the name of an employee based
//on his id.
function getEmployeeName($empId) {

    // Function to connect to the DB
    $link = connectToDB();

	//Retrieve the data
    $strSQL = "SELECT FirstName, LastName FROM FC_Employees where EmployeeID=" . $empId;
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $ors = mysql_fetch_array($result);
            $name = $ors['FirstName'] . " " . $ors['LastName'];
        } else {
            $name = " N/A ";
        }
    }
    mysql_close($link);

    return $name;
}

//getCategoryName function returns the category name for a given category
//id
function getCategoryName($catId) {
		
    // Function to connect to the DB
    $link = connectToDB();

	//Retrieve the data
	$strSQL = "SELECT CategoryName FROM FC_Categories where CategoryID=" . $catId;
    $result = mysql_query($strSQL) or die(mysql_error());
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $ors = mysql_fetch_array($result);
            $category = $ors['CategoryName'];
        } else {
            $category = " ";
        }
    }
    mysql_close($link);

    return $category;
}


/** NOVO MÉTODO PARA GRAFICOS FRANQUIA */
    function grafico_franquia_novo_01($selecao, &$FC) {
        $link = connectToDB();
        $sql = " SELECT
                    count(*) AS Average, 
                    c.nome as Country 
                 FROM cs2.cadastro a
        INNER JOIN cs2.cons b ON a.codloja = b.codloja
        INNER JOIN cs2.valcons c ON b.debito = c.codcons
        WHERE $selecao 
                b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                AND b.debito !='A0230' AND b.debito != 'A0232'
        GROUP BY b.debito
        ORDER BY Average";

        $result = mysql_query($sql) or die($sql);

        if($result) {
            $i = 0;
            while($ors = mysql_fetch_assoc($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_02($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = "SELECT count(*) Average, b.debito, concat( 
                            case month(b.amd) 
                             when 1 then 'Janeiro'
                             when 2 then 'Fevereiro'        
                             when 3 then 'Marco'        
                             when 4 then 'Abril'        
                             when 5 then 'Maio'        
                             when 6 then 'Junho'        
                             when 7 then 'Julho'
                             when 8 then 'Agosto'
                             when 9 then 'Setembro'
                             when 10 then 'Outubro'        
                             when 11 then 'Novembro'        
                             when 12 then 'Dezembro'
                           end ,'/',year(b.amd)) as Country 
                        FROM cs2.cadastro a 
                        INNER JOIN cs2.cons b ON a.codloja = b.codloja 
                        WHERE $selecao 
                              b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                              AND (b.debito ='A0230' or b.debito = 'A0232') 
                        GROUP BY month(b.amd) , year(b.amd)
                        ORDER BY b.amd";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_13($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = "SELECT count(*) Average1, sum(qtd_registro) as Average, b.tipo_consulta, 
                        concat( 
                            case month(b.data) 
                                when 1 then 'Janeiro'
                                when 2 then 'Fevereiro'        
                                when 3 then 'Marco'        
                                when 4 then 'Abril'        
                                when 5 then 'Maio'        
                                when 6 then 'Junho'        
                                when 7 then 'Julho'
                                when 8 then 'Agosto'
                                when 9 then 'Setembro'
                                when 10 then 'Outubro'        
                                when 11 then 'Novembro'        
                                when 12 then 'Dezembro'
                            end ,
                            '/',
                            year(b.data)
                        ) as Country 
                    FROM cs2.cadastro a 
                    INNER JOIN cs2.cons_localiza b ON a.codloja = b.codloja 
                    WHERE $selecao 
                          b.data between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                          AND (b.tipo_consulta ='A0231') 
                    GROUP BY month(b.data) , year(b.data)
                    ORDER BY b.data";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_03($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT sum(b.valor) as Average, concat( 
                        case month(b.datapg) 
                         when 1 then 'Janeiro'
                         when 2 then 'Fevereiro'        
                         when 3 then 'Marco'        
                         when 4 then 'Abril'        
                         when 5 then 'Maio'        
                         when 6 then 'Junho'        
                         when 7 then 'Julho'
                         when 8 then 'Agosto'
                         when 9 then 'Setembro'
                         when 10 then 'Outubro'        
                         when 11 then 'Novembro'        
                         when 12 then 'Dezembro'
                       end ,'/',year(b.datapg)
                    ) as Country 
                    FROM cs2.cadastro a
                    INNER JOIN cs2.titulos_recebafacil b ON a.codloja = b.codloja
                    Where $selecao 
                    b.datapg between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    and b.tp_titulo = 1 and 
                    ( b.valorpg > 0 ) AND ( b.descricao_repasse IS NULL )
                    GROUP BY month(b.datapg) , year(b.datapg)
                    ORDER BY b.datapg";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_04($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT sum(b.valor) as Average, concat( 
                        case month(b.datapg) 
                         when 1 then 'Janeiro'
                         when 2 then 'Fevereiro'        
                         when 3 then 'Marco'        
                         when 4 then 'Abril'        
                         when 5 then 'Maio'        
                         when 6 then 'Junho'        
                         when 7 then 'Julho'
                         when 8 then 'Agosto'
                         when 9 then 'Setembro'
                         when 10 then 'Outubro'        
                         when 11 then 'Novembro'        
                         when 12 then 'Dezembro'
                       end ,'/',year(b.datapg)
                    ) as Country 
                    FROM cs2.cadastro a
                    INNER JOIN cs2.titulos_recebafacil b ON a.codloja = b.codloja
                    Where $selecao 
                    b.datapg between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    and ( b.tp_titulo = 2 or b.tp_titulo = 3 ) and 
                    ( b.valorpg > 0 ) AND ( b.descricao_repasse IS NULL )
                    GROUP BY month(b.datapg) , year(b.datapg)
                    ORDER BY b.datapg";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_23($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT
                        sum(valor)as Average,
                        CONCAT( 
                            CASE month(a.vencimento) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.vencimento)
                        ) as Country 
                    FROM base_web_control.carne a
                    INNER JOIN cs2.cadastro b on a.id_cadastro = b.codloja
                    INNER JOIN cs2.franquia c on b.id_franquia = c.id
                    WHERE
                        $selecao
                        a.vencimento BETWEEN 
                            CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') 
                            AND 
                            CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 0 DAY)),1,7),'-01')
                        AND id_cadastro NOT IN(764,23096)
                    GROUP BY MONTH(a.vencimento), YEAR(a.vencimento)
                    ORDER BY a.vencimento";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_05($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT count(*) as Average, concat( 
                        case month(b.amd) 
                         when 1 then 'Janeiro'
                         when 2 then 'Fevereiro'        
                         when 3 then 'Marco'        
                         when 4 then 'Abril'        
                         when 5 then 'Maio'        
                         when 6 then 'Junho'        
                         when 7 then 'Julho'
                         when 8 then 'Agosto'
                         when 9 then 'Setembro'
                         when 10 then 'Outubro'        
                         when 11 then 'Novembro'        
                         when 12 then 'Dezembro'
                       end ,'/',year(b.amd)
                    ) as Country 
                    FROM cs2.cadastro a
                    INNER JOIN cs2.relacionamento_consumidor b ON a.codloja = b.codloja
                    Where $selecao 
                    b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(b.amd) , year(b.amd)
                    ORDER BY b.amd";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_14($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = "SELECT count(*) Average, concat( 
                        CASE MONTH(a.dt_cad) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,'/',year(a.dt_cad)) AS Country 
                    FROM cs2.cadastro a
                    WHERE $selecao 
                          a.dt_cad between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 730 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(a.dt_cad) , year(a.dt_cad)
                    ORDER BY a.dt_cad";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_06($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT count(*) as Average, concat( 
                        case month(b.data_cadastro) 
                         when 1 then 'Janeiro'
                         when 2 then 'Fevereiro'        
                         when 3 then 'Marco'        
                         when 4 then 'Abril'        
                         when 5 then 'Maio'        
                         when 6 then 'Junho'        
                         when 7 then 'Julho'
                         when 8 then 'Agosto'
                         when 9 then 'Setembro'
                         when 10 then 'Outubro'        
                         when 11 then 'Novembro'        
                         when 12 then 'Dezembro'
                       end ,'/',year(b.data_cadastro)
                    ) as Country 
                    FROM cs2.cadastro a
                    INNER JOIN consulta.alertas b ON a.codloja = b.codloja
                    Where $selecao 
                    b.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(b.data_cadastro) , year(b.data_cadastro)
                    ORDER BY b.data_cadastro";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_15($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT count(*) as Average, 
                        CONCAT( 
                            CASE month(a.data_cadastro) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_cadastro)
                        ) as Country 
                    FROM base_web_control.cliente a
                    inner join cs2.cadastro b on a.id_cadastro =  b.codloja
                    WHERE $selecao 
                        a.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(a.data_cadastro) , year(a.data_cadastro)
                    ORDER BY a.data_cadastro";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_16($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        /*
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT count(*) as Average, 
                        CONCAT( 
                            CASE month(a.data_cadastro) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_cadastro)
                        ) as Country 
                    FROM base_web_control.produto a
                    inner join cs2.cadastro b on a.id_cadastro =  b.codloja
                    WHERE $selecao 
                        a.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(a.data_cadastro) , year(a.data_cadastro)
                    ORDER BY a.data_cadastro";
		*/
	    $strSQL = " SELECT sum(a.valor) as Average, a.dados AS Country FROM cs2.grafico_desempenho a
	                WHERE a.id_grafico = '16' $selecao";
	    if ( $selecao == '' )
	        $strSQL .= " GROUP BY a.data ";
		else
	    	$strSQL .= " GROUP BY a.id_franquia, a.dados ";

		$strSQL .= " ORDER BY a.data ASC
	                LIMIT 12";
	                
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_17($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        /*
        $strSQL = " SELECT count(*) as Average, 
                        CONCAT( 
                            CASE month(a.data_venda) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_venda)
                        ) as Country 
                    FROM base_web_control.venda a
                    inner join cs2.cadastro b on a.id_cadastro =  b.codloja
                    WHERE $selecao 
                        a.data_venda between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(a.data_venda) , year(a.data_venda)
                    ORDER BY a.data_venda";
		*/
	    $strSQL = " SELECT sum(a.valor) as Average, a.dados AS Country FROM cs2.grafico_desempenho a
	                WHERE a.id_grafico = '17' $selecao";
	    if ( $selecao == '' )
	        $strSQL .= " GROUP BY a.data ";
		else
	    	$strSQL .= " GROUP BY a.id_franquia, a.dados ";

		$strSQL .= " ORDER BY a.data ASC
	                LIMIT 12";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }
            return $arr;
        }
    }

    function grafico_franquia_novo_18($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        /*
        $strSQL = " SELECT count(*) as Average, 
                        CONCAT( 
                            CASE month(a.data_hora) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_hora)
                        ) as Country 
                    FROM base_web_control.venda_notas_eletronicas a
                    INNER JOIN base_web_control.venda b ON a.id_venda = b.id
                    inner join cs2.cadastro c on b.id_cadastro =  c.codloja
                    WHERE $selecao 
                        a.data_hora between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                        AND
                            a.tipo_nota = 'NFE'
                        AND 
                            a.status = 5
                    GROUP BY month(a.data_hora) , year(a.data_hora)
                    ORDER BY a.data_hora";
		*/
	    $strSQL = " SELECT sum(a.valor) as Average, a.dados AS Country FROM cs2.grafico_desempenho a
	                WHERE a.id_grafico = '18' $selecao";

	    if ( $selecao == '' )
	        $strSQL .= " GROUP BY a.data ";
		else
	    	$strSQL .= " GROUP BY a.id_franquia, a.dados ";

		$strSQL .= " ORDER BY a.data ASC
	                LIMIT 12";

        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    
    function grafico_franquia_novo_19($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        /*
        $strSQL = " SELECT count(*) as Average, 
                        CONCAT( 
                            CASE month(a.data_hora) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_hora)
                        ) as Country 
                    FROM base_web_control.venda_notas_eletronicas a
                    INNER JOIN base_web_control.venda b ON a.id_venda = b.id
                    inner join cs2.cadastro c on b.id_cadastro =  c.codloja
                    WHERE $selecao 
                        a.data_hora between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                        AND
                            a.tipo_nota = 'NFC'
                        AND 
                            a.status = 5
                    GROUP BY month(a.data_hora) , year(a.data_hora)
                    ORDER BY a.data_hora";
		*/
	    $strSQL = " SELECT sum(a.valor) as Average, a.dados AS Country FROM cs2.grafico_desempenho a
	                WHERE a.id_grafico = '19' $selecao";

	    if ( $selecao == '' )
	        $strSQL .= " GROUP BY a.data ";
		else
	    	$strSQL .= " GROUP BY a.id_franquia, a.dados ";

		$strSQL .= " ORDER BY a.data ASC
	                LIMIT 12";

        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }
            return $arr;
        }
    }

    function grafico_franquia_novo_20($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        /*
        $strSQL = " SELECT count(*) as Average, 
                        CONCAT( 
                            CASE month(a.data_hora) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_hora)
                        ) as Country 
                    FROM base_web_control.venda_notas_eletronicas a
                    INNER JOIN base_web_control.venda b ON a.id_venda = b.id
                    inner join cs2.cadastro c on b.id_cadastro =  c.codloja
                    WHERE $selecao 
                        a.data_hora between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                        AND
                            a.tipo_nota = 'NFS' 
                        AND 
                            a.status = 5
                    GROUP BY month(a.data_hora) , year(a.data_hora)
                    ORDER BY a.data_hora";
		*/
	    $strSQL = " SELECT sum(a.valor) as Average, a.dados AS Country FROM cs2.grafico_desempenho a
	                WHERE a.id_grafico = '20' $selecao";

	    if ( $selecao == '' )
	        $strSQL .= " GROUP BY a.data ";
		else
	    	$strSQL .= " GROUP BY a.id_franquia, a.dados ";

		$strSQL .= " ORDER BY a.data ASC
	                LIMIT 12";

        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_21($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT 
                        count(*) as Average,
                        CONCAT( 
                            CASE month(a.data_compra) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.data_compra)
                        ) as Country 
                    FROM cs2.cadastro_equipamento a
                    INNER JOIN cs2.cadastro_equipamento_descricao b ON a.id = b.id_cadastro_equipamento
                    INNER JOIN cs2.cadastro c on a.codloja = c.codloja
                    WHERE
                        $selecao
                        a.data_compra  between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY MONTH(a.data_compra), YEAR(a.data_compra)
                    ORDER BY a.data_compra;";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }
            return $arr;
        }
    }
    
    function grafico_franquia_novo_22($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
        
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT
                        FORMAT( sum(valor) / count(*), 2 ) as Average,
                        CONCAT( 
                            CASE month(a.vencimento) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.vencimento)
                        ) as Country 
                    FROM cs2.titulos a
                    INNER JOIN cs2.cadastro b on a.codloja=b.codloja
                    INNER JOIN cs2.franquia c on b.id_franquia = c.id
                    WHERE
                            $selecao
                            mid(a.numdoc,1,1) <> '9'
                        AND
                            a.vencimento BETWEEN 
                                CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') 
                                AND 
                                CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL -30 DAY)),1,7),'-01')
                    GROUP BY MONTH(a.vencimento), YEAR(a.vencimento)
                    ORDER BY a.vencimento";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_franquia_novo_08($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT count(*) as Average, concat( 
                        case month(b.dt_envio_cartorio) 
                         when 1 then 'Janeiro'
                         when 2 then 'Fevereiro'        
                         when 3 then 'Marco'        
                         when 4 then 'Abril'        
                         when 5 then 'Maio'        
                         when 6 then 'Junho'        
                         when 7 then 'Julho'
                         when 8 then 'Agosto'
                         when 9 then 'Setembro'
                         when 10 then 'Outubro'        
                         when 11 then 'Novembro'        
                         when 12 then 'Dezembro'
                       end ,'/',year(b.dt_envio_cartorio)
                    ) as Country 
                    FROM cs2.cadastro a
                    INNER JOIN consulta.alertas b ON a.codloja = b.codloja
                    Where $selecao 
                    b.dt_envio_cartorio between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(b.dt_envio_cartorio) , year(b.dt_envio_cartorio)
                    ORDER BY b.dt_envio_cartorio";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }
    
    function grafico_franquia_novo_09($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT count(*) Average, b.debito,
                        concat( 
                            case month(b.amd) 
                             when 1 then 'Janeiro'
                             when 2 then 'Fevereiro'        
                             when 3 then 'Marco'        
                             when 4 then 'Abril'        
                             when 5 then 'Maio'        
                             when 6 then 'Junho'        
                             when 7 then 'Julho'
                             when 8 then 'Agosto'
                             when 9 then 'Setembro'
                             when 10 then 'Outubro'        
                             when 11 then 'Novembro'        
                             when 12 then 'Dezembro'
                            end ,'/',year(b.amd)
                        ) as Country
        
                    FROM cs2.cadastro a
                    INNER JOIN cs2.cons b ON a.codloja = b.codloja
                    WHERE $selecao 
                            b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                            AND (mid(b.debito,1,3)='A04')
                    GROUP BY month(b.amd) , year(b.amd)
                    ORDER BY b.amd";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }
    
    function grafico_franquia_novo_10($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = "SELECT count(*) Average, 
                        concat( 
                            case month(b.data_hora) 
                                when 1 then 'Janeiro'
                                when 2 then 'Fevereiro'        
                                when 3 then 'Marco'        
                                when 4 then 'Abril'        
                                when 5 then 'Maio'        
                                when 6 then 'Junho'        
                                when 7 then 'Julho'
                                when 8 then 'Agosto'
                                when 9 then 'Setembro'
                                when 10 then 'Outubro'        
                                when 11 then 'Novembro'        
                                when 12 then 'Dezembro'
                            end ,'/',year(b.data_hora)) as Country 
                    FROM cs2.cadastro a 
                    INNER JOIN contabil.acessos b ON a.codloja = b.id_cadastro 
                    WHERE $selecao
                          b.data_hora BETWEEN CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY MONTH(b.data_hora) , YEAR(b.data_hora)
                    ORDER BY b.data_hora";
        
    //    echo "<pre>";
    //    print_r( $strSQL );
        
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }

    function grafico_virtualflex_novo($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = " SELECT count(*) as Average, concat( 
                        CASE month(fra_data_hora) 
                         when 1  then 'Jan'
                         when 2  then 'Fev'        
                         when 3  then 'Mar'        
                         when 4  then 'Abr'        
                         when 5  then 'Mai'        
                         when 6  then 'Jun'        
                         when 7  then 'Jul'
                         when 8  then 'Ago'
                         when 9  then 'Set'
                         when 10 then 'Out'        
                         when 11 then 'Nov'        
                         when 12 then 'Dez'
                       end ,'/',year(fra_data_hora)
                    ) as Country 
                    FROM dbsites.tbl_framecliente
                    WHERE $selecao fra_codloja > 1
                    AND  fra_data_hora BETWEEN CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    GROUP BY month(fra_data_hora) , year(fra_data_hora)
                    ORDER BY fra_data_hora";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }

            return $arr;
        }
    }
    

/** FIM NOVO MÉTODO PARA GRAFICOS FRANQUIA */

/*  GRAFICO PARA PAGINA DE FRANQUIA */


function grafico_franquia_01($selecao,&$FC) {
    // Function to connect to the DB
    /*
    $link = connectToDB();
	$strSQL = " SELECT count(*) Average, c.nome as Country FROM cs2.cadastro a
                INNER JOIN cs2.cons b ON a.codloja = b.codloja
                INNER JOIN cs2.valcons c ON b.debito = c.codcons
                WHERE $selecao 
                        b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                        AND b.debito !='A0230' AND b.debito != 'A0232'
                GROUP BY b.debito
                ORDER BY Average";
    */

    $strSQL = " SELECT sum(a.valor) as Average, a.dados AS Country FROM cs2.grafico_desempenho a
                WHERE a.id_grafico = '1' $selecao";
    if ( $selecao == '' )
        $strSQL .= " GROUP BY a.data ";
	else
    	$strSQL .= " GROUP BY a.id_franquia, a.dados ";

	$strSQL .= " ORDER BY a.data ASC
                LIMIT 12";

    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
                # Add Chart Data
                $FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
                # Add Chart Data
                $FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_02($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = "SELECT count(*) Average, b.debito, concat( 
						case month(b.amd) 
						 when 1 then 'Janeiro'
						 when 2 then 'Fevereiro'        
						 when 3 then 'Marco'        
						 when 4 then 'Abril'        
						 when 5 then 'Maio'        
						 when 6 then 'Junho'        
						 when 7 then 'Julho'
						 when 8 then 'Agosto'
						 when 9 then 'Setembro'
						 when 10 then 'Outubro'        
						 when 11 then 'Novembro'        
						 when 12 then 'Dezembro'
					   end ,'/',year(b.amd)) as Country 
					FROM cs2.cadastro a 
					INNER JOIN cs2.cons b ON a.codloja = b.codloja 
					WHERE $selecao 
					      b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
					      AND (b.debito ='A0230' or b.debito = 'A0232') 
					GROUP BY month(b.amd) , year(b.amd)
					ORDER BY b.amd";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_13($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = "SELECT count(*) Average1, sum(qtd_registro) as Average, b.tipo_consulta, 
                    concat( 
                        case month(b.data) 
                            when 1 then 'Janeiro'
                            when 2 then 'Fevereiro'        
                            when 3 then 'Marco'        
                            when 4 then 'Abril'        
                            when 5 then 'Maio'        
                            when 6 then 'Junho'        
                            when 7 then 'Julho'
                            when 8 then 'Agosto'
                            when 9 then 'Setembro'
                            when 10 then 'Outubro'        
                            when 11 then 'Novembro'        
                            when 12 then 'Dezembro'
                        end ,
                        '/',
                        year(b.data)
                    ) as Country 
                FROM cs2.cadastro a 
                INNER JOIN cs2.cons_localiza b ON a.codloja = b.codloja 
                WHERE $selecao 
                      b.data between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                      AND (b.tipo_consulta ='A0231') 
                GROUP BY month(b.data) , year(b.data)
                ORDER BY b.data";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
                # Add Chart Data
                $FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
                # Add Chart Data
                $FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_14($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = "SELECT count(*) Average, concat( 
                    CASE MONTH(a.dt_cad) 
                        WHEN 1 THEN 'Janeiro'
                        WHEN 2 THEN 'Fevereiro'        
                        WHEN 3 THEN 'Marco'        
                        WHEN 4 THEN 'Abril'        
                        WHEN 5 THEN 'Maio'        
                        WHEN 6 THEN 'Junho'        
                        WHEN 7 THEN 'Julho'
                        WHEN 8 THEN 'Agosto'
                        WHEN 9 THEN 'Setembro'
                        WHEN 10 THEN 'Outubro'        
                        WHEN 11 THEN 'Novembro'        
                        WHEN 12 THEN 'Dezembro'
                    end ,'/',year(a.dt_cad)) AS Country 
                FROM cs2.cadastro a
                WHERE $selecao 
                      a.dt_cad between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 730 DAY)),1,7),'-01') AND NOW()
                GROUP BY month(a.dt_cad) , year(a.dt_cad)
                ORDER BY a.dt_cad";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_15($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    /*
    $strSQL = " SELECT count(*) as Average, 
                    CONCAT( 
                        CASE month(a.data_cadastro) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.data_cadastro)
                    ) as Country 
                FROM base_web_control.cliente a
                inner join cs2.cadastro b on a.id_cadastro =  b.codloja
                WHERE $selecao 
                    a.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                GROUP BY month(a.data_cadastro) , year(a.data_cadastro)
                ORDER BY a.data_cadastro";
	*/
    $strSQL = " SELECT sum(a.valor) as Average, a.dados AS Country FROM cs2.grafico_desempenho a
                WHERE a.id_grafico = '15' $selecao
                GROUP BY a.data
                ORDER BY a.data ASC
                LIMIT 12";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_16($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT count(*) as Average, 
                    CONCAT( 
                        CASE month(a.data_cadastro) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.data_cadastro)
                    ) as Country 
                FROM base_web_control.produto a
                inner join cs2.cadastro b on a.id_cadastro =  b.codloja
                WHERE $selecao 
                    a.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                GROUP BY month(a.data_cadastro) , year(a.data_cadastro)
                ORDER BY a.data_cadastro";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_17($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT count(*) as Average, 
                    CONCAT( 
                        CASE month(a.data_venda) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.data_venda)
                    ) as Country 
                FROM base_web_control.venda a
                inner join cs2.cadastro b on a.id_cadastro =  b.codloja
                WHERE $selecao 
                    a.data_venda between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                GROUP BY month(a.data_venda) , year(a.data_venda)
                ORDER BY a.data_venda";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_18($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT count(*) as Average, 
                    CONCAT( 
                        CASE month(a.data_hora) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.data_hora)
                    ) as Country 
                FROM base_web_control.venda_notas_eletronicas a
                INNER JOIN base_web_control.venda b ON a.id_venda = b.id
                inner join cs2.cadastro c on b.id_cadastro =  c.codloja
                WHERE $selecao 
                    a.data_hora between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    AND
                        a.tipo_nota = 'NFE'
                    AND 
                        a.status = 5
                GROUP BY month(a.data_hora) , year(a.data_hora)
                ORDER BY a.data_hora";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_19($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT count(*) as Average, 
                    CONCAT( 
                        CASE month(a.data_hora) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.data_hora)
                    ) as Country 
                FROM base_web_control.venda_notas_eletronicas a
                INNER JOIN base_web_control.venda b ON a.id_venda = b.id
                inner join cs2.cadastro c on b.id_cadastro =  c.codloja
                WHERE $selecao 
                    a.data_hora between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    AND
                        a.tipo_nota = 'NFC'
                    AND 
                        a.status = 5
                GROUP BY month(a.data_hora) , year(a.data_hora)
                ORDER BY a.data_hora";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_20($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT count(*) as Average, 
                    CONCAT( 
                        CASE month(a.data_hora) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.data_hora)
                    ) as Country 
                FROM base_web_control.venda_notas_eletronicas a
                INNER JOIN base_web_control.venda b ON a.id_venda = b.id
                inner join cs2.cadastro c on b.id_cadastro =  c.codloja
                WHERE $selecao 
                    a.data_hora between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                    AND
                        a.tipo_nota = 'NFS' 
                    AND 
                        a.status = 5
                GROUP BY month(a.data_hora) , year(a.data_hora)
                ORDER BY a.data_hora";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_21($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'c.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT 
                    count(*) as Average,
                    CONCAT( 
                        CASE month(a.data_compra) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.data_compra)
                    ) as Country 
                FROM cs2.cadastro_equipamento a
                INNER JOIN cs2.cadastro_equipamento_descricao b ON a.id = b.id_cadastro_equipamento
                INNER JOIN cs2.cadastro c on a.codloja = c.codloja
                WHERE
                    $selecao
                    a.data_compra  between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                GROUP BY MONTH(a.data_compra), YEAR(a.data_compra)
                ORDER BY a.data_compra;";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_22($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT
                    FORMAT( sum(valor) / count(*), 2 ) as Average,
                    CONCAT( 
                        CASE month(a.vencimento) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.vencimento)
                    ) as Country 
                FROM cs2.titulos a
                INNER JOIN cs2.cadastro b on a.codloja=b.codloja
                INNER JOIN cs2.franquia c on b.id_franquia = c.id
                WHERE
                        $selecao
                        mid(a.numdoc,1,1) <> '9'
                    AND
                        a.vencimento BETWEEN 
                            CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') 
                            AND 
                            CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL -30 DAY)),1,7),'-01')
                GROUP BY MONTH(a.vencimento), YEAR(a.vencimento)
                ORDER BY a.vencimento";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
            # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_23($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
    $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
    
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = " SELECT
                    sum(valor)as Average,
                    CONCAT( 
                        CASE month(a.vencimento) 
                            WHEN 1 THEN 'Janeiro'
                            WHEN 2 THEN 'Fevereiro'        
                            WHEN 3 THEN 'Marco'        
                            WHEN 4 THEN 'Abril'        
                            WHEN 5 THEN 'Maio'        
                            WHEN 6 THEN 'Junho'        
                            WHEN 7 THEN 'Julho'
                            WHEN 8 THEN 'Agosto'
                            WHEN 9 THEN 'Setembro'
                            WHEN 10 THEN 'Outubro'        
                            WHEN 11 THEN 'Novembro'        
                            WHEN 12 THEN 'Dezembro'
                        end ,
                        '/',
                        year(a.vencimento)
                    ) as Country 
                FROM base_web_control.carne a
                INNER JOIN cs2.cadastro b on a.id_cadastro = b.codloja
                INNER JOIN cs2.franquia c on b.id_franquia = c.id
                WHERE
                    $selecao
                    a.vencimento BETWEEN 
                        CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') 
                        AND 
                        CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 0 DAY)),1,7),'-01')
                GROUP BY MONTH(a.vencimento), YEAR(a.vencimento)
                ORDER BY a.vencimento";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while ($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(" . $intYear . "," . chr(34) . $ors['Country'] . chr(34) . ");");
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL) . ";link=" . $strLink, "");
            } else
                # Add Chart Data
                $FC->addChartData($ors['Average'], "label=" . escapeXML($ors['Country'], $forDataURL), "");
        }
    }
    mysql_close($link);
}

function grafico_franquia_03($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = " SELECT sum(b.valor) as Average, concat( 
					case month(b.datapg) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(b.datapg)
				) as Country 
				FROM cs2.cadastro a
                INNER JOIN cs2.titulos_recebafacil b ON a.codloja = b.codloja
				Where $selecao 
				b.datapg between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                and b.tp_titulo = 1 and 
				( b.valorpg > 0 ) AND ( b.descricao_repasse IS NULL )
				GROUP BY month(b.datapg) , year(b.datapg)
				ORDER BY b.datapg";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

    function grafico_EmailMarketing($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = "SELECT 
                        sum(total_emails_enviados) AS Average,
                        CONCAT( 
                            CASE month(a.dt_creation) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.dt_creation)
                        ) as Country 

                   FROM base_web_control.mailmkt_log a
                   INNER JOIN cs2.cadastro b ON a.id_cadastro = b.codloja
                   WHERE
                        $selecao
                        a.dt_creation BETWEEN SUBDATE(NOW(), INTERVAL 365 DAY) AND NOW()
                   GROUP BY YEAR(a.dt_creation), MONTH(a.dt_creation)";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }
            return $arr;
        }
    }

    function grafico_whatsAppMarketing($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        $strSQL = "SELECT 
                        COUNT(*) AS Average,
                        CONCAT( 
                            CASE month(a.dt_creation) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.dt_creation)
                        ) as Country 

                   FROM base_web_control.whatsapp_transacao a
                   INNER JOIN cs2.cadastro b ON a.id_cadastro = b.codloja
                   WHERE
                        $selecao
                        a.dt_creation BETWEEN SUBDATE(NOW(), INTERVAL 365 DAY) AND NOW()
                   GROUP BY YEAR(a.dt_creation), MONTH(a.dt_creation)";
        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }
            return $arr;
        }
    }

    function grafico_torpedoMarketing($intYear, $selecao, $addJSLinks, $forDataURL, &$FC) {
    
        $selecao = str_replace('a.id_franquia', 'b.id_franquia', $selecao);
        
        // Function to connect to the DB
        $link = connectToDB();
        echo "<pre>".$strSQL = "SELECT 
                        COUNT(*) AS Average,
                        CONCAT( 
                            CASE month(a.dh_entrada) 
                                WHEN 1 THEN 'Janeiro'
                                WHEN 2 THEN 'Fevereiro'        
                                WHEN 3 THEN 'Marco'        
                                WHEN 4 THEN 'Abril'        
                                WHEN 5 THEN 'Maio'        
                                WHEN 6 THEN 'Junho'        
                                WHEN 7 THEN 'Julho'
                                WHEN 8 THEN 'Agosto'
                                WHEN 9 THEN 'Setembro'
                                WHEN 10 THEN 'Outubro'        
                                WHEN 11 THEN 'Novembro'        
                                WHEN 12 THEN 'Dezembro'
                            end ,
                            '/',
                            year(a.dh_entrada)
                        ) as Country 

                   FROM base_web_control.torpedo_campanha_lista a
				   INNER JOIN base_web_control.torpedo_campanha b ON a.id_campanha = b.id
                   INNER JOIN cs2.cadastro c ON b.id_cadastro = c.codloja
                   WHERE
                   		$selecao
                        a.dh_entrada BETWEEN SUBDATE(NOW(), INTERVAL 365 DAY) AND NOW()
                   GROUP BY YEAR(a.dh_entrada), MONTH(a.dh_entrada)";

        $result = mysql_query($strSQL) or die($strSQL);
        if ($result) {
            $i = 0;
            while($ors = mysql_fetch_array($result)) {
                $arr[$i]['Average'] = $ors['Average'];
                $arr[$i]['Country'] = $ors['Country'];
                $arr[$i]['color'] = '#0000ff';
                $i++;
            }
            return $arr;
        }
    }

function grafico_virtualflex($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB_Virtual();
	$strSQL = " SELECT count(*) as Average, concat( 
					CASE month(fra_data_hora) 
					 when 1  then 'Janeiro'
					 when 2  then 'Fevereiro'        
					 when 3  then 'Marco'        
					 when 4  then 'Abril'        
					 when 5  then 'Maio'        
					 when 6  then 'Junho'        
					 when 7  then 'Julho'
					 when 8  then 'Agosto'
					 when 9  then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(fra_data_hora)
				) as Country 
				FROM dbsites.tbl_framecliente
				WHERE $selecao fra_codloja > 1
				GROUP BY month(fra_data_hora) , year(fra_data_hora)
				ORDER BY fra_data_hora";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_04($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = " SELECT sum(b.valor) as Average, concat( 
					case month(b.datapg) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(b.datapg)
				) as Country 
				FROM cs2.cadastro a
                INNER JOIN cs2.titulos_recebafacil b ON a.codloja = b.codloja
				Where $selecao 
				b.datapg between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                and ( b.tp_titulo = 2 or b.tp_titulo = 3 ) and 
				( b.valorpg > 0 ) AND ( b.descricao_repasse IS NULL )
				GROUP BY month(b.datapg) , year(b.datapg)
				ORDER BY b.datapg";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_05($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = " SELECT count(*) as Average, concat( 
					case month(b.amd) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(b.amd)
				) as Country 
				FROM cs2.cadastro a
                INNER JOIN cs2.relacionamento_consumidor b ON a.codloja = b.codloja
				Where $selecao 
				b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
				GROUP BY month(b.amd) , year(b.amd)
				ORDER BY b.amd";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_06($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = " SELECT count(*) as Average, concat( 
					case month(b.data_cadastro) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(b.data_cadastro)
				) as Country 
				FROM cs2.cadastro a
                INNER JOIN consulta.alertas b ON a.codloja = b.codloja
				Where $selecao 
				b.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
				GROUP BY month(b.data_cadastro) , year(b.data_cadastro)
				ORDER BY b.data_cadastro";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_07($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = " SELECT count(*) as Average, concat( 
					case month(b.data_cadastro) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(b.data_cadastro)
				) as Country 
				FROM cs2.cadastro a
                INNER JOIN base_web_control.cliente b ON a.codloja = b.id_cadastro
				Where $selecao 
				b.data_cadastro between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
				GROUP BY month(b.data_cadastro) , year(b.data_cadastro)
				ORDER BY b.data_cadastro";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_08($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = " SELECT count(*) as Average, concat( 
					case month(b.dt_envio_cartorio) 
					 when 1 then 'Janeiro'
					 when 2 then 'Fevereiro'        
					 when 3 then 'Marco'        
					 when 4 then 'Abril'        
					 when 5 then 'Maio'        
					 when 6 then 'Junho'        
					 when 7 then 'Julho'
					 when 8 then 'Agosto'
					 when 9 then 'Setembro'
					 when 10 then 'Outubro'        
					 when 11 then 'Novembro'        
					 when 12 then 'Dezembro'
				   end ,'/',year(b.dt_envio_cartorio)
				) as Country 
				FROM cs2.cadastro a
                INNER JOIN consulta.alertas b ON a.codloja = b.codloja
				Where $selecao 
				b.dt_envio_cartorio between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
				GROUP BY month(b.dt_envio_cartorio) , year(b.dt_envio_cartorio)
				ORDER BY b.dt_envio_cartorio";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_09($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
	$strSQL = " SELECT count(*) Average, b.debito,
					concat( 
						case month(b.amd) 
						 when 1 then 'Janeiro'
						 when 2 then 'Fevereiro'        
						 when 3 then 'Marco'        
						 when 4 then 'Abril'        
						 when 5 then 'Maio'        
						 when 6 then 'Junho'        
						 when 7 then 'Julho'
						 when 8 then 'Agosto'
						 when 9 then 'Setembro'
						 when 10 then 'Outubro'        
						 when 11 then 'Novembro'        
						 when 12 then 'Dezembro'
				 	   end ,'/',year(b.amd)
					) as Country
	
				FROM cs2.cadastro a
				INNER JOIN cs2.cons b ON a.codloja = b.codloja
				WHERE $selecao 
						b.amd between CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
						AND (mid(b.debito,1,3)='A04')
				GROUP BY month(b.amd) , year(b.amd)
				ORDER BY b.amd";
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function grafico_franquia_10($intYear, $selecao, $addJSLinks, $forDataURL,&$FC) {
    // Function to connect to the DB
    $link = connectToDB();
    $strSQL = "SELECT count(*) Average, 
                    concat( 
                        case month(b.data_hora) 
                            when 1 then 'Janeiro'
                            when 2 then 'Fevereiro'        
                            when 3 then 'Marco'        
                            when 4 then 'Abril'        
                            when 5 then 'Maio'        
                            when 6 then 'Junho'        
                            when 7 then 'Julho'
                            when 8 then 'Agosto'
                            when 9 then 'Setembro'
                            when 10 then 'Outubro'        
                            when 11 then 'Novembro'        
                            when 12 then 'Dezembro'
                        end ,'/',year(b.data_hora)) as Country 
                FROM cs2.cadastro a 
                INNER JOIN contabil.acessos b ON a.codloja = b.id_cadastro 
                WHERE $selecao
                      b.data_hora BETWEEN CONCAT( MID( (SELECT SUBDATE(NOW(), INTERVAL 365 DAY)),1,7),'-01') AND NOW()
                GROUP BY MONTH(b.data_hora) , YEAR(b.data_hora)
                ORDER BY b.data_hora";
    
//    echo "<pre>";
//    print_r( $strSQL );
    
    $result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
            if ($addJSLinks) {
                $strLink = urlencode("javaScript:updateChart(".$intYear.",".chr(34).$ors['Country'].chr(34).");");
				# Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL).";link=".$strLink,"");
            } else
			    # Add Chart Data
				$FC->addChartData($ors['Average'],"label=".escapeXML($ors['Country'],$forDataURL),"");
        }
    }
    mysql_close($link);
}

function nome_franquia($id_franquia){
	$link = connectToDB();
	$strSQL = " SELECT fantasia FROM cs2.franquia WHERE id = '$id_franquia'";
	$result = mysql_query($strSQL) or die($strSQL);
    if ($result) {
        while($ors = mysql_fetch_array($result)) {
			$nomefranquia = $ors['fantasia'];
		}
	}
	mysql_close($link);
	return $nomefranquia;
}


?>