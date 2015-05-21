
<div class="col-lg-6 col-md-6 col-sm-6">


  <div class="panel panel-info">

    <div class="panel-heading">
      <h3 class="panel-title"><?=$user_info['name']?></h3>
    </div>

    <div class="panel-body">
      <div class="row">
        <div class="col-md-3 col-lg-3 " align="center">
          <img alt="User Pic" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100" class="img-circle"> 
        </div>


        <div class=" col-md-9 col-lg-9 "> 
          <table class="table table-user-information">
            <tbody>

              <tr>
                <td>Email</td>
                <td><?=$user_info['email']?></td>
              </tr>

              <tr>
                <td>Phone Number</td>
                <td><?=$user_info['telephone']?></td>
              </tr>

              <tr>
                <td>Home Address</td>
                <td><?=$user_info['address']?></td>
              </tr>

            </tbody>
          </table>

          <br>
        </div>
      </div>
      </div>
      <!-- <div class="panel-footer">
        <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
        <span class="pull-right">
        <a href="edit.html" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
        <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
        </span>
      </div> -->
    </div>

  </div>



</div>