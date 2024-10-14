<?php

$current_user = SessionManager::get(SessionValues::USER_INFO->value);

if (is_null($current_user)) { 
    header("HTTP/1.0 401 Unauthorized");
    content_render('errors/401');
    die();
}

echo "<h2 class='title'> All Users </h2>";

$users = UserRepository::getAllUsers();

echo "<table class='table is-fullwidth'>";
echo "<thead>
<tr> 

<th>ID</th>
<th>Username</th>
<th>Email</th>
<th>Auth Level</th>
<th>Delete </th>

</tr>
</thead>
";
echo "<tbody>";
foreach ($users as $user) {

    echo "<tr>";
    echo "<td>" . $user->getId() . "</td>";
    echo "<td>" . $user->getUsername() . "</td>";
    echo "<td>" . $user->getEmail() . "</td>";
    echo "<td>" . $user->getAuthLevel()->value . "</td>";
    if ($user->getId() == $current_user['id']) {
        echo "<td>Can't delete yourself</td>";
        continue;
    }
    else{
        echo "<td><a class='button is-danger' href='/users/delete?id=" . $user->getId() . "'>Delete</a></td>";
    }
    echo "</tr>";

}

echo "</tbody>";
echo "</table>";