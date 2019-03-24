<t-if test="${echo isset($_SESSION['error'])}">
    <div class="alert alert-danger text-center" role="alert">
        ${
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        }
    </div>
</t-if>
<?php echo isset($_SESSION['error']) ?>