<t-if test="${(Session::getInstance()->flash) ? print('true') : print('false'); }">
    <div class="alert alert-danger">
        ${ echo Session::getInstance()->flash; }
    </div>
</t-if>