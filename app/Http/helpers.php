<?php
function set_active($uri)
{
    return Request::is($uri) ? 'active' : '';
}

?>