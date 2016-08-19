<?php

    $IN = [
        "varname" => [
            "a" => "vala", "b" => "valb", "c"=>"valc"
        ]
    ];

?>
<doc>
    <div go-foreach="varname as curKey => curVal">
        <span class="{{curKey}}">{{curVal}}</span>
    </div>
</doc>