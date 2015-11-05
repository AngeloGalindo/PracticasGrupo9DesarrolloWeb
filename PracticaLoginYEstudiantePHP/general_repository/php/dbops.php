<?php

error_reporting(0);
date_default_timezone_set("America/Guatemala");
class DBOps
{
    var $user;
    var $password;
    var $namedb;

    
    function DBOps($r_user, $r_pwd, $r_db, $r_host )
    {
        $this->user = $r_user;
        $this->password = $r_pwd;
        $this->host  = $r_host;
		$this->namedb = $r_db;       
    }
	function get_allowed_types()
	{
		$link = mysql_connect($this->host, $this->user, $this->password);
		if($link)
		{
				$db_selected = mysql_select_db($this->namedb) ;
				if ($db_selected)
				{

						// Performing SQL query
						$query = "select * from movement_type where show_list = 1;";

						$result = mysql_query($query, $link);

						if($result)
						{								
							while ($row = mysql_fetch_assoc($result)) {
								$response_header = $row['idtype_movement'];//."#".row[""];
                            }
						}
						 else {
								$response_header = "";
								die('Could not connect:1 ' . mysql_error());
						}
				}  else {
						$response_header ="";
						die('Could not connect:2 ' . mysql_error());
				}
				// Closing connection
				mysql_close($link);
		}
		else {
				die('Could not connect:3 ' . mysql_error());
		}
	}
    function exec_proc($proc_name, $proc_params)
    {
            $link = mysql_connect($this->host, $this->user, $this->password);
            if($link)
            {
                    $db_selected = mysql_select_db($this->namedb) ;
                    if ($db_selected)
                    {

                            // Performing SQL query
                            $query = "call ".$proc_name."(".$proc_params.");";

                            $result = mysql_query($query, $link);

                            if($result)
                            {
                                    $response = 0;
                            }
                             else {
                                    $response_header = 1;
                                    die('Could not connect:1 ' . mysql_error());
                            }
                    }  else {
                            $response_header = 2;
                            die('Could not connect:2 ' . mysql_error());
                    }
                    // Closing connection
                    mysql_close($link);
            }
            else {
                    die('Could not connect:3 ' . mysql_error());
            }
    }
    function list_orders($query)
    {
		$link = mysql_connect($this->host, $this->user, $this->password);
		if($link)
		{
				$db_selected = mysql_select_db($this->namedb) ;
				if ($db_selected)
				{

						// Performing SQL query
												
						$result = mysql_query($query, $link);

						if($result)
						{								
							/*while ($row = mysql_fetch_assoc($result)) {
								echo "<TR><TD><INPUT TYPE='CHECKBOX'></TD>"."<TD>".$row["order_date"]."</TD>"."<TD>".$row["idcustomer"]."</TD>"."<TD>".$row["lastname"]."</TD>"."<TD>".$row["firstname"]."</TD>"."<TD>".$row["idorder"]."</TD>"."<TD>".$row["edition_date"]."</TD>"."<TD>".$row["total_items"]."</TD>"."<TD>".$row["total_price"]."</TD>"."<TD>".$row["name"]."</TD>"."<TD>".$row["username"]."</TD></TR>";
                            }*/
						}
						 else {
								$result = null;
								die('Could not connect:1 ' . mysql_error());
						}
				}  else {
						$result = null;
						die('Could not connect:2 ' . mysql_error());
				}
				// Closing connection
				mysql_close($link);
		}
		else {
				$result = null;
				die('Could not connect:3 ' . mysql_error().$this->host."ddd");
		}
		return $result;
	}
    function insert_to_db($query)
    {
        $response = FALSE;
        $link = mysql_connect($this->host, $this->user, $this->password);
        if($link)
        {
            //echo "link<br>";
            $db_selected = mysql_select_db($this->namedb);
            if ($db_selected)
            {
                $result = mysql_query($query, $link);

                if($result)
                {
                    $response = TRUE;
                     // Free resultset
                    $this->transac_id = mysql_insert_id();
                    //mysql_free_result($result);
                }  else {
                    //$message  = 'Invalid query: ' . mysql_error() . "\n";
                    //$message .= 'Whole query: ' . $query;
                    die('001 Could not connect: ' . mysql_error());

                }
            }
            else {
                $response_header = 2;
                die('002 Could not connect: ' . mysql_error());
            }
            // Closing connection
            mysql_close($link);
        }
         else {
                die('003 Could not connect: ' . mysql_error());
        }
    return $response;
    }

    function update_to_db($query)
    {
        $response = FALSE;
        $link = mysql_connect($this->host, $this->user, $this->password);
        if($link)
        {
            //echo "link<br>";
            $db_selected = mysql_select_db($this->namedb);
            if ($db_selected)
            {
                $result = mysql_query($query, $link);

                if($result)
                {
                    $response = TRUE;
                     // Free resultset
                    $this->transac_id = mysql_affected_rows();
                    //mysql_free_result($result);
                }  else {
                    //$message  = 'Invalid query: ' . mysql_error() . "\n";
                    //$message .= 'Whole query: ' . $query;
                    die('001 Could not connect: ' . mysql_error());

                }
            }
            else {
                $response_header = 2;
                die('002 Could not connect: ' . mysql_error());
            }
            // Closing connection
            mysql_close($link);
        }
         else {
                die('003 Could not connect: ' . mysql_error());
        }
        return $response;
    }
}
?>