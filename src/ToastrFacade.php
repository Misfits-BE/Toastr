<?php 

namespace ActivismeBe\Toastr; 

use Illuminate\Support\Facades\Facade; 

/**
 * Class ToastrFacade
 * ---- 
 * Method for registering the facade form the Toastr package 
 *
 * @author      Kamal Nasser <kamal@kamalnasser.net>
 * @copyright   2013 Kamal Nasser <kamal@kamalnasser.net>
 * @package     ActivismeBe\Toastr
 */
class ToastrFacade extends Facade 
{
    /**
     * Get the register name of the component. 
     * 
     * @return string
     */
    protected static function getFacadeAccessor(): string 
    {
        return "Toastr";
    }
}