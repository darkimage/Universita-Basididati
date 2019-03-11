<?php echo "HELLO!" ?>
<t-each collection="@{array}" key="${echo 'key';}" item="value">
    <t-test tatt="@{value}" test="${echo 'TEST';}">
        ${ 
            echo $this->model["value"]; 
            echo " - tatt:"; 
            echo $this->model["tatt"];
            echo " - test:";
            echo $this->model["test"];
            echo " - key:";
            echo $this->model["key"];
            echo " - array2:";
            print_r($this->model["array2"]);
        }
    </t-test>
    <t-each collection="@{array2}" key="key2" item="value2">
        <span>${echo $this->model["value2"];}</span>
    </t-each>
</t-each>