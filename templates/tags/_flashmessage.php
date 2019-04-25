<t-if test="${Session::getInstance()->flash}">
    <div class="alert alert-danger">
        ${ echo Session::getInstance()->flash; }
    </div>
</t-if>