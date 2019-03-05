<div class="container bg-faded">
    <div class="row">
        <div class="col-xs-12 col-lg-4 mx-auto login-form">
        <form action="<!--Action-->" method="<!--Method-->">
            <h1 class="h3 mb-3 font-weight-normal text-center pt-2"><?php echo L::login_title; ?></h1>
            <input type="username" id="username" class="form-control my-2" placeholder="<?php echo L::login_username ?>" required="" autofocus="">
            <input type="password" id="password" class="form-control my-2" placeholder="<?php echo L::login_password ?>" required="" autofocus="">
            <div class="checkbox checkbox-primary">
                <input id="checkbox2" type="checkbox">
                <label for="checkbox2"><?php echo L::login_remember; ?></label>
            </div>
            <button class="btn btn-success btn-block" type="submit"><i class="fas fa-sign-in-alt"></i> <?php echo L::login_submit ?></button>
        </form>
        </div>
    </div>
</div>