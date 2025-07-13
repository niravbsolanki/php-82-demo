<?php
$blocks = [];

function startblock($name) {
    ob_start();
    $GLOBALS['__current_block'] = $name;
}

function endblock() {
    $block = $GLOBALS['__current_block'];
    $GLOBALS['blocks'][$block] = ob_get_clean();
}

function renderblock($name) {
    if (isset($GLOBALS['blocks'][$name])) {
        echo $GLOBALS['blocks'][$name];
    }
}
?>
