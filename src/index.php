<?php

class User {
    public int $id;
    public string $api_id;
    public String $firstname;
    public String $lastname;
    
    public function __construct(string $api_id) {
        $this->api_id = $api_id;
    }
        
    public function equals(User $user) {
      return $this->api_id === $compare->api_id; 
    }

    public function __toString()
    {
        try 
        {
            return (string) $this->firstname . " " . $this->api_id;
        } 
        catch (Exception $exception) 
        {
            return '';
        }
    }

    public static function parseAssocArray($user) {
        // array(6) { ["id"]=> string(24) "60d0fe4f5311236168a109ca" ["title"]=> string(2) "ms" ["firstName"]=> string(4) "Sara" 
        //      ["lastName"]=> string(8) "Andersen" ["email"]=> string(25) "sara.andersen@example.com" 
        //      ["picture"]=> string(48) "https://randomuser.me/api/portraits/women/58.jpg" } 

        $newUser = new User($user['id']);
        $newUser->firstname = $user['firstName'];
        $newUser->lastname = $user['lastName'];
        $newUser->title = $user['title'];
        $newUser->email = $user['email'];
        $newUser->picture = $user['picture'];
        return $newUser;
    }
    
}

class Post
{
    public $id;
    public $api_id;
    public $content;
}

class Database
{
    private $mysqli;

    public function __construct() {
        $this->mysqli = new mysqli("db", "root", "example", "mydatabase");

        // /* check connection */
        // if ($mysqli->connect_errno) {
        //     printf("Connect failed: %s\n", $mysqli->connect_error);
        //     exit();
        // }

        // /* check if server is alive */
        // if ($mysqli->ping()) {
        //     printf ("Our connection is ok!\n <p>");
        // } else {
        //     printf ("Error: %s\n", $mysqli->error);
        // }

    }

    public function persistUser($user) {
        $sql_insert_user = "INSERT INTO user (firstname, lastname) VALUES('{$user->firstname}', '{$user->lastname}')";
        $result_insert_user = $this->mysqli->query($sql_insert_user);
        if ($this -> mysqli -> error)
            error_log('an db error happend: ' . $this -> mysqli -> error);
        $user_id_insert = $this->mysqli->insert_id;
        error_log("created new user with id $user_id_insert");
        $user->id = $user_id_insert;
        return $user;
    }

    public function persistPost() {
        // // create post and uses id from user insert before $mysqli->insert_id
        // $sql_insert_post = "INSERT INTO post (user_id, content) VALUES($user_id_insert, 'post content')";
        // $result_insert_post = $mysqli->query($sql_insert_post);
        // $post_id_insert = $mysqli->insert_id;
        // echo "created new post with id $post_id_insert <br>";
    }

    public function allUsers() {
        $data = array();
        $sql_select_users = 'SELECT u.id as users_id, u.firstname as users_firstname, u.lastname as users_lastname FROM user u';
        if ($result_users = $this->mysqli->query($sql_select_users)) {
            while ($obj=mysqli_fetch_object($result_users)){
                $data[] = $obj;
             }
        }
        mysqli_free_result($result_users);

        return $data;
    }

    public function query($query) {
        return $this->mysqli->query($query);
    }
}

$dummyapi_app_key = '60ad5230c816136740ac285d';

$dummy_api_url = "https://dummyapi.io/data/api/user?limit=10";
$opts = [
    'http' => [
            'method' => 'GET',
            'header' => [
                    'User-Agent: PHP',
                    "app-id: $dummyapi_app_key"
            ]
    ]
];

$context = stream_context_create($opts);

$data = file_get_contents($dummy_api_url, false, $context);
$json_raw = json_decode($data, true);
$json_data = $json_raw["data"];

$api_result_count = count($json_data);

echo "<p> data from $dummy_api_url returned $api_result_count data sets <p>";

$db = new Database();

foreach ($json_data as $user)  {
    //var_dump($user);
    $newUser = User::parseAssocArray($user);
    echo "created user: $newUser <br>";
    $db->persistUser($newUser);
}

foreach ($db -> allUsers() as $user) {
    echo "user: <br>";
    var_dump($user);
    echo "<br>";
}



// $mysqli->close();
