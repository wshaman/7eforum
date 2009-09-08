<?php
/*! Class, controlling main forum-getting functions
 * Such as: building tree, themes strobing, etc
 */
class ForumAgent extends DBData{
    var $table = "posts";
    var $themea;
    var $parta;

    function __construct(){
        $this->themea = new themeAgent();
        $this->parta = new partAgent();
        parent::__construct();
    }

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
