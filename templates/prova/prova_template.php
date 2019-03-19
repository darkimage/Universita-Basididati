<t-form action="test.php">
    <div>Test</div>
</t-form>
<button type="button" class="btn btn-primary"><a href="/login">LOGIN</a></button>

<?php if($this->model['userlogged']){
    echo "yeee";
}else{
    echo "NOT AUTH";
}
?>