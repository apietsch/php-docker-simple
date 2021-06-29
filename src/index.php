<?php

echo "Hello my friend<br>";

$dummyapi_app_key = '60ad5230c816136740ac285d';

$mysqli = new mysqli("db", "root", "example", "mydatabase");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

/* check if server is alive */
if ($mysqli->ping()) {
    printf ("Our connection is ok!\n <p>");
} else {
    printf ("Error: %s\n", $mysqli->error);
}

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
// var_dump($json_data);
$api_result_count = count($json_data);
echo "<p> data from $dummy_api_url returned $api_result_count data sets <p>";

foreach ($json_data as $user)  {
    echo var_dump($user) ."<br />";
}

// array(6) { ["id"]=> string(24) "60d0fe4f5311236168a109ca" ["title"]=> string(2) "ms" ["firstName"]=> string(4) "Sara" 
//      ["lastName"]=> string(8) "Andersen" ["email"]=> string(25) "sara.andersen@example.com" 
//      ["picture"]=> string(48) "https://randomuser.me/api/portraits/women/58.jpg" } 

// create new user
// $sql_insert_user = "INSERT INTO user (firstname, lastname) VALUES('Elon', 'Musk')";
// $result_insert_user = $mysqli->query($sql_insert_user);
// $user_id_insert = $mysqli->insert_id;
// echo "created new user with id $user_id_insert <br>";

// // create post and uses id from user insert before $mysqli->insert_id
// $sql_insert_post = "INSERT INTO post (user_id, content) VALUES($user_id_insert, 'post content')";
// $result_insert_post = $mysqli->query($sql_insert_post);
// $post_id_insert = $mysqli->insert_id;
// echo "created new post with id $post_id_insert <br>";

$sql_select_users = 'SELECT u.id as users_id, u.firstname as users_firstname, u.lastname as users_lastname FROM user u';
if ($result_users = $mysqli->query($sql_select_users)) {
    echo "<p>All users with there posts:<br>";

    while ($obj = mysqli_fetch_object($result_users)) {
        
        echo "<p> db id: $obj->users_id firstname: $obj->users_firstname lastname: $obj->users_lastname <br>";

        $sql_users_posts = 'SELECT p.id as post_id, p.content as post_content FROM post p where p.user_id =' . $obj->users_id;
        if ($result_users_posts = $mysqli->query($sql_users_posts)) {
            while ($obj = mysqli_fetch_object($result_users_posts)) {
                echo "post id: $obj->post_id content: $obj->post_content <br>";
            }
        }
    }
    mysqli_free_result($result_users);
}

$mysqli->close();
