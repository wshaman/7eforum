<?php
/*! Base for Controller class in MVC */

/*!
 *  This _abstract_ class hadle the most basic functions such as 
 * processing methods of a controller and settings default values in
 * controller __construct method
 */

abstract class Controller extends DBData{
    protected $table = NULL;
    protected $func_name = NULL;
    protected $arguments = NULL;
    protected $E;
    protected $showAdmin=false;
    protected $showTemplate=true;
 //   protected static $class = __CLASS__;

    function __construct(){
        global $E;
        $this->E = $E;
		DBData::__construct();
    }
    
/*! Check if current action is valid for controller */

    function process( $ar =NULL ){
        echo 0;
        if ( is_array( $ar ) ){
            echo 1;
            $ar[0] = ( $this->showAdmin ) ? "admin_".$ar[0] : $ar[0];
            if( method_exists( $this, $ar[0] ) ){
                echo 2;
                $this->func_name = array_shift( $ar );
                $this->arguments = $ar;
                return true;
            }
        }
        return false;
    }

/*! Process controllers action 
 * Parameter is an array to process
 * Eg: it can consit of function name and an argument
 * or can be NULL 
 */
    public function run( $args, $admin=false ){
//        echo get_class($this);
        $this->showAdmin = $admin;
        $this->showTemplate = true;
        if ( $this->process( $args ) ){
            ob_start();
            $this->{$this->func_name}( $this->arguments );
            T::assign( "content", ob_get_contents() );
            ob_clean();
            $this->display();
        }
    }

    public function display(){
        if( $this->showTemplate ){
            $template= TEMPLATES.strtolower(get_class( $this )).'/'.( ($this->showAdmin)?'admin/':'').str_replace('admin_','',$this->func_name.".smarty");
            if( is_file( $template ) ) $cont = T::fetch( $template );
            else $this->E->setError("no template found" );
            T::assign( "content", $cont );
            T::display( TEMPLATES."page.smarty" );
        }
    }

    protected function redirect( $page ){
        $this->run( array( $page ), $this->showAdmin );
    }


}
?>

