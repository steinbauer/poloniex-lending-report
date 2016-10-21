<?php
function template_style()
{
    $template = '<style>
#mail { width: 600px; margin: 0 auto; position: relative; }
table { border-collapse: collapse; border: 1px solid #aaa; width: 100%; }
table td, table th { border-collapse: collapse; border: 1px solid #aaa; padding: 5px; margin: 0; }
table td { text-align: right; }
table th { background: #eee; text-align: left; }
table th.date { width: 80px; }
</style>';
    return $template;
}