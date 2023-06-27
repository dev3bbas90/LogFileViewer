<?php
class File{
    private $file;
    private $start;
    private $paginate;

    /*
     |--------------------------------------------------------------------------
     | set file reader parameters
     |--------------------------------------------------------------------------
     | This constructor accept file path , start index and limit per page
     |
    */

    public function __construct($file , $start , $paginate)
    {
        $this->file     = $file;
        $this->start    = $start ;
        $this->paginate = $paginate   ??  10;
        //set_error_handler("customError");
    }

    /*
     |--------------------------------------------------------------------------
     | get file lines
     |--------------------------------------------------------------------------
     | This Function read file content and return array of pagination data 
     |   with content paginated result.
     | @return array
    */
    public function get_file_lines()
    {
        $start      =   $this->start;
        $paginate   =   $this->paginate ??  10;
        $file       =   dirname(__DIR__) . '/' . $this->file;

        // Check if File Exsists 
        if(!$this->file || !file_exists( $file ) )
            trigger_error("Please, Enter File Path !!" , E_USER_ERROR);
           
        // Check File Extension
        if (!preg_match('/\.(?:txt|php|log)$/', $this->file ))
            trigger_error("Warning, Incorrect File Format !!" , E_USER_WARNING);
            
        $filesize = filesize($file);
        if ($filesize > (3*1024))
            trigger_error("Warning, File size should not exceed 3MB !!" , E_USER_WARNING);
         
        $lines          =   file($file);   //file in to an array
        $lines_count    =   count($lines);

        $last_index     =   ($lines_count - $lines_count % $paginate) + 1;
        $start          =   $start >= 1 ? $start : 1;
        $start          =   $start < $lines_count ? $start : $last_index;
        
        $result         =   array_splice( $lines , $start - 1 , $paginate );
        // $result         =   implode('<br>' , $result);
        
        $max_line       =   $start + $paginate;
        $max_line       =   $max_line <= $lines_count ? $max_line : $lines_count;

        return [
            'start'     =>  $max_line,
            'previous'  =>  $start - $paginate >= 1 ? $start - $paginate : 0 ,
            'next'      =>  $max_line,
            'end'       =>  $last_index,
            'total'     =>  $lines_count,
            'status'    =>  'success',
            'code'      =>  '200',
            'data'      =>  $result ,
        ];
    }
}

set_error_handler("customError");

/*
  |--------------------------------------------------------------------------
  | call File Object
  |--------------------------------------------------------------------------
  | This Function take file name and content paramters and pass them to file class to read file, 
  |   then show content.
  | @param string file
  | @param integer start
  | @param integer paginate
  | @return void
  |
*/
function callFileObject($file , $start = 0  , $paginate = 10)
{
    $file = new File($file , $start , $paginate);
    echo json_encode($file->get_file_lines()) ;
}

/*
  |--------------------------------------------------------------------------
  | Error handler Custom function
  |--------------------------------------------------------------------------
  | This Function handle error and return error message in json format.
  | @param integer errno
  | @param string errstr
  | @return json
  |
*/
function customError($errno, $errstr) {
    $result = [
        'status'    => 'error',
        'code'      => $errno,
        'message'   => $errstr,
    ];
    echo json_encode($result);
    die();
}
