<html>
    <head>
        <title><!--Title--></title>
        <meta base="/">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <t-resource type="stylesheet" src="./fontawesome/css/all.css"/>
        <t-resource type="stylesheet" src="application.css"/>
        <!--Resources_Header-->
        <!--Header-->
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">Task Manager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <t-access expression="getCurrentUser">
            <ul class="navbar-nav ml-auto mr-2">
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle inline" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ${echo L::common_greeting($this->user->Nome)}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <t-link controller='user' action='show' class="dropdown-item inline" overwrite params="${return ['id'=>$this->user->id]}">
                        ${echo L::user_account}
                    </t-link>
                    <div class="dropdown-divider"></div>
                    <t-link controller='user' action='logout' class="dropdown-item inline" overwrite params="${return []}">
                        ${echo L::user_logout}
                    </t-link>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav mr-5">
                <li class="nav-item dropdown mr-5">
                    <a class="nav-link dropdown-toggle inline text-uppercase" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ${ echo ((isset(Session::getInstance()->lang)) ? Session::getInstance()->lang : 'it');}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <t-link controller='langs' action='set' class="dropdown-item inline" overwrite params="${return ['lang'=>'it']}">
                        IT
                    </t-link>
                    <t-link controller='langs' action='set' class="dropdown-item inline" overwrite params="${return ['lang'=>'en']}">
                        EN
                    </t-link>
                    </div>
                </li>
            </ul>
            </t-access>
        </nav>
        <div class="py-4 px-3" id="body">
            <!--Body-->
        </div>
        <footer class="bg-light font-small blue">
            <div class="text-right py-3 pr-2">
                <a href="mailto:luca.faggion@studenti.unipr.it"> Luca Faggion.</a>
            </div>
        </footer>
        <t-resource type="script" src="popper/popper.min.js"/>
        <t-resource type="script" src="jquery/jquery-3.3.1.min.js"/>
        <t-resource type="script" src="bootstrap/bootstrap.js"/>
        <t-resource type="script" src="application.js"/>
        <!--Resources_Body-->
    </body>
</html>