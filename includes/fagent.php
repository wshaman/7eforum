<?php
/*! Class, controlling main forum-getting functions
 * Such as: building tree, themes strobing, etc
 */
class ForumAgent extends DBData{
    var $table = "posts";
    var $themea;
    var $parta;
    var $order = "datetime";
    private $own_data=NULL;

    function __construct(){
        $this->themea = new themeAgent();
        $this->parta = new partAgent();
        parent::__construct();
    }

//! Return all posts by User
    public function getMessagesByUser( $user_id = 1 ){
        return $this->getAll( "`user_id`=".$user_id );
    }

//! Return theme info
    public function getThemeInfo( $theme_id = 1 ){
        return $this->themea->getOne( $theme_id );
    }

//! Return a post by id
    public function getPost( $id = 1 ){
        $this->setLeftJoins( "users", "user_id", "id", array( "name", "login" ) );
        return $this->getOne( $id );
    }

//! Builds a pager menu for themes
    public function buildPager( $theme, $page, $pages=NULL ){
        if( $pages <2 ) return false;
        T::assign( "pages", $pages );
        T::assign( "pager_e", true );        
        T::assign( "theme", $theme );        
        T::assign( "current_page", $page );        
    }

//! Return all posts by theme
    public function getMessagesByTheme( $theme_id = 1, $page = 1 ){
        global $user;
        $this->setLeftJoins( "users", "user_id", "id", array( "name", "login" ) );
        $this->enablePager();
        $ppp = ( isset( $_SESSION[PROJECT]["user_conf"]["ppp"] )  ) ? $_SESSION[PROJECT]["user_conf"]["ppp"]: 10;
//	var_dump( $_SESSION[PROJECT]["user_conf"] ); die;
        $this->setPPP( $ppp );
        $this->setPage( $page );
        $r =  $this->getAll( "`theme_id`=".$theme_id );
        $this->buildPager( $theme_id, $page, $this->getTotalPages() );
        return $r;
    }
//! Returns all forum parts with themes inline
    public function getPartsThemes(){
        $p = $this->parta->getAll();
        foreach( $p as $key => $value)
            $p[$key]['themes'] = $this->getThemesForPart( $value[$this->parta->keyfield] );
        return $p;
    }
//! Returns lsit of themes by the part
    public function getThemesForPart( $id = 1 ){
        return $this->themea->getAll( " part_id=".$id );
    }
//! Deletes a post
	public function delete( $id ){
		global $user;
		$ia = $user->isAdmin();
		if( !$ia ) {
			$mi = $user->getMyID();
			$post = $this->getOne( $id );
			if( $post["user_id"] != $mi )
			return "NOTALLOWED";			
		}
		parent::delete( $id );
		return "OK";
	}
	
//! Builds a treeview
	public function buildTree( $tid ){
		$this->setLeftJoins( "users", "user_id", "id", array( "name", "login" ) );
		$all_top = $this->getAll( "theme_id=$tid AND parent=0" );
		$tree = array();
		foreach( $all_top as $item ){
			//		$item["children"] = $this->get_my_children( $post["id"] );
			$item["level"] = 0;
			$this->own_data[]=$item;
		//	var_dump( $item ); die;
			$this->get_my_children( $item["id"], 0 );
		}
//		var_dump( $this->own_data );die;
		return $this->own_data;
	}
	
	private	function get_my_children( $id, $level ){
		$level++;
		$all_children = $this->getAll( "parent=$id" );
		foreach( $all_children as $child ){
			$child["level"] = $level;
			$this->own_data[] = $child;
			$this->get_my_children( $child["id"], $level );
		}
			
	}
}
/*! Helper class to control themes */
class themeAgent extends DBData{
    var $table = "themes";
}
/*! Helper class to control parts */
class partAgent extends DBData{
    var $table = "parts";
}
?>
