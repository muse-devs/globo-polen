<?php

namespace Polen\Includes;

class Polen_DisableAdminBar
{
    function __construct()
    {
        show_admin_bar(false);
    }

}
