<div class="container bg-faded">
    <div class="row">
        <div class="col-xs-12 col-lg-4 mx-auto login-form">
        <t-form action="@{action}" method="@{method}" domain="User">
            <h1 class="h3 mb-3 font-weight-normal text-center pt-2"><?php echo L::login_title; ?></h1>
            <input type="username" id="username" name="NomeUtente" class="form-control my-2" placeholder="<?php echo L::login_username ?>" required="" autofocus="">
            <input type="password" id="password" name="Password" class="form-control my-2" placeholder="<?php echo L::login_password ?>" required="" autofocus="">
            <div class="checkbox checkbox-primary">
                <input id="remember" name="remember" type="checkbox">
                <label for="remember" ><?php echo L::login_remember; ?></label>
            </div>
            <button class="btn btn-success btn-block" type="submit"><i class="fas fa-sign-in-alt"></i> <?php echo L::login_submit ?></button>
        </t-form>
        </div>
    </div>
</div>