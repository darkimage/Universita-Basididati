<h1><?php echo $this->title ?></h1>
<div class="container-fluid pt-3">
<div class="row">
<div class="col-2">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <t-tablink id="test"><?php echo L::controlpanel_addproject ?></t-tablink>
        <t-tablink id="home" status="active">Home</t-tablink>
        <t-tablink id="profile">Profile</t-tablink>
        <t-tablink id="messages">Messages</t-tablink>
        <t-tablink id="settings">Settings</t-tablink>
    </div>
</div>
<div class="col">
    <div class="tab-content" id="v-pills-tabContent">
        <t-tabcontent id="home" status="show active">
            Home
        </t-tabcontent>
        <t-tabcontent id="profile">
            Profileeee
        </t-tabcontent>
        <t-tabcontent id="messages">
            messages
        </t-tabcontent>
        <t-tabcontent id="settings">
            Settings
        </t-tabcontent>
        <t-tabcontent id="test">
            <t-template path="/templates/forms/project_form.php" model="@{templatemodel}"></t-template>
        </t-tabcontent>
        </div>
    </div>
</div>
</div>