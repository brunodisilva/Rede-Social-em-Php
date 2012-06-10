<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * File:     modifier.price_format.php
 * Type:     modifier
 * Name:     format_price
 * Date:     Sep 03, 2004
 * Purpose:  Format given value according to the locale settings.
 *           A second argument can be supplied which controls the left precision (default is 2).
 *           Input will be converted to float, if conversion fails input will be returned unchanged.
 * Example:  {$price|price_fomat}
 *           {$price|price_format:2}
 * Install:  Drop into the plugin directory.
 * @version  1.1
 * @author   Markus Zapke-Gruendemann <markuszapke at gmx dot net>
 * @param float
 * @param int
 * @return string
 */
function smarty_modifier_price_format($float, $places = 2)
{
    if (!(float) $float) {
        return $float;
    }
    
    return money_format("%.{$places}n", $float);
}

/* vim: set expandtab: */

?>
