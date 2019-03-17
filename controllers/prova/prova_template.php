<t-form action="test.php">
    <div>Test</div>
</t-form>

<?php if($this->model['userlogged']){
    echo "yeee";
}else{
    echo "NOT AUTH";
}
?>