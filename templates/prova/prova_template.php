<!-- <t-form action="test.php">
    <div>Test</div>
</t-form>
<a href="/login"><button type="button" class="btn btn-primary">LOGIN</button></a> -->

<div t="testing-@{true}-stuff" t2="@{string}-stuff" t3="testing-@{int}" t4="testing-${echo 'ok';}-stuff" t5="testing-${echo 'ok';}" t6="${echo 'ok';}-stuff" test1="@{false}" test2="@{true}" test3="@{int}" test4="@{float}" test5="@{string}" compiled="${echo $this->model['string'];}" nullable="@{nullval}" default="@{default:[defaultest]}" default2="@{default:[${ echo 'compileddefault';}]}">
    ${ echo 'Test'; }
</div>

<!-- 'false' => false,
        'true' => true,
        'int' => 100,
        'float' => 99.99,
        'string' => 'CAVABONGA' -->

<?php
//  if($this->model['userlogged']){
//     echo "yeee";
// }else{
//     echo "NOT AUTH";
// }
?>