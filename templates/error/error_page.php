<t-if test="${(Session::getInstance()->error) ? print('true') :  print('false');}">
    <div class="alert alert-danger text-center" role="alert">
        ${
            echo Session::getInstance()->error;
            unset(Session::getInstance()->error);
        }
    </div>
</t-if>
<t-if test="${(Session::getInstance()->error) ? print('false') :  print('true');}">
    <div class="alert alert-danger text-center" role="alert">
        ${ echo L::errors_general; }
    </div>
</t-if>