<?php
    if(Count($this->list) < $this->count)
        $this->pagecount = $this->count / $this->params['max'];
    if(!isset($this->params['page']))
        $this->params['page'] = 0;
    $this->currentpage = $this->params['page'];
    $this->nextpage = $this->params['page']+1;
    $this->max = $this->params['max'];
?>
<t-if test="${$this->list && $this->count != 0 && $this->max < $this->count}">
<ul class="pagination pt-3 justify-content-center">
    <li class="${return 'page-item '.(($this->currentpage != 0)?'':'disabled');}">
        <t-link controller="@{controller}" action="@{action}" class="page-link" 
        params="${return ['page'=>($this->currentpage-1),'offset'=>$this->max*($this->currentpage-1)]}">
            ${echo L::pagination_previous;} 
        </t-link>
    </li>
    <t-for count="@{pagecount}">
        <li class="${return 'page-item '.(($this->index == $this->params['page'])?'active':'');}">
            <t-link controller="@{controller}" action="@{action}" class="page-link" 
            params="${return ['page'=>$this->index,'offset'=>$this->max*$this->index]}">
                ${echo $this->index;} 
                <t-if test="${$this->index == $this->params['page']}">
                    <span class="sr-only">(current)</span>
                </t-if>
            </t-link>
        </li>
    </t-for>
    <li class="${ return 'page-item '.(($this->currentpage < $this->pagecount-1)?'':'disabled');}">
        <t-link controller="@{controller}" action="@{action}" class="page-link" 
            params="${return ['page'=>$this->nextpage,'offset'=>$this->max*$this->nextpage]}">
                ${echo L::pagination_next;} 
        </t-link>
    </li>
</ul>
</t-if>