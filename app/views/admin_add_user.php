<div class="col-lg-6 col-md-6 col-sm-6">

    <form class="form-new-user" action="/registrate" method="post">

      <?if(!empty($success)):?>
        <div class="alert alert-success" role="alert"><?=$success?></div>
      <?endif;?>

      <?if(!empty($error)):?>
        <div class="alert alert-danger" role="alert"><?=$error?></div>
      <?endif;?>

      <label>Name</label>
        <input name="name" type="text" value="" class="form-control input-xlarge" required>

      <label>Email</label>
        <input name="email" type="text" value="" class="form-control input-xlarge" required>

      <label for="select1">User rights:</label>
      <select class="form-control" id="select1" name="role">
        <option value="user">user</option>
        <option value="admin">admin</option>
      </select>

      <label>Password</label>
        <input name="password" type="text" value="" class="form-control input-xlarge" required>

      <label>Salt</label>
        <input name="salt" type="text" value="" class="form-control input-xlarge" placeholder="not required field" required>

      <label>Iterations</label>
        <input name="iterations" type="text" value="" class="form-control input-xlarge" maxlength="3" placeholder="not required field; max value = 100" required>

      <label>Telephone</label>
        <input name="telephone" type="text" value="" class="form-control input-xlarge" required>

      <label>Address</label>
      <textarea name="address" rows="3" class="form-control input-xlarge" required>
      </textarea>

      <div>
        <button class="btn btn-lg btn-primary pull-right btn-adminbar" type="submit">Add user</button>
      </div>
    </form>

</div>