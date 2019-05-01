<t-if test="${Session::getInstance()->flash}">
    <div class="${ return 'alert '.Session::getInstance()->flash['class']}">
        ${ 
            echo Session::getInstance()->flash['message'];
            unset(Session::getInstance()->flash);
         }
    </div>
</t-if>