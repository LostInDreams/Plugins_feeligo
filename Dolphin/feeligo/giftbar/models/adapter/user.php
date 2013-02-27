<? 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/user_adapter.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../selector/userfriends.php');
class FeeligoDolUser implements FeeligoUserAdapter {

  /* constructor stores a reference to the adapted User
   */
  public function __construct($dol_user) {
    $this->user = $dol_user;
  }

  /* TODO: returns the $user's ID */
  public function id(){
    $user= $this->user;
    return intval($user['ID']);
  }

  /* TODO: returns the $user's name */
  public function name(){
    $user= $this->user;
    return $user['FirstName'] . ' ' . $user['LastName'];
  }

  /* TODO: returns the $user's username */
  public function username(){
    $user= $this->user;
    return $user['NickName'];
  }

  /* TODO: returns the URL of the $user's profile page */
  public function link(){
    $user= $this->user;
    return  getProfileLink($user['ID']);
  }

  /* TODO: returns the URL of the $user's profile picture */
  public function picture_url(){
     if (!@include_once (BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/include.php'))
          return '';
    $user= $this->user;
    $sType = 'medium';
    return $user['Avatar'] ? BX_AVA_URL_USER_AVATARS . $user['Avatar'] . ($sType == 'small' ? 'i' : '') . BX_AVA_EXT : $this->getSexPic($user['Sex'], $sType);
  }

  /* TODO: returns a FeeligoUserSelector which
   * allows to access the $user's friends
   * (for the moment, return null)
   */
 public function friends_selector(){
    return new Feeligo_Model_Selector_UserFriends($this);
  }
  
  function getSexPic($sSex, $sType = 'medium') {
      $aGenders = array (
          'female' => 'woman_',
          'Female' => 'woman_',
          'male' => 'man_',
          'Male' => 'man_',
      );        
      return getTemplateIcon(isset($aGenders[$sSex]) ? $aGenders[$sSex] . $sType . '.gif' : 'visitor_' . $sType . '.gif');
  }

} ?>