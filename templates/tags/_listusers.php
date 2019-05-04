<?php

function printUser($user){
    echo "
    <div class='col-sm material-container mr-1'>
        <div class='card'>
        <div class='card-body'>
        <h2 class='card-title'>
            <t-link controller='user' action='show' overwrite params=\"\${return ['id'=>$user->id]}\">
                $user->Nome
            </t-link>
        </h2>
            <div class='item'><b>".L::user_username.":</b> $user->NomeUtente</div>
            <div class='item'><b>".L::project_plural.":</b> $user->projects</div>
        </div>
        </div>
    </div>";
}

$colsize = $this->colsize;
$needclose = false;
for ($i=0; $i < Count($this->users) ; $i++) { 
    if($i % $colsize){
        printUser($this->users[$i]);
    }else if($needclose){
        echo "</div>";
        $needclose = false;
        $i -= 1;
    }else{
        echo "<div class='row mt-2'>";
        printUser($this->users[$i]);
        $needclose = true;
    }
}
?>