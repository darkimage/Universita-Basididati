<t-form controller="user" action="process" domain="User">
<h1 class="border-0"><!--Title--></h1>
<div class="pt-3 pl-5">
<input type="hidden" name="id" id="id" value="@{user->id:[]}">
<input type="hidden" name="update" id="update" value="@{update:[false]}">

<label for="Nome"><?php echo L::user_name ?>:</label>
<input type="text" class="form-control" required id="Nome" name="Nome" value="@{user->Nome:[]}" placeholder="${return L::user_name}">

<label for="Cognome"><?php echo L::user_surname ?>:</label>
<input type="text" class="form-control" required id="Cognome" name="Cognome" value="@{user->Cognome:[]}" placeholder="${return L::user_surname}">

<label class="pt-2" for="DataNascita"><?php echo L::user_birthday ?>:</label>
<input type="date" class="form-control" required id="DataNascita" name="DataNascita" value="@{user->DataNascita:[]}">

<div class="container-fluid pt-2 pl-0">
<div class="row">
    <div class="col-sm">
        <label for="NomeUtente"><?php echo L::user_username ?>:</label>
        <input type="text" class="form-control" required id="NomeUtente" name="NomeUtente" value="@{user->NomeUtente:[]}" placeholder="${return L::user_username}">
    </div>
    <div class="col-sm pr-0">
        <label for="Password"><?php echo L::user_password ?>:</label>
        <input type="password" class="form-control" <?php echo ($this->update == 'true') ? '' : 'required' ?> id="Password" name="Password" value="" placeholder="${return L::user_password}">
    </div>
</div>
</div>

<div class="d-flex justify-content-end">
    <input type="submit" class="mt-2 btn btn-primary" value="${return L::common_submit}" name="Submit">
</div>
</div>
</t-form>