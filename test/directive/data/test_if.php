<?php

    $IN = [
        "varname" => "test"
    ];

?>
<doc>
    <div go-if="varname == 'test'">OK</div>
    <div go-if="varname != 'test'">ERR</div>
</doc>