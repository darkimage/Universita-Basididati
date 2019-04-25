<!-- PAGE ATTRIBUTES -->
<div test0="@{notvalid:[default]}" test="@{arr->test:[null]}" test1="@{arr->prova}">
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