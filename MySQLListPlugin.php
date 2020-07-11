<?php

/**
 * MySQLListPlugin
 * Extract rows from database query.
 * Insert field value into markdown content
 */
class MySQLListPlugin extends AbstractPicoPlugin
{
   /**
	 * Stored config
	 */
	protected $config = array();
   
   
   /**
	 * Triggered after Pico has read its configuration
	 *
	 * @see    Pico::getConfig()
	 * @param  array &$config array of config variables
	 * @return void
	 */
	public function onConfigLoaded(array &$config)
	{
	$this->config = include('config.php'); 
	if (isset($config['mysql_source']))
        {
            $db_conf = $config['mysql_source'];
            $i=0;

	    foreach ($this->config as $db_config_key => $db_config_array)
	    {
		$one_db_conf = $db_conf[$db_config_key];
		foreach($one_db_conf as $query_name => $query_string)
		{
			$this->config[$db_config_key][$query_name] = $query_string;
			$i++;
		}
	    } 
        }
	}
	
    public function onContentPrepared(&$content)
    {
        // Search for Embed shortcodes allover the content
        preg_match_all('#\[db_source *.*?\]#s', $content, $matches);

        // Make sure we found some shortcodes
        if (count($matches[0]) > 0) {
            $error = false;
            // Walk through shortcodes one by one
            foreach ($matches[0] as $match) 
            {
                if ( ! preg_match('#query=[\"]([^\"]*)[\"]#s', $match, $query))
                    $error = true;
                if ( ! preg_match('/row=[\"]([^\"]*)[\"]/', $match, $row))
                    $error = true;
                if (! $error)
                {
		    $query_string="";
		    $db_name_string="";
		    $found = 0;
		    foreach ($this->config as $db_name => $db_conf_array)
		    {
			foreach($db_conf_array as $key => $value)
			{
				if($key == $query[1])	
				{
					$query_string = $value;
					$found = 1;
				}
			}
			if($found == 1)
				$db_name_string = $db_name;
		    }

                    $result = $this->makeQuery($db_name_string,$query_string,$row[1]);
                    // Replace embeding code with the shortcode in the content
                    $content = preg_replace('#\[db_source *.*?\]#s', $result, $content, 1);
                }
                else
                    $content = preg_replace('#\[db_source *.*?\]#s', '*MySQLList ERROR*', $content, 1);
                
                $error = false;
            }
        }
    }
    
    
   private function getLineWording($newsNameConst, $keyValueArray)
   {
	$keyarray=array();
	$valuearray=array();
	foreach ($keyValueArray as $key => $value)
	{
		array_push($keyarray, $key);
		array_push($valuearray, $value);
	}
	$newsDesc = str_replace($keyarray,$valuearray,  $newsNameConst);
	return $newsDesc;

   }
    
    private function makeQuery($dbconf, $query,$line)
    {
    $dbhost = $this->config[$dbconf]['host'];
    $dbuser = $this->config[$dbconf]['username'];
    $dbpwd = $this->config[$dbconf]['password'];
    $dbname = $this->config[$dbconf]['db_name'];
    // Create connection    
    $conn = new mysqli($dbhost, $dbuser, $dbpwd, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    $result = $conn->query($query);
    $results ="";
    if ($result->num_rows > 0) 
    {
        // output data of each row
        while($row = $result->fetch_assoc()) 
        {
          $rowording = array();
          foreach($row as $key => $value)
            $rowording['{'.$key.'}'] =$value;
          $results .= $this->getLineWording($line,$rowording) ."\n";
        }
    } 
    else 
    {
        $results =  "0 results";
    }
    
    $conn->close();
    return $results;
    
    }
   
}
