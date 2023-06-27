<?php
include 'Login.php';

/*
|--------------------------------------------------------------------------
| My Test
|--------------------------------------------------------------------------
|
| This Class is responsible for handling Feature Test and ensure that every end point is tested.
|
*/
class MyTest
{
    /*
        |--------------------------------------------------------------------------
        | assert Equals
        |--------------------------------------------------------------------------
        | This Function ensure that the 2 parameters are equevilant.
        | @param $expected
        | @param $actual
        | @param String $message
        | @return void
        |
    */
    public static function assertEquals($expected, $actual, $message = null )
    {
        if ($expected == $actual) {
            echo "<span style='color:green;'>Test passed ✔" . PHP_EOL . "</span>";
        } else {
            echo "<span style='color:brown;'>Test failed! Expected: " . $expected . ", Actual: " . $actual . PHP_EOL .  "</span>";
        }
        if($message ){
            echo " <br> $message ";
        }
        echo "<br>";
        echo "------------------------------------------------------------";
        echo "<br>";
    }

    /*
        |--------------------------------------------------------------------------
        | Send Get Request
        |--------------------------------------------------------------------------
        | This Function take Url and send GET request and return json response
        | @param string $url
        | @return Response
        |
    */
    public static function sendGetRequest($url)
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => 'Content-type: application/json',
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        return $response;
    }

    /*
        |--------------------------------------------------------------------------
        | assert Status
        |--------------------------------------------------------------------------
        | This Function take Url and expected status code 
        |  then send get request and check that respons estatus code matches expected status or not.
        |  return json response
        | @param string $url
        | @param integer $code
        | @return void
        |
    */
    public static function assertStatus($url, $code)
    {
        $response    = self::sendGetRequest(url().'/'.$url);
        $response    = json_decode($response);
        $actual_code = @$response->code;
        $message     = @$response->message;
        echo "test read file path : <span style='color:blue;'>`$url` </span> ( Expected: $code , Actual: $actual_code)";
        echo "<br>";
        return self::assertEquals($code, $actual_code , $message );
    }

    /*
        |--------------------------------------------------------------------------
        | assert Json Structure
        |--------------------------------------------------------------------------
        | This Function take Url and  expected response structure then send GET request 
        | and check whether response structure matches expected_structure or not.
        | @param string $url
        | @param array $expected_structure
        | @return void
        |
    */
    public static function assertJsonStructure($url, $expected_structure)
    {
        $response = self::sendGetRequest(url().'/'.$url);
        $response = json_decode($response);
        
        $response_keys = (array_keys(get_object_vars($response)));
        if ($response_keys == $expected_structure) {
            echo "<span style='color:green;'>Test passed ✔" . PHP_EOL . "</span>";
        } else {
            echo "<span style='color:brown;'>Test failed!</span>";
        }
        echo "<br>";
        echo "------------------------------------------------------------";
        echo "<br>";
    }

}

// Run the tests
// echo "<h1>Unit Test </h1>";
// echo json_encode(callFileObject("content/file.txt" , 0 , 10)) ;


echo "<h1>Feature Test </h1>";
MyTest::assertStatus('Main.php?file=content/file.txt&start=0&paginate=10'  , 200);
MyTest::assertStatus('Main.php?file=content/file2.txt&start=0&paginate=10' , 256);
MyTest::assertStatus('Main.php?file=content/image.png&start=0&paginate=10' , 256);

echo "<h3>Assert Json Structure </h3>";
$expected_structure = [ 'start' ,  'previous' ,  'next' ,  'end' ,  'total' ,  'status' ,  'code' ,  'data' ];
MyTest::assertJsonStructure('Main.php?file=content/file.txt&start=0&paginate=10' , $expected_structure);

$expected_structure = [ 'status' ,  'code' ,  'message' ];
MyTest::assertJsonStructure('Main.php?file=content/file.txt2&start=0&paginate=10' , $expected_structure);


/*
    |--------------------------------------------------------------------------
    | Get Base url
    |--------------------------------------------------------------------------
    | This Function returns cuurent file folder path on server
    | @return string
    |
*/
function url(){
    $url = sprintf(
      "%s://%s%s",
      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
      $_SERVER['SERVER_NAME'],
      $_SERVER['REQUEST_URI']
    );

    return str_replace('/MyTest.php' , '' , $url);
  }
  
  