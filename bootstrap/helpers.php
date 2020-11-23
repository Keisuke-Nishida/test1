<?php

function langtext($code)
{
    return config('languages.' . env('LANG_CODE') . '.' . $code);
}
