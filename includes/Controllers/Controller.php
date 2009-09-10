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
    private   $generatedText = '';
 //   protected static $class = __CLASS__;

    function __construct(){
        global $E;
        $this->E = $E;
		DBData::__construct();
    }
  /*! default action - just display index.smarty */  
    public function index(){
    
    }
/*! Check if current action is valid for controller */
    function process( $ar =NULL ){
//        var_dump( $ar ); die;
        if ( is_array( $ar ) && isset( $ar[0] )){
            $ar[0] = ( $this->showAdmin ) ? "admin_".$ar[0] : $ar[0];
            if( !empty( $ar[0] ) && ( method_exists( $this, $ar[0] ) ) ){
 //               echo "111"; die;
                $this->func_name = array_shift( $ar );
                $this->arguments = $ar;
                return true;
            } else {
//                echo "222"; die;
                $this->func_name = "index";
                $this->arguments = array();
                return true;
            }
        } else {
            $this->func_name = "index";
            $this->arguments = array();
            return true;
        }
//        return false;
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
            $this->generatedText = ob_get_contents();
//            echo $content;
//            T::assign( "content", $content );
            ob_clean();
//            var_dump( $this->generatedText );
            $this->display();
        }
    }

    public function display(){
        global $user;
        if( $this->showTemplate ){
            $template= TEMPLATES.strtolower(str_replace("Controller",'',get_class( $this ))).'/'.( ($this->showAdmin)?'admin/':'').str_replace('admin_','',$this->func_name.".smarty");
        } else {
            T::assign( "empty_display", $this->generatedText );
            $template=TEMPLATES."empty.smarty";
            $this->generatedText = '';
        }
        ob_start();
        if( is_file( $template ) ) $c_template = T::display( $template );
        else $this->E->setError("no template found" );
        if( !$this->showTemplate ) T::assign( "empty_display", '' );
        T::assign( "USER_NAME", $user->isLoged() );
        T::assign( "content_on_page", ob_get_contents() );
        ob_clean();
        T::display( TEMPLATES."layout.smarty" );
    }

    protected function redirect( $page ){
        $this->run( array( $page ), $this->showAdmin );
    }


}
?>

