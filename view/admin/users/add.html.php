<?php

use Goteo\Library\Text;

$data = $this['data'];
?>
<div class="widget">
    <form action="/admin/users/add" method="post">
        <p>
            <label for="user-user">Login:</label><span style="font-style:italic;">Only letters, numbers and hyphen '-'. No spaces or accents or 'ñ' or 'ç' or other characters that are not letters, numbers or scripts.</span><br />
            <input type="text" id="user-user" name="userid" value="<?php echo $data['user'] ?>" style="width:250px" maxlength="50"/>
        </p>
        <p>
            <label for="user-name">Public name:</label><br />
            <input type="text" id="user-name" name="name" value=<?php echo $data['name'] ?>"" style="width:500px" maxlength="255"/>
        </p>
        <p>
            <label for="user-email">Email:</label><span style="font-style:italic;">Be valid.</span><br />
            <input type="text" id="user-email" name="email" value="<?php echo $data['email'] ?>" style="width:500px" maxlength="255"/>
        </p>
        <p>
            <label for="user-password">Password:</label><span style="font-style:italic;">Minimum 6 characters. It will be encrypted and cannot be seen</span><br />
            <input type="text" id="user-password" name="password" value="<?php echo $data['password'] ?>" style="width:500px" maxlength="255"/>
        </p>

        <input type="submit" name="add" value="Create this User" /><br />
        <span style="font-style:italic;font-weight:bold;">Attention! We check email arrives as if the user has been registered.</span>

    </form>
</div>