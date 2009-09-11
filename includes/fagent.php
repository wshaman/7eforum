<?php
/*! Class, controlling main forum-getting functions
 * Such as: building tree, themes strobing, etc
 */
class ForumAgent extends DBData{
    var $table = "posts";
    var $themea;
    var $parta;
    var $order = "datetime";

    function __construct(){
        $this->themea = new themeAgent();
        $this->parta = new partAgent();
        parent::__construct();
    }

/*! Return all posts by User*/
    public function getMessagesByUser( $user_id = 1 ){
        return $this->getAll( "`user_id`=".$user_id );
    }

/*! Return theme info*/
    public function getThemeInfo( $theme_id = 1 ){
        return $this->themea->getOne( $theme_id );
    }

/*! Return a post by id*/
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

/*! Return all posts by theme*/
    public function getMessagesByTheme( $theme_id = 1, $page = 1 ){
        global $user;
        $this->setLeftJoins( "users", "user_id", "id", array( "name", "login" ) );
        $this->enablePager();
        $ppp = ( isset( $_SESSION["user_conf"]["ppp"] )  ) ? $_SESSION["user_conf"]["ppp"]: 10;
        $this->setPPP( $ppp );
        $this->setPage( $page );
        $r =  $this->getAll( "`theme_id`=".$theme_id );
        $this->buildPager( $theme_id, $page, $this->getTotalPages() );
        return $r;
    }
/*! Returns all forum parts with themes inline */
    public function getPartsThemes(){
        $p = $this->parta->getAll();
        foreach( $p as $key => $value)
            $p[$key]['themes'] = $this->getThemesForPart( $value[$this->parta->keyfield] );
        return $p;
    }
    public function getThemesForPart( $id = 1 ){
        return $this->themea->getAll( " part_id=".$id );
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
