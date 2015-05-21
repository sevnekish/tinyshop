<!-- registration form -->
<div class="container">
  <div class="registration-form">

  <form class="form-signin" action="/registrate" method="post">

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

    <label>Password</label>
      <input id="password1" type="password" name="password" type="text" value="" class="form-control input-xlarge" autocomplete="off" required>
    <label>Repeat Password</label>
      <input id="password2" type="password" name="password2" type="text" value="" class="form-control input-xlarge" autocomplete="off" required>
      
    <div class="row">
      <div class="col-sm-12">
        <span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
      </div>
    </div>

    <label>Telephone</label>
      <input name="telephone" type="text" value="" class="form-control input-xlarge" required>
    <label>Address</label>
    <textarea name="address" rows="3" class="form-control input-xlarge" required>
    </textarea>
    <br>
    <div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
    </div>
  </div>
</div>