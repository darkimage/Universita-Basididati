<!-- PAGE ATTRIBUTES -->
<div test3="@{testarr['hi'][1]}" test0="@{notvalid:[default]}" test="@{arr->test:[null]}" test1="@{arr->prova}">
    inspect div for test attributes
</div>

<!-- IF NODE VALID EXPRESSIONS -->
<t-if test="@{test:[ok]}">
    ok page attr
</t-if>

<t-if test="true">
    ok normal
</t-if>

<t-if test="${ $this->arr }">
    ok php node
</t-if>

<t-if test="@{arr:[false]}">
    ok page attr with valid attr
</t-if>

<!-- LINK EXPRESSIONS -->
<t-link controller="project" action="addForm">Link to Project</t-link><br>
<t-link href="/test">Link to a 404 page</t-link>

<t-each collection="@{testarr}" item="item">
ciao!
</t-each>