<html>
    <head>
        <title><!--Title--></title>
        <meta base="/">
        <link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
        <!--Header-->
    </head>

    <body>
        <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">Task Manager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
        <div class="py-4 px-3">
            <?php echo $model['test']; ?>
            <?php echo L::greeting; ?>
            <!--Body-->
        </div>
        <footer class="bg-light font-small blue">
            <div class="text-right py-3 pr-2">
                <a href="mailto:luca.faggion@studenti.unipr.it"> Luca Faggion.</a>
            </div>
        </footer>
        <script src="/bootstrap/js/bootstrap.js"></script>
    </body>
</html>