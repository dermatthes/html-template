<?php

    $IN = [
        "varname" => true
    ];

?>
<doc>
    <go-macro name="someMacro(param1, param2)">
        <div>Hello world</div>
        <div go-bind="param1"></div><div go-bind="param2"></div>
    </go-macro>
    <go-callmacro name="someMacro('a', 'b')"></go-callmacro>

    <go-callmacro name="someMacro(varname, varname)"></go-callmacro>
</doc>