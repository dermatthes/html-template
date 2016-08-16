<?php

    $IN = [
        "varname" => [
            "a", "b", "c"
        ]
    ];

?>
<doc>
    <div go-foreach="varname as curVal">
        <span go-bind="curVal"></span>
    </div>
</doc>